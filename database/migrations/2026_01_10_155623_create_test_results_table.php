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
        Schema::create('test_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained()->onDelete('cascade');
            $table->foreignId('report_id')->nullable()->constrained()->onDelete('set null');
            $table->string('test_name'); // اسم الفحص
            $table->enum('test_type', ['blood', 'urine', 'xray', 'ultrasound', 'ct_scan', 'mri', 'other']); // نوع الفحص
            $table->date('test_date'); // تاريخ الفحص
            $table->json('results')->nullable(); // النتائج - JSON
            $table->string('normal_range')->nullable(); // المدى الطبيعي
            $table->enum('status', ['normal', 'abnormal', 'critical'])->default('normal');
            $table->text('notes')->nullable(); // ملاحظات
            $table->string('attachments')->nullable(); // مرفقات
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_results');
    }
};
