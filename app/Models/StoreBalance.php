<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreBalance extends Model
{
    protected $fillable = [
        'current_balance',
    ];

    protected function casts(): array
    {
        return [
            'current_balance' => 'integer',
        ];
    }
}
