<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Account;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        $credentials = $request->only(['email', 'password']);

        if ( ! Auth::attempt($credentials) ) {
            return response()->json(['message' => 'Invalid login details'], 401);
        }
    
        /** @var User $user */
        $user = User::where('email', $request['email'])->firstOrFail();
    
        $token = $user->createToken('authToken')->plainTextToken;

        # if has no account details, create it
        # see the booted method of Account model for account_number generation
        if( ! Account::where('user_id', $user->id)->exists() ) {
            $account = Account::create([ 'user_id' => $user->id ]);
            $account = !is_null($account) ? $account->account_number : null;
        };
    
        return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'message' => 'Login was successful!',
                'data' => [ 'account_number' => $account ?? '' ]
            ], 200);
    }

    /**
     * Only if both phone and email are allowed for login
     * Get the username as either phone or email.
     * 
     * @param Request $request
     * @return string
     */
    private function getUsername(Request $request)
    {
        $username = $request->input('username');

        $attribute = filter_var($username, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $request->merge([$attribute => $username]);

        return $attribute;
    }
}
