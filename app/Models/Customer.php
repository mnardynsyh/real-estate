<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    // Izinkan semua kolom diisi kecuali ID
    protected $guarded = ['id'];

    // Relasi ke User (Milik User siapa?)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}