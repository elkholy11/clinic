<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Medication;
use App\Http\Requests\StorePrescriptionRequest;
use App\Http\Requests\UpdatePrescriptionRequest;
use App\Http\Controllers\Concerns\HandlesAuthorization;
use Illuminate\Support\Facades\DB;

class PrescriptionController extends Controller
{
    use HandlesAuthorization;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Prescription::class);
        
        $user = $this->currentUser();
        
        $user = $this->currentUser();
        
        // Prescriptions have different logic for doctors (they filter by doctor_id, not patient_id)
        if ($this->isAdmin()) {
            $prescriptions = Prescription::with(['patient', 'doctor', 'report'])->latest()->paginate(10);
        } elseif ($this->isDoctor() && $user->doctor) {
            $prescriptions = Prescription::where('doctor_id', $user->doctor->id)
                ->with(['patient', 'doctor', 'report'])
                ->latest()
                ->paginate(10);
        } else {
            // Use the standard filtered items method for regular users
            $prescriptions = $this->getFilteredItems(
                Prescription::class,
                'patient_id',
                ['patient', 'doctor', 'report'],
                10
            );
        }
        
        return view('prescriptions.index', compact('prescriptions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Prescription::class);
        
        $user = $this->currentUser();
        
        // Get patients and doctors based on user role
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
        
        $medications = Medication::where('is_active', true)->get();
        
        return view('prescriptions.create', compact('patients', 'doctors', 'medications'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePrescriptionRequest $request)
    {
        $this->authorize('create', Prescription::class);
        
        $user = $this->currentUser();
        
        // If doctor, set doctor_id to their doctor
        if ($this->isDoctor() && $user->doctor) {
            $request->merge(['doctor_id' => $user->doctor->id]);
        }
        
        DB::transaction(function () use ($request) {
            $prescriptionData = $request->only([
                'report_id',
                'patient_id',
                'doctor_id',
                'prescription_date',
                'notes',
            ]);
            
            // Ensure status has a valid value
            $prescriptionData['status'] = $request->input('status', 'active');
            
            $prescription = Prescription::create($prescriptionData);
            
            // Create prescription items
            if ($request->has('items')) {
                foreach ($request->items as $item) {
                    $prescription->items()->create($item);
                }
            }
        });
        
        return redirect()->route('admin.prescriptions.index')
            ->with('success', __('messages.created_successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Prescription $prescription)
    {
        $this->authorize('view', $prescription);
        
        $prescription->load(['patient', 'doctor', 'report', 'items.medication']);
        
        return view('prescriptions.show', compact('prescription'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Prescription $prescription)
    {
        $this->authorize('update', $prescription);
        
        $user = $this->currentUser();
        
        // Get patients and doctors based on user role
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
        
        $medications = Medication::where('is_active', true)->get();
        $prescription->load('items');
        
        return view('prescriptions.edit', compact('prescription', 'patients', 'doctors', 'medications'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePrescriptionRequest $request, Prescription $prescription)
    {
        $this->authorize('update', $prescription);
        
        $user = $this->currentUser();
        
        // If doctor, ensure they can't change doctor_id
        if ($this->isDoctor() && $user->doctor) {
            $request->merge(['doctor_id' => $user->doctor->id]);
        }
        
        DB::transaction(function () use ($request, $prescription) {
            $prescriptionData = $request->only([
                'report_id',
                'patient_id',
                'doctor_id',
                'prescription_date',
                'notes',
            ]);
            
            // Ensure status has a valid value
            $prescriptionData['status'] = $request->input('status', $prescription->status);
            
            $prescription->update($prescriptionData);
            
            // Update prescription items
            if ($request->has('items')) {
                // Delete existing items
                $prescription->items()->delete();
                
                // Create new items
                foreach ($request->items as $item) {
                    $prescription->items()->create($item);
                }
            }
        });
        
        return redirect()->route('admin.prescriptions.index')
            ->with('success', __('messages.updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Prescription $prescription)
    {
        $this->authorize('delete', $prescription);
        
        $prescription->delete();
        
        return redirect()->route('admin.prescriptions.index')
            ->with('success', __('messages.deleted_successfully'));
    }
}
