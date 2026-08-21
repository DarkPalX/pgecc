<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeSearch extends Model
{
    // Point explicitly to the view
    protected $table = 'employee_search_view';
    
    // Disable writes since it's a view
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'date' => 'date',
        'total' => 'decimal:2',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function upload(): BelongsTo
    {
        return $this->belongsTo(UploadedFile::class);
    }
}