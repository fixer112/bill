<?php

use App\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */
Route::middleware(['webRouteEnabled'])->group(function () {
    Route::middleware(['referral'])->group(function () {
        Route::get('/', function () {
            //return calPercentage(10000, 5);
            return view('welcome');
        });
        Auth::routes();
    });

    Route::middleware(['auth'])->group(function () {

        #admin
        Route::prefix('admin')->middleware(['admin'])->group(function () {
            Route::get('/', 'AdminController@index');
        });

        Route::get('test/{user}', function (User $user) {
            return calPercentageAmount(100, 200); //calDiscountAmount(10, $user->calDiscount());
        });

        #User
        Route::prefix('user')->group(function () {
            // Route::get('/paystack/validate/{reference}', 'UserController@validatePaystack');

            Route::middleware(['subscribed'])->group(function () {
                Route::get('/{user}', 'UserController@index');
                Route::get('/{user}/edit', 'UserController@getEditUser');
                Route::post('/{user}/edit', 'UserController@editUser');
                Route::get('/{user}/activity', 'UserController@activity');
                Route::get('/wallet/{user}/history', 'UserController@walletHistory');
                Route::get('/wallet/{user}/fund', 'UserController@getFundWallet');
                Route::get('/referral/{user}/history', 'UserController@referralHistory');
                Route::get('/referral/{user}/withdraw', 'UserController@getWithdrawReferral');
                Route::post('/referral/{user}/withdraw', 'UserController@withdrawReferral');

                Route::get('/{user}/airtime', 'UserController@getAirtime');
                Route::post('/{user}/airtime', 'UserController@postAirtime');

                Route::get('/{user}/data', 'UserController@getData');
                Route::post('/{user}/data', 'UserController@postData');

                Route::get('/{user}/subscriptions/', 'UserController@subscriptions');
            });

            Route::middleware(['unsubscribed'])->group(function () {
                Route::get('/{user}/subscribe/', 'UserController@getSubscribe')->name('subscribe');
            });

            Route::get('/{user}/subscription/upgrade/', 'UserController@getUpgrade');
        });

        #Payment Verification
        Route::prefix('verify')->group(function () {
            Route::get('/subscribe/{reference}', 'UserController@subscribe');
            Route::get('/wallet/fund/{reference}', 'UserController@fundWallet');
        });

        Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
    });

    Route::get('/test', 'Controller@test');
    //Route::get('/home', 'HomeController@index')->name('home');
});