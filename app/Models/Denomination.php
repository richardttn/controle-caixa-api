<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Denomination extends Model
{
    use HasFactory;

    protected $fillable = [
        'value',
    ];

    protected $casts = [
        'value' => 'decimal:2',
    ];

    public function transactionDenominations()
    {
        return $this->hasMany(TransactionDenomination::class);
    }
}