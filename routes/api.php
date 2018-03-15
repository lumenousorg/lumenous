<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return response()->json(['user' => $request->user()]);
});

Route::namespace('API\Auth')->group(function () {
    Route::post('/login', 'LoginController@login');
    Route::post('/register', 'RegisterController@register');
});

Route::namespace('API')->middleware('auth:api')->group(function () {
    Route::get('/dashboard/pool/votes/percentage', 'DashboardController@getPoolVotePercentage');
    Route::get('dashboard/users/{user}/stats', 'DashboardController@getStats');
    Route::get('payouts/users/{user}', 'PayoutsController@getUserPayouts');
});


