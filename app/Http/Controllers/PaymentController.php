<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Patient;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Controllers\Concerns\HandlesAuthorization;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    use HandlesAuthorization;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Payment::class);
        
        $payments = $this->getFilteredItems(
            Payment::class,
            'patient_id',
            ['invoice', 'patient'],
            10
        );
        
        return view('payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Payment::class);
        
        $user = $this->currentUser();
        $invoiceId = request()->get('invoice_id');
        
        $invoiceService = app(\App\Services\InvoiceService::class);
        
        if ($this->isAdmin()) {
            $invoices = $invoiceService->getInvoicesWithRemainingBalance();
            $patients = Patient::all();
        } elseif ($this->isDoctor() && $user->doctor) {
            $patientIds = $this->getDoctorPatientIds();
            $invoices = $invoiceService->getInvoicesWithRemainingBalance(['patient_id' => $patientIds->toArray()]);
            $patients = Patient::whereIn('id', $patientIds)->get();
        } else {
            $invoices = collect();
            $patients = collect();
        }
        
        $selectedInvoice = $invoiceId ? Invoice::with(['patient', 'payments'])->find($invoiceId) : null;
        if ($selectedInvoice) {
            $selectedInvoice->total_paid = $selectedInvoice->payments->sum('amount');
            $selectedInvoice->remaining_amount = $selectedInvoice->final_amount - $selectedInvoice->total_paid;
        }
        
        return view('payments.create', compact('invoices', 'patients', 'selectedInvoice'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePaymentRequest $request)
    {
        $this->authorize('create', Payment::class);
        
        try {
            $paymentService = app(\App\Services\PaymentService::class);
            $payment = $paymentService->processPayment($request->validated());
            
            return redirect()->route('admin.payments.index')
                ->with('success', __('messages.created_successfully'));
        } catch (\Exception $e) {
            return $this->handleException($e, route('admin.payments.index'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        $this->authorize('view', $payment);
        $payment->load(['invoice', 'patient']);
        return view('payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        $this->authorize('update', $payment);
        
        $user = $this->currentUser();
        
        $patientIds = $this->isDoctor() && $user->doctor ? $this->getDoctorPatientIds() : collect();
        
        if ($this->isAdmin()) {
            $invoices = Invoice::with('patient')->get();
            $patients = Patient::all();
        } elseif ($this->isDoctor() && $user->doctor && $patientIds->isNotEmpty()) {
            $invoices = Invoice::whereIn('patient_id', $patientIds)->with('patient')->get();
            $patients = Patient::whereIn('id', $patientIds)->get();
        } else {
            $invoices = collect();
            $patients = collect();
        }
        
        return view('payments.edit', compact('payment', 'invoices', 'patients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StorePaymentRequest $request, Payment $payment)
    {
        $this->authorize('update', $payment);
        
        try {
            $paymentService = app(\App\Services\PaymentService::class);
            $paymentService->updatePayment($payment, $request->validated());
            
            return redirect()->route('admin.payments.index')
                ->with('success', __('messages.updated_successfully'));
        } catch (\Exception $e) {
            return $this->handleException($e, route('admin.payments.index'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        $this->authorize('delete', $payment);
        
        DB::transaction(function () use ($payment) {
            $invoice = $payment->invoice;
            $payment->delete();
            
            // Update invoice status after payment deletion
            $totalPaid = $invoice->getTotalPaid();
            
            if ($totalPaid >= $invoice->final_amount) {
                $invoice->update(['status' => 'paid']);
            } elseif ($totalPaid > 0) {
                $invoice->update(['status' => 'partial']);
            } else {
                $invoice->update(['status' => 'pending', 'payment_date' => null]);
            }
        });
        
        return redirect()->route('admin.payments.index')
            ->with('success', __('messages.deleted_successfully'));
    }
}
