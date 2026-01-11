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
        Schema::create('medications', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // اسم الدواء
            $table->string('generic_name')->nullable(); // الاسم العلمي
            $table->string('dosage')->nullable(); // الجرعة
            $table->string('unit')->default('mg'); // الوحدة: mg, ml, etc.
            $table->decimal('price', 10, 2)->default(0); // السعر
            $table->integer('stock_quantity')->default(0); // الكمية المتوفرة
            $table->date('expiry_date')->nullable(); // تاريخ الانتهاء
            $table->string('manufacturer')->nullable(); // الشركة المصنعة
            $table->text('description')->nullable(); // الوصف
            $table->string('category')->nullable(); // الفئة: antibiotic, painkiller, etc.
            $table->boolean('requires_prescription')->default(true); // يحتاج وصفة طبية
            $table->boolean('is_active')->default(true); // نشط
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medications');
    }
};
