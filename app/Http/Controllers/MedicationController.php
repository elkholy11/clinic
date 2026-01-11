<?php

namespace App\Http\Controllers;

use App\Models\Medication;
use App\Http\Requests\StoreMedicationRequest;
use App\Http\Requests\UpdateMedicationRequest;
use App\Http\Controllers\Concerns\HandlesAuthorization;

class MedicationController extends Controller
{
    use HandlesAuthorization;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Medication::class);
        
        $user = $this->currentUser();
        
        // Admin and Doctor can see all medications
        // Regular users can only see active medications
        if ($this->isAdmin() || $this->isDoctor()) {
            $medications = Medication::latest()->paginate(10);
        } else {
            $medications = Medication::where('is_active', true)->latest()->paginate(10);
        }
        
        return view('medications.index', compact('medications'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Medication::class);
        
        return view('medications.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMedicationRequest $request)
    {
        $this->authorize('create', Medication::class);
        
        Medication::create($request->validated());
        
        return redirect()->route('admin.medications.index')
            ->with('success', __('messages.created_successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Medication $medication)
    {
        $this->authorize('view', $medication);
        
        return view('medications.show', compact('medication'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Medication $medication)
    {
        $this->authorize('update', $medication);
        
        return view('medications.edit', compact('medication'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMedicationRequest $request, Medication $medication)
    {
        $this->authorize('update', $medication);
        
        $medication->update($request->validated());
        
        return redirect()->route('admin.medications.index')
            ->with('success', __('messages.updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Medication $medication)
    {
        $this->authorize('delete', $medication);
        
        $medication->delete();
        
        return redirect()->route('admin.medications.index')
            ->with('success', __('messages.deleted_successfully'));
    }
}
