<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Report;
use App\Models\Prescription;
use App\Models\MedicalHistory;
use App\Models\Medication;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    /**
     * Display the user dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Ensure user is not admin
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Admins must use the admin dashboard.');
        }

        // Get user's patient profile
        $patient = $user->patient;
        
        if (!$patient) {
            $totalAppointments = 0;
            $totalReports = 0;
            $totalPrescriptions = 0;
            $totalMedicalHistory = 0;
            $totalInvoices = 0;
            $totalPayments = 0;
            $totalRevenue = 0;
            $pendingInvoices = 0;
            $paidInvoices = 0;
            $totalInvoiceAmount = 0;
            $recentAppointments = collect();
            $recentReports = collect();
            $recentPrescriptions = collect();
            $recentInvoices = collect();
            $recentPayments = collect();
        } else {
            $totalAppointments = Appointment::where('patient_id', $patient->id)->count();
            $totalReports = Report::where('patient_id', $patient->id)->count();
            $totalPrescriptions = Prescription::where('patient_id', $patient->id)->count();
            $totalMedicalHistory = MedicalHistory::where('patient_id', $patient->id)->count();
            $totalInvoices = Invoice::where('patient_id', $patient->id)->count();
            $totalPayments = Payment::where('patient_id', $patient->id)->count();
            
            // Financial statistics
            $totalRevenue = Payment::where('patient_id', $patient->id)->sum('amount');
            $pendingInvoices = Invoice::where('patient_id', $patient->id)->where('status', 'pending')->count();
            $paidInvoices = Invoice::where('patient_id', $patient->id)->where('status', 'paid')->count();
            $totalInvoiceAmount = Invoice::where('patient_id', $patient->id)->sum('final_amount');
            
            $recentAppointments = Appointment::with(['patient', 'doctor'])
                ->where('patient_id', $patient->id)
                ->latest()
                ->take(5)
                ->get();
                
            $recentReports = Report::with(['patient', 'doctor'])
                ->where('patient_id', $patient->id)
                ->latest()
                ->take(5)
                ->get();
                
            $recentPrescriptions = Prescription::with(['patient', 'doctor', 'report'])
                ->where('patient_id', $patient->id)
                ->latest()
                ->take(5)
                ->get();
                
            $recentInvoices = Invoice::with('patient')
                ->where('patient_id', $patient->id)
                ->latest()
                ->take(5)
                ->get();
                
            $recentPayments = Payment::with(['invoice', 'patient'])
                ->where('patient_id', $patient->id)
                ->latest()
                ->take(5)
                ->get();
        }
        
        return view('user.dashboard', compact(
            'totalAppointments',
            'totalReports',
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
            'recentPrescriptions',
            'recentInvoices',
            'recentPayments'
        ));
    }
}
