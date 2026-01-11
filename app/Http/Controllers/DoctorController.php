<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\User;
use App\Http\Requests\DoctorRequest;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Concerns\HandlesAuthorization;

class DoctorController extends Controller
{
    use HandlesAuthorization;
    public function index()
    {
        $this->authorize('viewAny', Doctor::class);
        
        $user = $this->currentUser();
        
        // If user is a doctor, show only themselves
        if ($this->isDoctor() && $user->doctor) {
            $doctors = Doctor::where('id', $user->doctor->id)->paginate(10);
        } else {
            // Admin or non-doctor users see all doctors
            $doctors = Doctor::latest()->paginate(10);
        }
        
        return view('doctors.index', compact('doctors'));
    }

    public function create()
    {
        $this->authorize('create', Doctor::class);
        
        return view('doctors.create');
    }

    public function store(DoctorRequest $request)
    {
        $this->authorize('create', Doctor::class);
        
        $validated = $request->validated();
        $doctor = Doctor::create($validated);
        
        // Create user account if requested
        if ($request->has('create_user_account') && $request->create_user_account) {
            // Generate a random password
            $password = \Str::random(12);
            
            $newUser = User::create([
                'name' => $doctor->name,
                'email' => $doctor->email ?? $doctor->name . '@clinic.com',
                'password' => Hash::make($password),
                'doctor_id' => $doctor->id,
                'is_admin' => false, // Doctor account
            ]);
            
            return redirect()->route('admin.doctors.index')
                ->with('success', __('messages.doctor_and_user_created'))
                ->with('user_password', $password); // Show password to admin
        }
        
        return redirect()->route('admin.doctors.index')
            ->with('success', __('messages.created_successfully'));
    }

    public function show(Doctor $doctor)
    {
        $this->authorize('view', $doctor);
        
        return view('doctors.show', compact('doctor'));
    }

    public function edit(Doctor $doctor)
    {
        $this->authorize('update', $doctor);
        
        return view('doctors.edit', compact('doctor'));
    }

    public function update(DoctorRequest $request, Doctor $doctor)
    {
        $this->authorize('update', $doctor);
        
        $doctor->update($request->validated());
        return redirect()->route('admin.doctors.index')
            ->with('success', __('messages.updated_successfully'));
    }

    public function destroy(Doctor $doctor)
    {
        $this->authorize('delete', $doctor);
        
        $doctor->delete();
        return redirect()->route('admin.doctors.index')
            ->with('success', __('messages.deleted_successfully'));
    }
} 