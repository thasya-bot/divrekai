<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pendapatan extends Model
{
    protected $table = 'pendapatan';
    protected $fillable = [
        'tanggal',
        'jumlah',
        'keterangan',
        'unit_id',
        'created_by'
    ];

    // Pendapatan milik 1 Unit
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    // Pendapatan dibuat oleh 1 User
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}