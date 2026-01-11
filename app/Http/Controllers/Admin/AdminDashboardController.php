<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\Report;
use App\Models\User;
use App\Models\Medication;
use App\Models\Prescription;
use App\Models\MedicalHistory;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        // Ensure user is admin
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Only administrators can access this dashboard.');
        }

        $totalPatients = Patient::count();
        $totalDoctors = Doctor::count();
        $totalAppointments = Appointment::count();
        $totalReports = Report::count();
        $totalUsers = User::count();
        $totalMedications = Medication::where('is_active', true)->count();
        $totalPrescriptions = Prescription::count();
        $totalMedicalHistory = MedicalHistory::count();
        $totalInvoices = Invoice::count();
        $totalPayments = Payment::count();
        
        // Financial statistics
        $totalRevenue = Payment::sum('amount');
        $pendingInvoices = Invoice::where('status', 'pending')->count();
        $paidInvoices = Invoice::where('status', 'paid')->count();
        $totalInvoiceAmount = Invoice::sum('final_amount');
        
        $recentAppointments = Appointment::with(['patient', 'doctor'])->latest()->take(5)->get();
        $recentReports = Report::with(['patient', 'doctor'])->latest()->take(5)->get();
        $recentInvoices = Invoice::with('patient')->latest()->take(5)->get();
        $recentPayments = Payment::with(['invoice', 'patient'])->latest()->take(5)->get();
        
        return view('admin.dashboard', compact(
            'totalPatients',
            'totalDoctors',
            'totalAppointments',
            'totalReports',
            'totalUsers',
            'totalMedications',
            'totalPrescriptions',
            'totalMedicalHistory',
            'totalInvoices',
            'totalPayments',
            'totalRevenue',
            'pendingInvoices',
            'paidInvoices',
            'totalInvoiceAmount',
            'recentAppointments',
            'recentReports',
            'recentInvoices',
            'recentPayments'
        ));
    }
}
