<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisRiset extends Model
{
    protected $guarded = [];
    public function requestPembelians() {
        return $this->hasMany(RequestPembelian::class);
    }
}
