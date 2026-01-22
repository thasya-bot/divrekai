<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'password',
        'role_id',
        'unit_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // User milik 1 Unit
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    // User membuat banyak Pendapatan
    public function pendapatan()
    {
        return $this->hasMany(Pendapatan::class, 'created_by');
    }
}