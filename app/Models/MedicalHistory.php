<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalHistory extends Model
{
    use HasFactory;

    protected $table = 'medical_history';

    protected $fillable = [
        'patient_id',
        'record_type',
        'title',
        'description',
        'date',
        'doctor_id',
        'attachments',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Get the patient associated with the medical history.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the doctor associated with the medical history.
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
