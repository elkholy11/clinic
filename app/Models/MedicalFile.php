<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalFile extends Model
{
    use HasFactory;
    
    protected $fillable = ['patient_id', 'file_name', 'description'];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
