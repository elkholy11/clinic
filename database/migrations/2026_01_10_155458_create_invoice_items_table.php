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
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
            $table->enum('item_type', ['consultation', 'medication', 'test', 'procedure', 'other']); // نوع العنصر
            $table->string('item_name'); // اسم العنصر
            $table->integer('quantity')->default(1); // الكمية
            $table->decimal('unit_price', 10, 2); // سعر الوحدة
            $table->decimal('total_price', 10, 2); // السعر الإجمالي
            $table->text('description')->nullable(); // الوصف
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
