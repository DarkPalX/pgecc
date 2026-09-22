<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberClass extends Model
{
    protected $fillable = ['name', 'carenderia_limit', 'consumer_limit', 'maximum_loan'];

    protected $casts = [
        'carenderia_limit' => 'float',
        'consumer_limit' => 'float',
        'maximum_loan' => 'float',
    ];
}
