<?php

namespace App\Policies;

use App\Models\Doctor;
use App\Models\User;

class DoctorPolicy
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
    public function view(User $user, Doctor $doctor): bool
    {
        // Admin can view all doctors
        if ($user->isAdmin()) {
            return true;
        }
        
        // Doctor can only view themselves
        return $user->isDoctor() && $user->doctor && $user->doctor->id === $doctor->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only admins can create doctors
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Doctor $doctor): bool
    {
        // Admin can update all doctors
        if ($user->isAdmin()) {
            return true;
        }
        
        // Doctor can only update themselves
        return $user->isDoctor() && $user->doctor && $user->doctor->id === $doctor->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Doctor $doctor): bool
    {
        // Only admins can delete doctors
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Doctor $doctor): bool
    {
        // Only admins can restore doctors
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Doctor $doctor): bool
    {
        // Only admins can permanently delete doctors
        return $user->isAdmin();
    }
}
