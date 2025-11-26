<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $guarded = ['id'];

    // Relasi: Unit ini ada di lokasi mana?
    public function location()
    {
        return $this->belongsTo(HousingLocation::class, 'housing_location_id');
    }

    // Relasi: Unit ini punya banyak riwayat transaksi
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
    
    // Helper: Ambil transaksi yang sedang AKTIF (Booked/Process)
    // Berguna untuk melihat siapa yang sedang membooking unit ini
    public function activeTransaction()
    {
        return $this->hasOne(Transaction::class)->latestOfMany();
    }
}