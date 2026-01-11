<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Http\Controllers\Concerns\HandlesAuthorization;

class AppointmentController extends Controller
{
    use HandlesAuthorization;
    
    public function index()
    {
        $this->authorize('viewAny', Appointment::class);
        
        $user = $this->currentUser();
        
        // If user is a doctor, show only their appointments
        if ($this->isDoctor() && $user->doctor) {
            $appointments = Appointment::with(['patient', 'doctor'])
                ->where('doctor_id', $user->doctor->id)
                ->latest()
                ->paginate(10);
        } else {
            // Admin or non-doctor users see all appointments
            $appointments = Appointment::with(['patient', 'doctor'])->latest()->paginate(10);
        }
        
        return view('appointments.index', compact('appointments'));
    }

    public function create()
    {
        $this->authorize('create', Appointment::class);
        
        $user = $this->currentUser();
        
        // If user is a doctor, show only their patients
        if ($this->isDoctor() && $user->doctor) {
            $patients = $user->doctor->patients()->get();
            $doctors = collect([$user->doctor]); // Only themselves
        } else {
            // Admin or non-doctor users see all
            $patients = Patient::all();
            $doctors = Doctor::all();
        }
        
        return view('appointments.create', compact('patients', 'doctors'));
    }

    public function store(StoreAppointmentRequest $request)
    {
        $this->authorize('create', Appointment::class);
        
        $user = $this->currentUser();
        $validated = $request->validated();
        
        // If user is a doctor, automatically set doctor_id to their doctor_id
        if ($this->isDoctor() && $user->doctor) {
            $validated['doctor_id'] = $user->doctor->id;
        }
        
        Appointment::create($validated);

        return redirect()->route('admin.appointments.index')
            ->with('success', __('messages.created_successfully'));
    }

    public function show(Appointment $appointment)
    {
        $this->authorize('view', $appointment);
        
        return view('appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        $this->authorize('update', $appointment);
        
        $user = $this->currentUser();
        
        // If user is a doctor, show only their patients
        if ($this->isDoctor() && $user->doctor) {
            $patients = $user->doctor->patients()->get();
            $doctors = collect([$user->doctor]); // Only themselves
        } else {
            // Admin or non-doctor users see all
            $patients = Patient::all();
            $doctors = Doctor::all();
        }
        
        return view('appointments.edit', compact('appointment', 'patients', 'doctors'));
    }

    public function update(UpdateAppointmentRequest $request, Appointment $appointment)
    {
        $this->authorize('update', $appointment);
        
        $validated = $request->validated();
        $appointment->update($validated);

        return redirect()->route('admin.appointments.index')
            ->with('success', __('messages.updated_successfully'));
    }

    public function destroy(Appointment $appointment)
    {
        $this->authorize('delete', $appointment);
        
        $appointment->delete();

        return redirect()->route('admin.appointments.index')
            ->with('success', __('messages.deleted_successfully'));
    }
}
