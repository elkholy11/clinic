<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Medication;
use Illuminate\Auth\Access\HandlesAuthorization;

class MedicationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Admin and Doctor can view all medications
        // Regular users can view active medications only
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Medication $medication): bool
    {
        // Admin and Doctor can view all medications
        // Regular users can view active medications only
        if ($user->isAdmin() || $user->isDoctor()) {
            return true;
        }
        
        return $medication->is_active;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only Admin can create medications
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Medication $medication): bool
    {
        // Only Admin can update medications
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Medication $medication): bool
    {
        // Only Admin can delete medications
        return $user->isAdmin();
    }
}
