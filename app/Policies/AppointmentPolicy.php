<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\User;

class AppointmentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view appointments (filtered in controller)
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Appointment $appointment): bool
    {
        // Admin can view all appointments
        if ($user->isAdmin()) {
            return true;
        }
        
        // Doctor can only view their appointments
        return $user->isDoctor() && $user->doctor && $appointment->doctor_id === $user->doctor->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // All authenticated users can create appointments
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Appointment $appointment): bool
    {
        // Admin can update all appointments
        if ($user->isAdmin()) {
            return true;
        }
        
        // Doctor can only update their appointments
        return $user->isDoctor() && $user->doctor && $appointment->doctor_id === $user->doctor->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Appointment $appointment): bool
    {
        // Admin can delete all appointments
        if ($user->isAdmin()) {
            return true;
        }
        
        // Doctor can only delete their appointments
        return $user->isDoctor() && $user->doctor && $appointment->doctor_id === $user->doctor->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Appointment $appointment): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Appointment $appointment): bool
    {
        return $user->isAdmin();
    }
}
