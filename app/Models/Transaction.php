<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_name',
        'client_code',
        'amount',
        'type',
        'user_id',
        'teller_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function teller()
    {
        return $this->belongsTo(Teller::class);
    }

    public function transactionDenominations()
    {
        return $this->hasMany(TransactionDenomination::class);
    }

    public function transactionType()
    {
        return $this->belongsTo(TransactionType::class, 'type', 'name');
    }
}