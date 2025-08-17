<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teller extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'total_amount',
        'today_transaction_nr',
        'last_reset_at',
    ];

    protected $casts = [
        'number' => 'integer',
        'total_amount' => 'decimal:2',
        'today_transaction_nr' => 'integer',
        'last_reset_at' => 'date',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}