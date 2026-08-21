<?php

namespace App\Models;

use App\Traits\SearchModuleByEmployee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoanItem extends Model
{
    use SearchModuleByEmployee;
    
    protected $fillable = ['upload_id', 'employee_id', 'total', 'date'];

    protected $casts = [
        'date' => 'date',
        'total' => 'decimal:2',
    ];

    public function employee(): BelongsTo { return $this->belongsTo(Employee::class); }
    public function upload(): BelongsTo { return $this->belongsTo(UploadedFile::class); }
}