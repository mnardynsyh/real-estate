<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HousingLocation extends Model
{
    protected $guarded = ['id'];

    // 1 Lokasi punya banyak Unit rumah
    public function units()
    {
        return $this->hasMany(Unit::class);
    }
}