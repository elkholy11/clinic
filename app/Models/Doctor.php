<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'specialty',
        'phone',
        'email',
        'address'
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
    
    public function report()
    {
        return $this->hasMany(Report::class);
    }

    /**
     * Get the user associated with the doctor.
     */
    public function user()
    {
        return $this->hasOne(User::class);
    }

    /**
     * Get patients through appointments.
     */
    public function patients()
    {
        return Patient::whereHas('appointments', function($query) {
            $query->where('doctor_id', $this->id);
        });
    }
}
