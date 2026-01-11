<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_id',
        'patient_id',
        'doctor_id',
        'prescription_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'prescription_date' => 'date',
    ];

    /**
     * Get the report associated with the prescription.
     */
    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    /**
     * Get the patient associated with the prescription.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the doctor associated with the prescription.
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Get prescription items.
     */
    public function items()
    {
        return $this->hasMany(PrescriptionItem::class);
    }
}
