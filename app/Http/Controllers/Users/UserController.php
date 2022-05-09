<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::all();

        return response()->json([
            'error' => false,
            'data' => compact('user'),
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($request->id);

        return response()->json([
            'error' => false,
            'data' => compact('user'),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
        ]);

        $user = User::find($validated['id']);
        
        if( ! is_null($user) ) {

            if( $user->update([$validated]) ) {

                return response()->json([
                    'error' => false,
                    'data' => compact('user'),
                ], 200);
            }
        }
            
        return response()->json([
            'error' => true,
            'message' => 'could not update user!',
        ], 200);
    }
}
