<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Report;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Http\Controllers\Concerns\HandlesAuthorization;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    use HandlesAuthorization;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Invoice::class);
        
        $user = $this->currentUser();
        
        $invoices = $this->getFilteredItems(
            Invoice::class,
            'patient_id',
            ['patient', 'appointment', 'report'],
            10
        );
        
        return view('invoices.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Invoice::class);
        
        $patients = $this->getPatientsForUser();
        $appointments = $this->getAppointmentsForUser();
        $reports = $this->getReportsForUser();
        
        return view('invoices.create', compact('patients', 'appointments', 'reports'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInvoiceRequest $request)
    {
        $this->authorize('create', Invoice::class);
        
        try {
            DB::transaction(function () use ($request) {
            $invoiceData = $request->only([
                'patient_id',
                'appointment_id',
                'report_id',
                'total_amount',
                'discount',
                'tax',
                'final_amount',
                'issue_date',
                'due_date',
                'payment_date',
                'notes',
            ]);
            
            // Set status - default to 'pending' if not provided or invalid
            $status = $request->input('status', 'pending');
            $allowedStatuses = ['pending', 'paid', 'cancelled', 'partial'];
            $invoiceData['status'] = in_array($status, $allowedStatuses) ? $status : 'pending';
            
            // Generate unique invoice number
            $invoiceData['invoice_number'] = Invoice::generateInvoiceNumber();
                
                $invoice = Invoice::create($invoiceData);
                
                // Create invoice items
                if ($request->has('items') && is_array($request->items)) {
                    foreach ($request->items as $item) {
                        // Calculate total_price if not provided
                        if (!isset($item['total_price']) || empty($item['total_price'])) {
                            $quantity = isset($item['quantity']) ? (float)$item['quantity'] : 0;
                            $unitPrice = isset($item['unit_price']) ? (float)$item['unit_price'] : 0;
                            $item['total_price'] = $quantity * $unitPrice;
                        }
                        $invoice->items()->create($item);
                    }
                }
            });
            
            return redirect()->route('admin.invoices.index')
                ->with('success', __('messages.created_successfully'));
        } catch (\Exception $e) {
            return $this->handleException($e, route('admin.invoices.index'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        $this->authorize('view', $invoice);
        $invoice->load(['patient', 'appointment', 'report', 'items', 'payments']);
        return view('invoices.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        $this->authorize('update', $invoice);
        
        $patients = $this->getPatientsForUser();
        $appointments = $this->getAppointmentsForUser();
        $reports = $this->getReportsForUser();
        
        $invoice->load('items');
        
        return view('invoices.edit', compact('invoice', 'patients', 'appointments', 'reports'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {
        $this->authorize('update', $invoice);
        
        try {
            DB::transaction(function () use ($request, $invoice) {
                $invoice->update($request->only([
                    'patient_id',
                    'appointment_id',
                    'report_id',
                    'total_amount',
                    'discount',
                    'tax',
                    'final_amount',
                    'status',
                    'issue_date',
                    'due_date',
                    'payment_date',
                    'notes',
                ]));
                
                // Update invoice items
                if ($request->has('items')) {
                    $invoice->items()->delete();
                    foreach ($request->items as $item) {
                        // Ensure total_price is calculated if not provided
                        if (!isset($item['total_price'])) {
                            $item['total_price'] = ($item['quantity'] ?? 0) * ($item['unit_price'] ?? 0);
                        }
                        $invoice->items()->create($item);
                    }
                }
            });
            
            return redirect()->route('admin.invoices.index')
                ->with('success', __('messages.updated_successfully'));
        } catch (\Exception $e) {
            return $this->handleException($e, route('admin.invoices.index'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        $this->authorize('delete', $invoice);
        $invoice->delete();
        return redirect()->route('admin.invoices.index')
            ->with('success', __('messages.deleted_successfully'));
    }
}
