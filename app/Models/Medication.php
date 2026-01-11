<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medication extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'generic_name',
        'dosage',
        'unit',
        'price',
        'stock_quantity',
        'expiry_date',
        'manufacturer',
        'description',
        'category',
        'requires_prescription',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'expiry_date' => 'date',
        'requires_prescription' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get prescription items for this medication.
     */
    public function prescriptionItems()
    {
        return $this->hasMany(PrescriptionItem::class);
    }

    /**
     * Check if medication is in stock.
     */
    public function isInStock(): bool
    {
        return $this->stock_quantity > 0;
    }

    /**
     * Check if medication is expired.
     */
    public function isExpired(): bool
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }
}
