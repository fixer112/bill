<?php

use Illuminate\Support\Facades\Route;

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

/* Route::middleware('auth:api')->get('/user', function (Request $request) {
return $request->user();
}); */
Route::post('/login', 'Auth\LoginController@loginApi');

Route::middleware(['auth:api', 'checkStatus', 'throttle:rate_limit,1'])->group(function () {
    Route::prefix('user')->group(function () {
        Route::post('/{user}/airtime', 'UserController@postAirtime');
        Route::post('/{user}/data', 'UserController@postData');
        Route::post('/{user}/cable', 'UserController@postCable');
        Route::get('/{user}/balance', 'UserController@getBalance');
        Route::get('/{user}/history/{ref}', 'UserController@history');

        Route::get('/{user}/history', 'UserController@walletHistory');
        Route::get('/{user}/referral', 'UserController@referralHistory');

        Route::post('/{user}/update_token', 'UserController@updateToken');
        Route::post('/{user}/remove_token', 'UserController@removeToken');

    });

});

Route::get('/fetch_data', 'UserController@fetchData');