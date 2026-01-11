<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\Auth\AdminLoginController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\User\UserAppointmentController;
use App\Http\Controllers\MedicationController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\MedicalHistoryController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Helpers\UserHelper;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Language Switcher
Route::get('language/{locale}', [LanguageController::class, 'switchLang'])->name('language.switch');

// ============================================
// ADMIN ROUTES (Admin Panel)
// ============================================

// Admin Authentication Routes
Route::middleware('guest')->prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminLoginController::class, 'login']);
});

// Admin Protected Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::post('logout', [AdminLoginController::class, 'logout'])->name('logout');
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Admin Resources
    Route::resource('doctors', DoctorController::class);
    Route::resource('patients', PatientController::class);
    Route::resource('appointments', AppointmentController::class);
    Route::resource('reports', ReportController::class);
    Route::resource('users', UserController::class);
    Route::resource('medications', MedicationController::class);
    Route::resource('prescriptions', PrescriptionController::class);
    Route::resource('medical-history', MedicalHistoryController::class);
    Route::resource('invoices', InvoiceController::class);
    Route::resource('payments', PaymentController::class);
});

// ============================================
// USER ROUTES (User Panel)
// ============================================

// User Authentication Routes
Route::middleware('guest')->group(function () {
    // User Login
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    
    // User Registration
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);
    
    // Password Reset Routes
    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// User Protected Routes
Route::middleware('auth')->group(function () {
    // Redirect root based on user role
    Route::get('/', function () {
        return redirect()->route(UserHelper::getDashboardRoute(Auth::user()));
    })->name('home');
    
    // Dashboard route - redirects based on user role
    Route::get('/dashboard', function () {
        return redirect()->route(UserHelper::getDashboardRoute(Auth::user()));
    })->name('dashboard');
    
    // User Logout
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    
    // User Dashboard
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
        Route::resource('appointments', UserAppointmentController::class);
        
        // User can only view their own reports (read-only)
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/{report}', [ReportController::class, 'show'])->name('reports.show');
        
        // User can view their own prescriptions (read-only)
        Route::get('prescriptions', [PrescriptionController::class, 'index'])->name('prescriptions.index');
        Route::get('prescriptions/{prescription}', [PrescriptionController::class, 'show'])->name('prescriptions.show');
        
        // User can view their own medical history (read-only)
        Route::get('medical-history', [MedicalHistoryController::class, 'index'])->name('medical-history.index');
        Route::get('medical-history/{medicalHistory}', [MedicalHistoryController::class, 'show'])->name('medical-history.show');
        
        // User can view active medications (read-only)
        Route::get('medications', [MedicationController::class, 'index'])->name('medications.index');
        Route::get('medications/{medication}', [MedicationController::class, 'show'])->name('medications.show');
        
        // User can view their own invoices (read-only)
        Route::get('invoices', [InvoiceController::class, 'index'])->name('invoices.index');
        Route::get('invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
        
        // User can view their own payments (read-only)
        Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');
        Route::get('payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
    });
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//require __DIR__.'/auth.php';

