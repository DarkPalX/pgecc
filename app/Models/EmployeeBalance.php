<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'location',
        'mem_class',
        'pmc_id',
        'name',
        'department',
        'member_status',
        'carenderia_waived',
        'share_capital',
        'short_term_loan',
        'carenderia_bal',
        'consumer_bal',
        'long_term_loan',
        'total_balances',
        'consumer_remarks',
        'exit_cancellation',
    ];
}