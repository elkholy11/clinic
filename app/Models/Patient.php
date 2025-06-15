<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'phone', 'gender', 'birth_date'];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function medicalFiles()
    {
        return $this->hasMany(MedicalFile::class);
    }
    public function report(){
        return $this->hasOne(Report::class);
    }
}
