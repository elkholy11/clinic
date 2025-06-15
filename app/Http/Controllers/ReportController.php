<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\Appointment;
use App\Http\Requests\StoreReportRequest;
use App\Http\Requests\UpdateReportRequest;
use App\Models\Patient;
use App\Models\Doctor;


class ReportController extends Controller
{
    public function index()
    {
        $reports = Report::with(['patient', 'doctor'])->latest()->paginate(10);
        return view('reports.index', compact('reports'));
    }

    public function create()
    {
        $patients = Patient::all();
        $doctors = Doctor::all();
        return view('reports.create', compact('patients', 'doctors'));
    }

    public function store(StoreReportRequest $request)
    {
        Report::create($request->validated());
        return redirect()->route('reports.index')
            ->with('success', __('messages.created_successfully'));
    }

    public function show(Report $report)
    {
        return view('reports.show', compact('report'));
    }

    public function edit(Report $report)
    {
        $patients = Patient::all();
        $doctors = Doctor::all();
        return view('reports.edit', compact('report', 'patients', 'doctors'));
    }

    public function update(UpdateReportRequest $request, Report $report)
    {
        $report->update($request->validated());
        return redirect()->route('reports.index')
            ->with('success', __('messages.updated_successfully'));
    }

    public function destroy(Report $report)
    {
        $report->delete();
        return redirect()->route('reports.index')
            ->with('success', __('messages.deleted_successfully'));
    }
}
