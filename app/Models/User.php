<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected  $table = 'users';

    protected $fillable = [
        'nama',
        'username',
        'password',
        'role',
    ];

    public function pembelian()
    {
        return $this->hasMany(pembelian::class);
    }
    public function penjualan()
    {
        return $this->hasMany(penjualan::class);
    }
}
