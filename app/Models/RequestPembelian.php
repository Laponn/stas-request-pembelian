<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestPembelian extends Model
{
    protected $guarded = []; 
    public function user() {
        return $this->belongsTo(User::class);
    }
    public function jenisRiset() {
        return $this->belongsTo(JenisRiset::class);
    }
}
