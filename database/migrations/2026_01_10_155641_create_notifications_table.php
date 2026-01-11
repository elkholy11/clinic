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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['appointment', 'report', 'payment', 'prescription', 'reminder', 'other']); // نوع الإشعار
            $table->string('title'); // العنوان
            $table->text('message'); // الرسالة
            $table->unsignedBigInteger('related_id')->nullable(); // ID للعنصر المرتبط
            $table->string('related_type')->nullable(); // نوع العنصر المرتبط
            $table->boolean('is_read')->default(false); // تم القراءة
            $table->timestamp('read_at')->nullable(); // تاريخ القراءة
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
