<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/** clear cache for testing an updates */
Route::get('clear-cache', function() {
    Artisan::call('optimize:clear');
});

/** Public route */
Route::post('login', [ LoginController::class, 'login' ]);
Route::post('register', [ RegisterController::class, 'register' ]);

/** Protected Route */
Route::middleware('auth:sanctum')->group( function () {

    /** users */
    Route::get('users', [UserController::class, 'index']);
    Route::get('user/{id}', [UserController::class, 'show']);
    Route::put('user/{id}/update', [UserController::class, 'update']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    /** transaction */
    Route::post('fund/send', [TransactionController::class, 'send']);

});

