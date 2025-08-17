<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDenomination extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'denomination_id',
        'quantity',
        'amount',
        'operation',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'amount' => 'decimal:2',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function denomination()
    {
        return $this->belongsTo(Denomination::class);
    }
}