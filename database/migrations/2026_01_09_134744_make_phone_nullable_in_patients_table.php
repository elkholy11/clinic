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
        Schema::table('patients', function (Blueprint $table) {
            // Make phone nullable
            $table->string('phone')->nullable()->change();
            
            // Change gender from enum to nullable string
            $table->string('gender')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            // Revert phone to required
            $table->string('phone')->nullable(false)->change();
            
            // Revert gender to enum
            $table->enum('gender', ['male', 'female'])->nullable(false)->change();
        });
    }
};
