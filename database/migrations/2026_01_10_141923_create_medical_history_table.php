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
        Schema::create('medical_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->enum('record_type', ['allergy', 'surgery', 'chronic_disease', 'medication', 'vaccination', 'other'])->default('other'); // نوع السجل
            $table->string('title'); // العنوان
            $table->text('description')->nullable(); // الوصف
            $table->date('date')->nullable(); // التاريخ
            $table->foreignId('doctor_id')->nullable()->constrained()->onDelete('set null'); // الطبيب المسؤول
            $table->string('attachments')->nullable(); // مرفقات: صور، ملفات
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_history');
    }
};
