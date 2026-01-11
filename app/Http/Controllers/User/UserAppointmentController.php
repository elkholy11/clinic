<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Http\Requests\StoreUserAppointmentRequest;
use App\Http\Requests\UpdateUserAppointmentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAppointmentController extends Controller
{
    /**
     * Display a listing of the user's appointments.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Ensure user is not admin
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        // Get user's patient profile
        $patient = $user->patient;
        
        if (!$patient) {
            $appointments = collect();
        } else {
            $appointments = Appointment::with(['patient', 'doctor'])
                ->where('patient_id', $patient->id)
                ->latest()
                ->paginate(10);
        }
        
        return view('user.appointments.index', compact('appointments'));
    }

    /**
     * Show the form for creating a new appointment.
     */
    public function create()
    {
        $user = Auth::user();
        
        // Ensure user is not admin
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        // Get all doctors for selection
        $doctors = Doctor::all();
        
        // Get or create patient profile for this user
        $patient = Patient::firstOrCreate(
            ['user_id' => $user->id],
            [
                'name' => $user->name,
                'email' => $user->email,
            ]
        );
        
        return view('user.appointments.create', compact('doctors', 'patient'));
    }

    /**
     * Store a newly created appointment.
     */
    public function store(StoreUserAppointmentRequest $request)
    {
        $user = Auth::user();
        
        // Ensure user is not admin
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        $validated = $request->validated();
        
        // Get or create patient profile
        $patient = Patient::firstOrCreate(
            ['user_id' => $user->id],
            [
                'name' => $user->name,
                'email' => $user->email,
            ]
        );
        
        // Set patient_id to current user's patient profile
        $validated['patient_id'] = $patient->id;
        
        // Combine date and time into appointment_date
        if (isset($validated['appointment_date']) && isset($validated['appointment_time'])) {
            $validated['appointment_date'] = $validated['appointment_date'] . ' ' . $validated['appointment_time'];
            unset($validated['appointment_time']);
        }
        
        Appointment::create($validated);

        return redirect()->route('user.appointments.index')
            ->with('success', __('messages.created_successfully'));
    }

    /**
     * Display the specified appointment.
     */
    public function show(Appointment $appointment)
    {
        $user = Auth::user();
        
        // Ensure user owns this appointment
        $patient = $user->patient;
        if (!$patient || $appointment->patient_id !== $patient->id) {
            if (!$user->isAdmin()) {
                abort(403, 'You can only view your own appointments.');
            }
        }
        
        return view('user.appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified appointment.
     */
    public function edit(Appointment $appointment)
    {
        $user = Auth::user();
        
        // Ensure user owns this appointment
        $patient = $user->patient;
        if (!$patient || $appointment->patient_id !== $patient->id) {
            if (!$user->isAdmin()) {
                abort(403, 'You can only edit your own appointments.');
            }
        }

        $doctors = Doctor::all();
        
        // Split appointment_date into date and time for editing
        $appointmentDate = $appointment->appointment_date ? \Carbon\Carbon::parse($appointment->appointment_date) : null;
        
        return view('user.appointments.edit', compact('appointment', 'doctors', 'appointmentDate'));
    }

    /**
     * Update the specified appointment.
     */
    public function update(UpdateUserAppointmentRequest $request, Appointment $appointment)
    {
        $user = Auth::user();
        
        // Ensure user owns this appointment
        $patient = $user->patient;
        if (!$patient || $appointment->patient_id !== $patient->id) {
            if (!$user->isAdmin()) {
                abort(403, 'You can only update your own appointments.');
            }
        }

        $validated = $request->validated();
        
        // Combine date and time into appointment_date
        if (isset($validated['appointment_date']) && isset($validated['appointment_time'])) {
            $validated['appointment_date'] = $validated['appointment_date'] . ' ' . $validated['appointment_time'];
            unset($validated['appointment_time']);
        }
        
        $appointment->update($validated);

        return redirect()->route('user.appointments.index')
            ->with('success', __('messages.updated_successfully'));
    }

    /**
     * Remove the specified appointment.
     */
    public function destroy(Appointment $appointment)
    {
        $user = Auth::user();
        
        // Ensure user owns this appointment
        $patient = $user->patient;
        if (!$patient || $appointment->patient_id !== $patient->id) {
            if (!$user->isAdmin()) {
                abort(403, 'You can only delete your own appointments.');
            }
        }

        $appointment->delete();

        return redirect()->route('user.appointments.index')
            ->with('success', __('messages.deleted_successfully'));
    }
}
