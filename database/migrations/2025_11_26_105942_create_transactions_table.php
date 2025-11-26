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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Invoice: TRX-0001
            
            // Relasi ke User (Akun login) & Unit
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('unit_id')->constrained();
            
            // --- Tahap 1: Booking ---
            $table->decimal('booking_fee', 15, 2);
            $table->string('booking_proof')->nullable(); // Foto Transfer
            $table->timestamp('booking_verified_at')->nullable();
            
            // --- Tahap 2: DP / Pelunasan ---
            $table->decimal('down_payment', 15, 2)->nullable();
            $table->string('dp_proof')->nullable(); // Foto Transfer DP
            $table->timestamp('dp_verified_at')->nullable();
            
            // --- Status Flow ---
            // pending      : Baru checkout, belum bayar
            // process      : Sudah upload bukti, tunggu admin
            // booking_acc  : Admin terima booking, lanjut pemberkasan
            // docs_review  : User sudah upload berkas
            // bank_review  : Berkas valid, proses bank
            // approved     : Bank ACC, lanjut bayar DP
            // sold         : DP Lunas, selesai
            // rejected     : Ditolak (uang kembali/hangus)
            // canceled     : Batal oleh user
            $table->enum('status', [
                'pending', 'process', 'booking_acc', 
                'docs_review', 'bank_review', 
                'approved', 'sold', 'rejected', 'canceled'
            ])->default('pending');
            
            $table->text('admin_note')->nullable(); // Alasan penolakan
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
