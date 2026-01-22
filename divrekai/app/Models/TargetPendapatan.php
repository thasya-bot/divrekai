<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TargetPendapatan extends Model
{
    protected $fillable = [
        'unit_id',
        'periode',
        'tanggal',
        'bulan',
        'tahun',
        'target_jumlah'
    ];

    // Target milik 1 Unit
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}