<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2); // المبلغ
            $table->enum('payment_method', ['cash', 'card', 'bank_transfer', 'cheque', 'other']); // طريقة الدفع
            $table->date('payment_date'); // تاريخ الدفع
            $table->string('transaction_id')->nullable(); // رقم المعاملة
            $table->string('receipt_number')->nullable(); // رقم الإيصال
            $table->text('notes')->nullable(); // ملاحظات
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
