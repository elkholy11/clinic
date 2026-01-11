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
        Schema::create('prescription_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prescription_id')->constrained()->onDelete('cascade');
            $table->foreignId('medication_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(1); // الكمية
            $table->string('dosage')->nullable(); // الجرعة
            $table->string('frequency')->nullable(); // التكرار: يومياً، مرتين يومياً، etc.
            $table->integer('duration')->nullable(); // المدة بالأيام
            $table->text('instructions')->nullable(); // التعليمات
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescription_items');
    }
};
