<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\Report;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPatients = Patient::count();
        $totalDoctors = Doctor::count();
        $totalAppointments = Appointment::count();
        $totalReports = Report::count();
        $recentAppointments = Appointment::with(['patient', 'doctor'])->latest()->take(5)->get();
        $recentReports = Report::with(['patient', 'doctor'])->latest()->take(5)->get();
        return view('dashboard', compact(
            'totalPatients',
            'totalDoctors',
            'totalAppointments',
            'totalReports',
            'recentAppointments',
            'recentReports'
        ));
    }
} 