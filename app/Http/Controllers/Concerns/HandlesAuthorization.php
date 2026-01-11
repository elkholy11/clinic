<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Support\Facades\Auth;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Report;
use Illuminate\Pagination\LengthAwarePaginator;

trait HandlesAuthorization
{
    /**
     * Check if current user is admin.
     */
    protected function isAdmin(): bool
    {
        return Auth::check() && Auth::user()->isAdmin();
    }

    /**
     * Check if current user is doctor.
     */
    protected function isDoctor(): bool
    {
        return Auth::check() && Auth::user()->isDoctor();
    }

    /**
     * Get current authenticated user.
     */
    protected function currentUser()
    {
        return Auth::user();
    }

    /**
     * Get patients based on user role.
     * 
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    protected function getPatientsForUser()
    {
        $user = $this->currentUser();
        
        if ($this->isAdmin()) {
            return Patient::all();
        } elseif ($this->isDoctor() && $user->doctor) {
            return Patient::whereHas('appointments', function($query) use ($user) {
                $query->where('doctor_id', $user->doctor->id);
            })->get();
        }
        
        return collect();
    }

    /**
     * Get appointments based on user role.
     * 
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    protected function getAppointmentsForUser()
    {
        $user = $this->currentUser();
        
        if ($this->isAdmin()) {
            return Appointment::all();
        } elseif ($this->isDoctor() && $user->doctor) {
            return Appointment::where('doctor_id', $user->doctor->id)->get();
        }
        
        return collect();
    }

    /**
     * Get reports based on user role.
     * 
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    protected function getReportsForUser()
    {
        $user = $this->currentUser();
        
        if ($this->isAdmin()) {
            return Report::all();
        } elseif ($this->isDoctor() && $user->doctor) {
            return Report::where('doctor_id', $user->doctor->id)->get();
        }
        
        return collect();
    }

    /**
     * Get patient IDs for doctor.
     * 
     * @return \Illuminate\Support\Collection
     */
    protected function getDoctorPatientIds()
    {
        $user = $this->currentUser();
        
        if ($this->isDoctor() && $user->doctor) {
            return Patient::whereHas('appointments', function($query) use ($user) {
                $query->where('doctor_id', $user->doctor->id);
            })->pluck('id');
        }
        
        return collect();
    }

    /**
     * Handle exceptions with consistent error response.
     * 
     * @param \Exception $e
     * @param string|null $redirectRoute
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function handleException(\Exception $e, $redirectRoute = null)
    {
        \Log::error($e->getMessage(), [
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
        
        $redirectRoute = $redirectRoute ?? (Auth::check() && $this->isAdmin() 
            ? route('admin.dashboard') 
            : route('user.dashboard'));
        
        return redirect()->back()
            ->withInput()
            ->withErrors(['error' => __('messages.error_occurred') . ': ' . $e->getMessage()]);
    }

    /**
     * Get filtered items based on user role for index methods.
     * 
     * @param string $modelClass Full model class name (e.g., App\Models\Invoice::class)
     * @param string $patientIdColumn Column name for patient_id (default: 'patient_id')
     * @param array $relationships Relationships to eager load
     * @param int $perPage Items per page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    protected function getFilteredItems($modelClass, $patientIdColumn = 'patient_id', $relationships = [], $perPage = 10)
    {
        $user = $this->currentUser();
        
        if ($this->isAdmin()) {
            return $modelClass::with($relationships)->latest()->paginate($perPage);
        } elseif ($this->isDoctor() && $user->doctor) {
            $patientIds = $this->getDoctorPatientIds();
            
            if ($patientIds->isEmpty()) {
                return $modelClass::whereIn('id', [])
                    ->with($relationships)
                    ->latest()
                    ->paginate($perPage);
            }
            
            return $modelClass::whereIn($patientIdColumn, $patientIds)
                ->with($relationships)
                ->latest()
                ->paginate($perPage);
        } else {
            // Regular user
            if ($user->patient) {
                return $modelClass::where($patientIdColumn, $user->patient->id)
                    ->with($relationships)
                    ->latest()
                    ->paginate($perPage);
            }
            
            // Return empty paginated result
            return $modelClass::whereIn('id', [])
                ->with($relationships)
                ->latest()
                ->paginate($perPage);
        }
    }
}


