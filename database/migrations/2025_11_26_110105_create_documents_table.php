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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained()->onDelete('cascade');
            
            // Jenis Dokumen
            $table->enum('type', ['ktp', 'kk', 'npwp', 'slip_gaji', 'rekening_koran', 'lainnya']);
            
            $table->string('file_path');
            
            // Status per File
            $table->enum('status', ['pending', 'valid', 'invalid'])->default('pending');
            $table->string('note')->nullable(); // Catatan revisi per file
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
