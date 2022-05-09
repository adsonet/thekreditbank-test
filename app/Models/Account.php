<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected static function booted()
    {
        parent::boot();

        # assign generated account number during create method
        static::creating(function($account) {
            $account->account_number = self::generateAccountNumber();
        })
    }

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'account_number',
        'book_balance'
    ];

    protected static function generateNumber(): int
    {
        do {

            mt_srand(time()); 
            $number = mt_rand(1000000000, 9999999999);

        } while ( $this->numberExists($number) );
  
        return $number;
    }

    private function numberExists($number) 
    {
        return self::where('account_number', $number)->exists();
    }
}
