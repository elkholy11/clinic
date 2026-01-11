<?php

namespace App\Policies;

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InvoicePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Admin and Doctor can view all invoices
        // Regular users can view their own invoices only
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Invoice $invoice): bool
    {
        // Admin and Doctor can view all invoices
        if ($user->isAdmin() || $user->isDoctor()) {
            return true;
        }
        
        // Regular users can view their own invoices only
        return $invoice->patient_id === $user->patient?->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only Admin and Doctor can create invoices
        return $user->isAdmin() || $user->isDoctor();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Invoice $invoice): bool
    {
        // Only Admin can update invoices
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Invoice $invoice): bool
    {
        // Only Admin can delete invoices
        return $user->isAdmin();
    }
}
