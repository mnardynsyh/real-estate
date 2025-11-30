<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $guarded = ['id'];

    // Ubah format tanggal otomatis jadi Carbon Object agar mudah diolah
    protected $casts = [
        'booking_verified_at' => 'datetime',
        'dp_verified_at' => 'datetime',
    ];

    // Relasi: Transaksi milik User siapa?
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Transaksi beli Unit yang mana?
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    // Relasi: Transaksi punya banyak Dokumen
    public function documents()
    {
        return $this->hasMany(TransactionDocument::class);
    }
}