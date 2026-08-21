<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    protected $fillable = ['employee_code', 'name', 'is_active'];

    public function carenderiaItems(): HasMany 
    { 
        return $this->hasMany(CarenderiaItem::class); 
    }
  
    public function loanItems(): HasMany 
    { 
        return $this->hasMany(LoanItem::class); 
    }

    public function groceryItems(): HasMany 
    { 
        return $this->hasMany(GroceryItem::class); 
    }
    
    public function paymentItems(): HasMany 
    { 
        return $this->hasMany(PaymentItem::class); 
    }
}