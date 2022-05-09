<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $validatedData = $this->validated($request);      
        
        $validatedData['password'] = app('hash')->make($validatedData['password']);

        $user = User::create($validatedData);
        
        if( ! $user ) {
            return response()->json([
                'error' => true,
                'message' => 'User could not be created'
            ]);
        }

        # Account Number is generated
        # see booted method of the Account model.
        # success is confirmed and retried during login, see LoginController
        $account = Account::create([ 'user_id' => $user->id ]);
        $account = !is_null($account) ? $account->account_number : null;
    
        return response()->json([
            'error' => false,
            'message' => 'User successfully created!',
            'data' => [ 'account_number' => $account ]
        ], 200);
    }

    protected function validated(Request $request)
    {
        return $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone' => 'required|string|min:11|max:14',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|confirmed|min:4'
        ]);
    }
}
