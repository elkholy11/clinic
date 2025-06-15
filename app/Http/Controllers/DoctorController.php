<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Http\Requests\DoctorRequest;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = Doctor::latest()->paginate(10);
        return view('doctors.index', compact('doctors'));
    }

    public function create()
    {
        return view('doctors.create');
    }

    public function store(DoctorRequest $request)
    {
        Doctor::create($request->validated());
        return redirect()->route('doctors.index')
            ->with('success', __('messages.created_successfully'));
    }

    public function show(Doctor $doctor)
    {
        return view('doctors.show', compact('doctor'));
    }

    public function edit(Doctor $doctor)
    {
        return view('doctors.edit', compact('doctor'));
    }

    public function update(DoctorRequest $request, Doctor $doctor)
    {
        $doctor->update($request->validated());
        return redirect()->route('doctors.index')
            ->with('success', __('messages.updated_successfully'));
    }

    public function destroy(Doctor $doctor)
    {
        $doctor->delete();
        return redirect()->route('doctors.index')
            ->with('success', __('messages.deleted_successfully'));
    }
} 