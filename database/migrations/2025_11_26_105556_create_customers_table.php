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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            // Relasi One-to-One (Unique) ke Users
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->unique();
            
            $table->string('nik', 16)->nullable(); 
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('job')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
