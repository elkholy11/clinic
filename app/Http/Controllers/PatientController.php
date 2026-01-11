<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Http\Requests\PatientRequest;
use App\Http\Controllers\Concerns\HandlesAuthorization;

class PatientController extends Controller
{
    use HandlesAuthorization;
    
    public function index()
    {
        $this->authorize('viewAny', Patient::class);
        
        $user = $this->currentUser();
        
        // If user is a doctor, show only their patients
        if ($this->isDoctor() && $user->doctor) {
            $patients = $user->doctor->patients()->latest()->paginate(10);
        } else {
            // Admin or non-doctor users see all patients
            $patients = Patient::latest()->paginate(10);
        }
        
        return view('patients.index', compact('patients'));
    }

    public function create()
    {
        $this->authorize('create', Patient::class);
        
        return view('patients.create');
    }

    public function store(PatientRequest $request)
    {
        $this->authorize('create', Patient::class);
        
        Patient::create($request->validated());
        return redirect()->route('admin.patients.index')
            ->with('success', __('messages.created_successfully'));
    }

    public function show(Patient $patient)
    {
        $this->authorize('view', $patient);
        
        return view('patients.show', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        $this->authorize('update', $patient);
        
        return view('patients.edit', compact('patient'));
    }

    public function update(PatientRequest $request, Patient $patient)
    {
        $this->authorize('update', $patient);
        
        $patient->update($request->validated());
        return redirect()->route('admin.patients.index')
            ->with('success', __('messages.updated_successfully'));
    }

    public function destroy(Patient $patient)
    {
        $this->authorize('delete', $patient);
        
        $patient->delete();
        return redirect()->route('admin.patients.index')
            ->with('success', __('messages.deleted_successfully'));
    }
} 