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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique(); // رقم الفاتورة
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('appointment_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('report_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('total_amount', 10, 2)->default(0); // المبلغ الإجمالي
            $table->decimal('discount', 10, 2)->default(0); // الخصم
            $table->decimal('tax', 10, 2)->default(0); // الضريبة
            $table->decimal('final_amount', 10, 2)->default(0); // المبلغ النهائي
            $table->enum('status', ['pending', 'paid', 'cancelled'])->default('pending');
            $table->date('issue_date'); // تاريخ الإصدار
            $table->date('due_date')->nullable(); // تاريخ الاستحقاق
            $table->date('payment_date')->nullable(); // تاريخ الدفع
            $table->text('notes')->nullable(); // ملاحظات
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
