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
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('housing_location_id')->constrained()->onDelete('cascade');
            
            $table->string('block_number'); // Contoh: "A-01"
            $table->string('type');         // Contoh: "36/72"
            $table->decimal('price', 15, 2);
            $table->integer('land_area');   // Luas Tanah
            $table->integer('building_area'); // Luas Bangunan
            
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            
            // Status Publik (Apa yang dilihat user)
            $table->enum('status', ['available', 'booked', 'sold'])->default('available');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
