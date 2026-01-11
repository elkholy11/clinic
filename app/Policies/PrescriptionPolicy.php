<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Prescription;
use Illuminate\Auth\Access\HandlesAuthorization;

class PrescriptionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Admin, Doctor, and Regular users can view prescriptions
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Prescription $prescription): bool
    {
        // Admin can view all prescriptions
        if ($user->isAdmin()) {
            return true;
        }
        
        // Doctor can view their own prescriptions
        if ($user->isDoctor() && $user->doctor && $prescription->doctor_id === $user->doctor->id) {
            return true;
        }
        
        // Regular users can view their own prescriptions
        if ($user->patient && $prescription->patient_id === $user->patient->id) {
            return true;
        }
        
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Admin and Doctor can create prescriptions
        return $user->isAdmin() || $user->isDoctor();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Prescription $prescription): bool
    {
        // Admin can update all prescriptions
        if ($user->isAdmin()) {
            return true;
        }
        
        // Doctor can update their own prescriptions
        if ($user->isDoctor() && $user->doctor && $prescription->doctor_id === $user->doctor->id) {
            return true;
        }
        
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Prescription $prescription): bool
    {
        // Only Admin can delete prescriptions
        return $user->isAdmin();
    }
}
