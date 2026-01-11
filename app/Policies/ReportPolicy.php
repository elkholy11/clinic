<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\User;

class ReportPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view reports (filtered in controller)
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Report $report): bool
    {
        // Admin can view all reports
        if ($user->isAdmin()) {
            return true;
        }
        
        // Doctor can only view their reports
        if ($user->isDoctor() && $user->doctor && $report->doctor_id === $user->doctor->id) {
            return true;
        }
        
        // Patient can only view their own reports
        if ($user->patient && $report->patient_id === $user->patient->id) {
            return true;
        }
        
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only admins and doctors can create reports
        // Patients cannot create reports - only view their own
        return $user->isAdmin() || ($user->isDoctor() && $user->doctor);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Report $report): bool
    {
        // Admin can update all reports
        if ($user->isAdmin()) {
            return true;
        }
        
        // Doctor can only update their reports
        return $user->isDoctor() && $user->doctor && $report->doctor_id === $user->doctor->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Report $report): bool
    {
        // Only admins can delete reports
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Report $report): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Report $report): bool
    {
        return $user->isAdmin();
    }
}
