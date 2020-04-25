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
        /*  Route::get('/', function () {
        //return calPercentage(10000, 5);
        return view('welcome');
        }); */
        Route::get('/', 'HomeController@index');
        Route::get('pricing', 'HomeController@pricing');

        Auth::routes();
    });

    Route::middleware(['auth', 'checkStatus'])->group(function () {

        #admin
        Route::prefix('admin')->middleware(['admin'])->group(function () {
            Route::get('/', 'AdminController@index');
            Route::get('/wallet/history', 'AdminController@walletHistory');
            Route::get('/wallet/referral', 'AdminController@referralHistory');
            Route::get('/search/users', 'AdminController@searchUsers');

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
                Route::get('/{user}/status/update', 'UserController@updateStatus');

                Route::get('/{user}/airtime', 'UserController@getAirtime');
                Route::post('/{user}/airtime', 'UserController@postAirtime');

                Route::get('/{user}/data', 'UserController@getData');
                Route::post('/{user}/data', 'UserController@postData');

                Route::get('/{user}/subscriptions/', 'UserController@subscriptions');

                Route::get('/{user}/api/documentation/', 'UserController@apiDocumentation');
                Route::get('/{user}/api/reset/', 'UserController@apiReset');

            });

            Route::middleware(['unsubscribed'])->group(function () {
                Route::get('/{user}/subscribe/', 'UserController@getSubscribe')->name('subscribe');
            });

            Route::get('/{user}/subscription/upgrade/', 'UserController@getUpgrade');
        });

        #Payment Verification

        Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
    });

    Route::prefix('verify')->group(function () {
        Route::get('/subscribe/{reference}', 'UserController@subscribe');
        Route::get('/wallet/fund/{reference}', 'UserController@fundWallet');
        Route::get('/airtime/{reference}', 'Controller@guestAirtime');
        Route::get('/data/{reference}', 'Controller@guestData');

    });

    Route::get('/test', 'Controller@test');
    //Route::get('/home', 'HomeController@index')->name('home');
});