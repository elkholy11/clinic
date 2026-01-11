<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'phone', 'gender', 'birth_date', 'user_id'];

    /**
     * Get the user associated with the patient.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function report(){
        return $this->hasOne(Report::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    public function medicalHistory()
    {
        return $this->hasMany(MedicalHistory::class);
    }
}
