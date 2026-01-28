<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Pendapatan;
use App\Models\TargetPendapatan;

class Unit extends Model
{
    protected $fillable = [
        'nama_unit',
        'kode_unit'
        ];

    // 1 Unit punya banyak User
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // 1 Unit punya banyak Pendapatan
    public function pendapatan()
    {
        return $this->hasMany(Pendapatan::class);
    }

    // 1 Unit punya banyak TargetPendapatan
    public function targets()
    {
        return $this->hasMany(TargetPendapatan::class);
    }
}