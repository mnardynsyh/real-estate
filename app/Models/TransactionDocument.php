<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionDocument extends Model
{
    protected $table = 'documents';
    protected $guarded = ['id'];

    // Relasi: Dokumen ini milik transaksi mana?
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}