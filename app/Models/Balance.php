<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Balance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_income',
        'total_expense',
        'balance',
        'date'
    ];

    protected $casts = [
        'date' => 'date',
        'total_income' => 'decimal:2',
        'total_expense' => 'decimal:2',
        'balance' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
