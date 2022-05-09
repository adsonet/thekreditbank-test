<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Transaction;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'email',
        'password',
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

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get all credit transactions using to_user_id field
     */
    public function creditTransactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'to_user_id');
    }

    /**
     * Get all debit transactions using user_id
     */
    public function debitTransactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function balance()
    {
        $credit = $this->creditTransactions->sum('amount');
        $debit = $this->debitTransactions->sum('amount');
        $balance = $credit - $debit;

        return round($balance, Transaction::MONEY_DECIMAL, PHP_ROUND_HALF_UP);
    }
}
