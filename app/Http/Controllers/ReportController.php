<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Http\Requests\StoreReportRequest;
use App\Http\Requests\UpdateReportRequest;
use App\Models\Patient;
use App\Models\Doctor;
use App\Http\Controllers\Concerns\HandlesAuthorization;


class ReportController extends Controller
{
    use HandlesAuthorization;
    
    public function index()
    {
        $this->authorize('viewAny', Report::class);
        
        $user = $this->currentUser();
        
        // If user is a doctor, show only their reports
        if ($this->isDoctor() && $user->doctor) {
            $reports = Report::with(['patient', 'doctor'])
                ->where('doctor_id', $user->doctor->id)
                ->latest()
                ->paginate(10);
        } elseif ($user->patient) {
            // Regular users (patients) see only their own reports
            $reports = Report::with(['patient', 'doctor'])
                ->where('patient_id', $user->patient->id)
                ->latest()
                ->paginate(10);
        } else {
            // Admin sees all reports
            $reports = Report::with(['patient', 'doctor'])->latest()->paginate(10);
        }
        
        return view('reports.index', compact('reports'));
    }

    public function create()
    {
        $this->authorize('create', Report::class);
        
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
        
        return view('reports.create', compact('patients', 'doctors'));
    }

    public function store(StoreReportRequest $request)
    {
        $this->authorize('create', Report::class);
        
        $user = $this->currentUser();
        $validated = $request->validated();
        
        // If user is a doctor, automatically set doctor_id to their doctor_id
        if ($this->isDoctor() && $user->doctor) {
            $validated['doctor_id'] = $user->doctor->id;
        }
        
        Report::create($validated);
        return redirect()->route('admin.reports.index')
            ->with('success', __('messages.created_successfully'));
    }

    public function show(Report $report)
    {
        $this->authorize('view', $report);
        
        return view('reports.show', compact('report'));
    }

    public function edit(Report $report)
    {
        $this->authorize('update', $report);
        
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
        
        return view('reports.edit', compact('report', 'patients', 'doctors'));
    }

    public function update(UpdateReportRequest $request, Report $report)
    {
        $this->authorize('update', $report);
        
        $report->update($request->validated());
        return redirect()->route('admin.reports.index')
            ->with('success', __('messages.updated_successfully'));
    }

    public function destroy(Report $report)
    {
        $this->authorize('delete', $report);
        
        $report->delete();
        return redirect()->route('admin.reports.index')
            ->with('success', __('messages.deleted_successfully'));
    }
}
