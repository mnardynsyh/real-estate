<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
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

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // --- RELASI ---

    // 1 User memiliki 1 Profil Customer
    public function customer()
    {
        return $this->hasOne(Customer::class);
    }

    // 1 User bisa memiliki banyak Transaksi (History pembelian)
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
    
    // Helper untuk cek apakah admin
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
