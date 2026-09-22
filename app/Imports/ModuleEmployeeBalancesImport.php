<?php

namespace App\Imports;

use App\Models\EmployeeBalance;
use App\Models\UploadedFile;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ModuleEmployeeBalancesImport implements ToCollection, WithStartRow, WithChunkReading
{
    public function __construct(
        private readonly string $module,
        private readonly int $uploadId
    ) {}

    public function startRow(): int
    {
        // The same dashboard CSV has one header row; employee data starts on row 2.
        return 2;
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function collection(Collection $rows): void
    {
        $processed = 0;

        foreach ($rows as $row) {
            $pmcId = trim((string) ($row[2] ?? ''));

            if (
                $pmcId === '' ||
                str_starts_with($pmcId, '=') ||
                str_contains(strtolower($pmcId), 'reclass') ||
                in_array(strtolower($pmcId), ['total', 'id'], true) ||
                strlen($pmcId) > 20
            ) {
                continue;
            }

            $values = $this->moduleValues($row);

            // Ignore employees with no balance in the selected module.
            if ($values === []) {
                continue;
            }

            $employee = EmployeeBalance::firstOrNew(['pmc_id' => $pmcId]);

            if (!$employee->exists) {
                $employee->fill([
                    'location' => $this->safeText($row[0] ?? null) ?: substr($pmcId, 0, 1),
                    'mem_class' => $this->safeText($row[1] ?? null) ?: 'NA',
                    'name' => $this->safeText($row[3] ?? null) ?: $pmcId,
                    'department' => $this->safeText($row[4] ?? null),
                    'member_status' => strtoupper($this->safeText($row[5] ?? null) ?: 'ACTIVE'),
                ]);
            }

            // Only the selected module columns are changed. Other balances remain intact.
            $employee->fill($values);
            $employee->save();
            $processed++;
        }

        $upload = UploadedFile::find($this->uploadId);
        if ($upload) {
            $upload->increment('row_count', $processed);
        }
    }

    private function moduleValues(array|Collection $row): array
    {
        $carenderia = $this->number($row[9] ?? 0);
        $consumer = $this->number($row[10] ?? 0);
        $shortTermLoan = $this->number($row[8] ?? 0);
        $longTermLoan = $this->number($row[11] ?? 0);

        return match ($this->module) {
            'carenderia' => $carenderia != 0.0
                ? ['carenderia_bal' => $carenderia]
                : [],
            'consumer_balances' => $consumer != 0.0
                ? ['consumer_bal' => $consumer]
                : [],
            'loan' => ($shortTermLoan != 0.0 || $longTermLoan != 0.0)
                ? [
                    'short_term_loan' => $shortTermLoan,
                    'long_term_loan' => $longTermLoan,
                ]
                : [],
            default => [],
        };
    }

    private function number(mixed $value): float
    {
        if ($value === null || $value === '' || $value === '-') {
            return 0.0;
        }

        $value = trim((string) $value);
        if (str_starts_with($value, '(') && str_ends_with($value, ')')) {
            $value = '-' . substr($value, 1, -1);
        }

        $value = preg_replace('/[^0-9.-]/', '', $value);

        return is_numeric($value) ? (float) $value : 0.0;
    }

    private function safeText(mixed $value): ?string
    {
        $value = trim((string) ($value ?? ''));

        return $value === '' || str_starts_with($value, '=') ? null : $value;
    }
}
