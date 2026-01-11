<?php

namespace App\Policies;

use App\Models\User;
use App\Models\MedicalHistory;
use Illuminate\Auth\Access\HandlesAuthorization;

class MedicalHistoryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Admin, Doctor, and Regular users can view medical history
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MedicalHistory $medicalHistory): bool
    {
        // Admin can view all medical history
        if ($user->isAdmin()) {
            return true;
        }
        
        // Doctor can view medical history of their patients
        if ($user->isDoctor() && $user->doctor) {
            // Check if this medical history belongs to a patient of this doctor
            $patient = $medicalHistory->patient;
            if ($patient && $patient->appointments()->where('doctor_id', $user->doctor->id)->exists()) {
                return true;
            }
        }
        
        // Regular users can view their own medical history
        if ($user->patient && $medicalHistory->patient_id === $user->patient->id) {
            return true;
        }
        
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Admin and Doctor can create medical history
        return $user->isAdmin() || $user->isDoctor();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MedicalHistory $medicalHistory): bool
    {
        // Admin can update all medical history
        if ($user->isAdmin()) {
            return true;
        }
        
        // Doctor can update medical history they created or for their patients
        if ($user->isDoctor() && $user->doctor) {
            // Check if this medical history belongs to a patient of this doctor
            $patient = $medicalHistory->patient;
            if ($patient && $patient->appointments()->where('doctor_id', $user->doctor->id)->exists()) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MedicalHistory $medicalHistory): bool
    {
        // Only Admin can delete medical history
        return $user->isAdmin();
    }
}
