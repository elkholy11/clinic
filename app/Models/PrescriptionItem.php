<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrescriptionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'prescription_id',
        'medication_id',
        'quantity',
        'dosage',
        'frequency',
        'duration',
        'instructions',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'duration' => 'integer',
    ];

    /**
     * Get the prescription associated with the item.
     */
    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }

    /**
     * Get the medication associated with the item.
     */
    public function medication()
    {
        return $this->belongsTo(Medication::class);
    }
}
