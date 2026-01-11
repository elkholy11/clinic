<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Invoice;
use App\Services\InvoiceService;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    protected $invoiceService;

    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    /**
     * Process payment and update invoice status.
     * 
     * @param array $paymentData
     * @return Payment
     */
    public function processPayment(array $paymentData): Payment
    {
        return DB::transaction(function () use ($paymentData) {
            $payment = Payment::create($paymentData);
            
            $invoice = $payment->invoice;
            $this->invoiceService->updateInvoiceStatus($invoice);
            
            return $payment;
        });
    }

    /**
     * Update payment and recalculate invoice status.
     * 
     * @param Payment $payment
     * @param array $paymentData
     * @return Payment
     */
    public function updatePayment(Payment $payment, array $paymentData): Payment
    {
        return DB::transaction(function () use ($payment, $paymentData) {
            $payment->update($paymentData);
            
            $invoice = $payment->invoice;
            $this->invoiceService->updateInvoiceStatus($invoice);
            
            return $payment;
        });
    }

    /**
     * Delete payment and recalculate invoice status.
     * 
     * @param Payment $payment
     * @return bool
     */
    public function deletePayment(Payment $payment): bool
    {
        return DB::transaction(function () use ($payment) {
            $invoice = $payment->invoice;
            $deleted = $payment->delete();
            
            if ($deleted) {
                $this->invoiceService->updateInvoiceStatus($invoice);
            }
            
            return $deleted;
        });
    }
}

