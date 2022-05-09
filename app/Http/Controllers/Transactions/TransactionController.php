<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
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

        $user = auth()->user();

        $transaction = Transaction::create([
            'from_user_id' => $user->id;
            'to_user_id' => $validated['to_user_id'];
            'amount' => $this->floatValue( $validated['amount'] );
            'details' => 'references that user A transfer to User B';
        ]);

        if( ! $transaction ) {
            response()->json([
                'error' => true,
                'message' => 'could not complete trasaction!'
            ]);
        }

        $balance = $user->balance();
        
        response()->json([
            'error' => true,
            'message' => 'successful!',
            'balance' => $balance
        ]);

    }
}
