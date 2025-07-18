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
}
