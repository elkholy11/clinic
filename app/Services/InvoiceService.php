<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class InvoiceService
{
    /**
     * Calculate total paid for an invoice.
     * 
     * @param Invoice $invoice
     * @return float
     */
    public function calculateTotalPaid(Invoice $invoice): float
    {
        return $invoice->payments()->sum('amount');
    }

    /**
     * Calculate remaining amount for an invoice.
     * 
     * @param Invoice $invoice
     * @return float
     */
    public function calculateRemainingAmount(Invoice $invoice): float
    {
        $totalPaid = $this->calculateTotalPaid($invoice);
        return max(0, $invoice->final_amount - $totalPaid);
    }

    /**
     * Update invoice status based on payments.
     * 
     * @param Invoice $invoice
     * @return void
     */
    public function updateInvoiceStatus(Invoice $invoice): void
    {
        $totalPaid = $this->calculateTotalPaid($invoice);
        
        if ($totalPaid >= $invoice->final_amount) {
            $invoice->update([
                'status' => 'paid',
                'payment_date' => now(),
            ]);
        } elseif ($totalPaid > 0) {
            $invoice->update(['status' => 'partial']);
        } else {
            $invoice->update(['status' => 'pending']);
        }
    }

    /**
     * Get invoices with remaining balance.
     * 
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getInvoicesWithRemainingBalance(array $filters = [])
    {
        $query = Invoice::with(['patient', 'payments'])
            ->whereRaw('(SELECT COALESCE(SUM(amount), 0) FROM payments WHERE invoice_id = invoices.id) < final_amount');

        if (isset($filters['patient_id'])) {
            if (is_array($filters['patient_id'])) {
                $query->whereIn('patient_id', $filters['patient_id']);
            } else {
                $query->where('patient_id', $filters['patient_id']);
            }
        }

        return $query->get()->map(function ($invoice) {
            $invoice->total_paid = $this->calculateTotalPaid($invoice);
            $invoice->remaining_amount = $this->calculateRemainingAmount($invoice);
            return $invoice;
        });
    }
}

