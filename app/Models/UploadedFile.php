<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UploadedFile extends Model
{
    protected $fillable = [
        'original_filename', 
        'storage_path', 
        'module_type', 
        'status', 
        'row_count', 
        'uploaded_by'
    ];

    // --- Relationships ---
    
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    // Generic relationships to items if you ever need to pull items linked to a specific file
    public function carenderiaItems(): HasMany { return $this->hasMany(CarenderiaItem::class); }
    public function loanItems(): HasMany { return $this->hasMany(LoanItem::class); }
    public function consumerBalanceItems(): HasMany { return $this->hasMany(ConsumerBalanceItem::class); }
    public function groceryItems(): HasMany { return $this->hasMany(GroceryItem::class); }
    public function paymentItems(): HasMany { return $this->hasMany(PaymentItem::class); }


    // --- Query Scopes for Easy Filtering & Search ---

    // Filter by Module (carenderia, loan, etc.)
    public function scopeOfModule(Builder $query, string $module): Builder
    {
        return $query->where('module_type', $module);
    }

    // Search by Filename
    public function scopeSearch(Builder $query, ?string $searchTerm): Builder
    {
        return $query->when($searchTerm, function ($q) use ($searchTerm) {
            $q->where('original_filename', 'LIKE', "%{$searchTerm}%");
        });
    }
}
