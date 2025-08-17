<?php

// User Model (app/Models/User.php)
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'email',
        'password',
        'fullname',
        'role',
        'avatar',
        'email_visibility',
        'verified',
        'teller_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'token_key',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'email_visibility' => 'boolean',
        'verified' => 'boolean',
    ];

    public function teller()
    {
        return $this->belongsTo(Teller::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
