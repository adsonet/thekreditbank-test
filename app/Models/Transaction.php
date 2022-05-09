<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Transaction extends Model
{
    use HasFactory;

    public const MONEY_DECIMAL = 3;

    public function user(): belongsTo
    {
        return $this->belongsTo(User::class);
    }
}
