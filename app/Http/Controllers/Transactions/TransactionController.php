<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\User;
use App\Traits\Numbers;

class TransactionController extends Controller
{
    use Numbers;

    public function send(Request $request)
    {
        $validated = $request->validate([
            'to_user_id' => 'required',
            'amount' => 'required'
        ]);

        /** 
         * make Model instance as methods cannot be called on auth
         * @var User $user 
         */
        $user = User::find(auth()->user()->id);

        $transaction = Transaction::create([
            'from_user_id' => $user->id,
            'to_user_id' => $validated['to_user_id'],
            'amount' => $this->floatValue( $validated['amount'] ),
            'details' => 'references that user A transfer to User B'
        ]);

        if( ! $transaction ) {
            response()->json([
                'error' => true,
                'message' => 'could not complete transaction!'
            ]);
        }

        
        $balance = $user->balance();
        
        response()->json([
            'error' => false,
            'message' => 'successful!',
            'data' => compact('balance')
        ]);

    }
}
