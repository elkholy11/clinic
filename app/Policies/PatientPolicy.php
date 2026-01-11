<?php

namespace App\Policies;

use App\Models\Patient;
use App\Models\User;

class PatientPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Admin can view all, Doctor can view (but will be filtered in controller)
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Patient $patient): bool
    {
        // Admin can view all patients
        if ($user->isAdmin()) {
            return true;
        }
        
        // Doctor can only view their patients
        if ($user->isDoctor() && $user->doctor) {
            return $user->doctor->patients()->where('patients.id', $patient->id)->exists();
        }
        
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only admins can create patients directly
        // Doctors should create appointments instead
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Patient $patient): bool
    {
        // Admin can update all patients
        if ($user->isAdmin()) {
            return true;
        }
        
        // Doctor can only update their patients
        if ($user->isDoctor() && $user->doctor) {
            return $user->doctor->patients()->where('patients.id', $patient->id)->exists();
        }
        
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Patient $patient): bool
    {
        // Admin can delete all patients
        if ($user->isAdmin()) {
            return true;
        }
        
        // Doctor can only delete their patients
        if ($user->isDoctor() && $user->doctor) {
            return $user->doctor->patients()->where('patients.id', $patient->id)->exists();
        }
        
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Patient $patient): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Patient $patient): bool
    {
        return $user->isAdmin();
    }
}
