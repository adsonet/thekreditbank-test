<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $credentials = $request->only(['email', 'password']);

        if (! Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid login details'], 401);
        }
    
        /** @var User $user */
        $user = User::where('email', $request['email'])->firstOrFail();
    
        $token = $user->createToken('authToken')->plainTextToken;

        /** if has no account details, create */
        if( ! Account::where('user_id', $user->id)->exists() ) {
            Account::create([ 'user_id' => $user->id ]);
        };
    
        return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
    }

    /**
     * Get the login username using phone or email.
     * only if both are allowed for login
     * @param Request $request
     * @return string
     */
    private function getUsername(Request $request)
    {
        $username = $request->input('username');

        $attribute = filter_var($username, FILTER_VALIDATE_EMAIL) ? 'email' : 'phoneno';

        $request->merge([$attribute => $login]);

        return $attribute;
    }
}
