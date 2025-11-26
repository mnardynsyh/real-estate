<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $guarded = ['id'];

    // Relasi: Dokumen ini milik transaksi mana?
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}