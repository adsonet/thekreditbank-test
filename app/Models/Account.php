<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected static function booted()
    {
        parent::booted();

        # assign generated account number during create method
        static::creating(function($account) {
            $account->account_number = self::generateAccountNumber();
        });
    }

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'account_number',
        'book_balance'
    ];

    protected static function generateAccountNumber(): int
    {
        do {

            mt_srand(time()); 
            $number = mt_rand(1000000000, 9999999999);

        } while ( self::numberExists($number) );
  
        return $number;
    }

    private static function numberExists($number) 
    {
        return self::where('account_number', $number)->exists();
    }
}
