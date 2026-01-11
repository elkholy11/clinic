<?php

namespace App\Http\Controllers;

use App\Models\MedicalHistory;
use App\Models\Patient;
use App\Models\Doctor;
use App\Http\Requests\StoreMedicalHistoryRequest;
use App\Http\Requests\UpdateMedicalHistoryRequest;
use App\Http\Controllers\Concerns\HandlesAuthorization;

class MedicalHistoryController extends Controller
{
    use HandlesAuthorization;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', MedicalHistory::class);
        
        $user = $this->currentUser();
        
        $medicalHistories = $this->getFilteredItems(
            MedicalHistory::class,
            'patient_id',
            ['patient', 'doctor'],
            10
        );
        
        return view('medical-history.index', compact('medicalHistories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', MedicalHistory::class);
        
        $user = $this->currentUser();
        
        // Get patients based on user role
        if ($this->isAdmin()) {
            $patients = Patient::all();
            $doctors = Doctor::all();
        } elseif ($this->isDoctor() && $user->doctor) {
            // Doctor can only see their patients
            $patients = $this->getPatientsForUser();
            $doctors = Doctor::where('id', $user->doctor->id)->get();
        } else {
            $patients = collect();
            $doctors = collect();
        }
        
        return view('medical-history.create', compact('patients', 'doctors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMedicalHistoryRequest $request)
    {
        $this->authorize('create', MedicalHistory::class);
        
        $user = $this->currentUser();
        
        // If doctor, set doctor_id to their doctor
        if ($this->isDoctor() && $user->doctor) {
            $request->merge(['doctor_id' => $user->doctor->id]);
        }
        
        MedicalHistory::create($request->validated());
        
        return redirect()->route('admin.medical-history.index')
            ->with('success', __('messages.created_successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(MedicalHistory $medicalHistory)
    {
        $this->authorize('view', $medicalHistory);
        
        $medicalHistory->load(['patient', 'doctor']);
        
        return view('medical-history.show', compact('medicalHistory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MedicalHistory $medicalHistory)
    {
        $this->authorize('update', $medicalHistory);
        
        $user = $this->currentUser();
        
        // Get patients based on user role
        if ($this->isAdmin()) {
            $patients = Patient::all();
            $doctors = Doctor::all();
        } elseif ($this->isDoctor() && $user->doctor) {
            $patients = $this->getPatientsForUser();
            $doctors = Doctor::where('id', $user->doctor->id)->get();
        } else {
            $patients = collect();
            $doctors = collect();
        }
        
        return view('medical-history.edit', compact('medicalHistory', 'patients', 'doctors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMedicalHistoryRequest $request, MedicalHistory $medicalHistory)
    {
        $this->authorize('update', $medicalHistory);
        
        $user = $this->currentUser();
        
        // If doctor, ensure they can't change doctor_id
        if ($this->isDoctor() && $user->doctor) {
            $request->merge(['doctor_id' => $user->doctor->id]);
        }
        
        $medicalHistory->update($request->validated());
        
        return redirect()->route('admin.medical-history.index')
            ->with('success', __('messages.updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MedicalHistory $medicalHistory)
    {
        $this->authorize('delete', $medicalHistory);
        
        $medicalHistory->delete();
        
        return redirect()->route('admin.medical-history.index')
            ->with('success', __('messages.deleted_successfully'));
    }
}
