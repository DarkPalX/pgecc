<?php

namespace App\Imports;

use App\Models\EmployeeBalance;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeImport;

class EmployeeBalancesImport implements ToCollection, WithStartRow, WithChunkReading, WithEvents
{
    /**
     * Clear the entire table once before the import starts
     */
    public function registerEvents(): array
    {
        return [
            BeforeImport::class => function (BeforeImport $event) {
                // Uses DELETE instead of TRUNCATE to avoid SQL Server foreign key / scope locks
                EmployeeBalance::query()->delete();
            },
        ];
    }

    public function startRow(): int
    {
        // The dashboard CSV has one header row; employee data starts on row 2.
        return 2;
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function collection(Collection $rows)
    {
        $cleanNum = function ($val) {
            if ($val === null || $val === '' || $val === '-') return 0.0;
            $str = trim((string)$val);

            if (str_starts_with($str, '=')) {
                return 0.0;
            }

            if (str_starts_with($str, '(') && str_ends_with($str, ')')) {
                $str = '-' . substr($str, 1, -1);
            }

            $numeric = preg_replace('/[^0-9.-]/', '', $str);

            if (!is_numeric($numeric)) {
                return 0.0;
            }

            $floatVal = (float)$numeric;

            if (abs($floatVal) > 999999999999.99) {
                return 0.0;
            }

            return $floatVal;
        };

        foreach ($rows as $row) {
            $rawPmcId = $row[2] ?? null;
            $pmcId = $rawPmcId !== null ? trim((string)$rawPmcId) : '';

            if (
                empty($pmcId) || 
                str_starts_with($pmcId, '=') ||
                str_contains(strtolower($pmcId), 'reclass') || 
                strtolower($pmcId) === 'total' ||
                strtolower($pmcId) === 'id' ||
                strlen($pmcId) > 20
            ) {
                continue;
            }

            $rawLoc = (string)($row[0] ?? '');
            $location = (!empty($rawLoc) && !str_starts_with($rawLoc, '=')) 
                ? $rawLoc 
                : substr($pmcId, 0, 1);

            $name = trim((string)($row[3] ?? ''));
            if (str_starts_with($name, '=')) {
                continue;
            }

            $shareCapital = $cleanNum($row[7] ?? 0);

            $rawMemClass = (string)($row[1] ?? '');
            $memClass = (!empty($rawMemClass) && !str_starts_with($rawMemClass, '='))
                ? $rawMemClass 
                : $this->calculateMemClass($shareCapital);

            EmployeeBalance::create([
                'pmc_id'            => $pmcId,
                'location'          => $location,
                'mem_class'         => $memClass,
                'name'              => $name,
                'department'        => !str_starts_with((string)($row[4] ?? ''), '=') ? ($row[4] ?? null) : null,
                'member_status'     => !empty($row[5]) && !str_starts_with((string)$row[5], '=') ? strtoupper(trim((string)$row[5])) : 'ACTIVE',
                'carenderia_waived' => $row[6] ?? null,
                'share_capital'     => $shareCapital,
                'short_term_loan'   => $cleanNum($row[8] ?? 0),
                'carenderia_bal'    => $cleanNum($row[9] ?? 0),
                'consumer_bal'      => $cleanNum($row[10] ?? 0),
                'long_term_loan'    => $cleanNum($row[11] ?? 0),
                'total_balances'    => $cleanNum($row[12] ?? 0),
                'consumer_remarks'  => $row[13] ?? null,
                'exit_cancellation' => $row[14] ?? null,
            ]);
        }
    }

    private function calculateMemClass(float $shareCapital): string
    {
        if ($shareCapital < 1000) return 'NA';
        if ($shareCapital < 5000) return 'COPPER';
        if ($shareCapital < 13000) return 'BRONZE';
        if ($shareCapital < 21000) return 'SILVER';
        if ($shareCapital < 33000) return 'GOLD';
        if ($shareCapital < 57000) return 'DIAMOND';
        if ($shareCapital < 98000) return 'TITANIUM';
        return 'PLATINUM';
    }
}
