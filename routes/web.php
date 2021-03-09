<?php

use Illuminate\Support\Facades\Auth;
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
        Route::get('about', 'HomeController@about');
        Route::get('terms', 'HomeController@terms');
        Route::get('privacy', 'HomeController@privacy');
        Route::get('contact', 'HomeController@contact');

        Auth::routes();
    });

    Route::middleware(['auth', 'checkStatus'])->group(function () {

        #admin
        Route::prefix('admin')->middleware(['admin'])->group(function () {
            Route::get('/', 'AdminController@index');
            Route::get('/wallet/history', 'AdminController@walletHistory');
            Route::get('/wallet/referral', 'AdminController@referralHistory');
            Route::get('/subscriptions', 'AdminController@subscriptionHistory');
            Route::get('/search/users', 'AdminController@searchUsers');
            Route::get('/contact', 'AdminController@getContact');
            Route::post('/sms', 'AdminController@postSms');
            Route::get('/sms', 'AdminController@getSms');
            Route::post('/contact', 'AdminController@contact');

            Route::get('/user/create', 'AdminController@getCreateAdmin');
            Route::post('/user/create', 'AdminController@createAdmin');

            Route::get('/test', 'Controller@test');

            Route::get('test/{user}', 'Controller@testUser');

            Route::get('/data', 'Controller@refreshData');
            Route::get('/electricity', 'Controller@refreshElectricity');

            Route::get('/update_user/{user}', 'Controller@reserveAccount');

            Route::get('/update_users/{id?}', 'Controller@updateUsers');

            Route::get('/assign_role/{user}', 'AdminController@assignRole');
            Route::get('/assign_permission/{user}', 'AdminController@assignPermission');

        });

        #User
        Route::prefix('user')->group(function () {
            // Route::get('/paystack/validate/{reference}', 'UserController@validatePaystack');

            Route::middleware(['subscribed', 'locker'])->group(function () {
                Route::get('/{user}', 'UserController@index');
                Route::get('/{user}/edit', 'UserController@getEditUser');
                Route::post('/{user}/edit', 'UserController@editUser');
                Route::get('/{user}/activity', 'UserController@activity');
                Route::get('/wallet/{user}/history', 'UserController@walletHistory');
                Route::get('/wallet/{user}/fund', 'UserController@getFundWallet');
                Route::post('/wallet/{user}/fund', 'AdminController@fundWallet')->block();
                Route::get('/wallet/{user}/debit', 'UserController@getDebitWallet');
                Route::post('/wallet/{user}/debit', 'UserController@debitWallet');
                Route::get('/wallet/{user}/transfer', 'UserController@getTransfer');
                Route::post('/wallet/{user}/transfer', 'UserController@transfer')->block();
                Route::get('/referral/{user}/history', 'UserController@referralHistory');
                Route::get('/referral/{user}/withdraw', 'UserController@getWithdrawReferral');
                Route::post('/referral/{user}/withdraw', 'UserController@withdrawReferral');
                Route::get('/{user}/status/update', 'UserController@updateStatus');

                Route::get('/{user}/airtime', 'UserController@getAirtime');
                Route::post('/{user}/airtime', 'UserController@postAirtime')->block();

                Route::get('/{user}/data', 'UserController@getData');
                Route::post('/{user}/data', 'UserController@postData')->block();

                Route::get('/{user}/cable', 'UserController@getCable');
                Route::post('/{user}/cable', 'UserController@postCable')->block();

                Route::get('/{user}/electricity', 'UserController@getElectricity');
                Route::post('/{user}/electricity', 'UserController@postElectricity')->block();

                Route::get('/{user}/subscriptions/', 'UserController@subscriptions');

                Route::get('/{user}/api/documentation/', 'UserController@apiDocumentation');
                Route::get('/{user}/api/reset/', 'UserController@apiReset');

                Route::get('/{user}/contact', 'UserController@getContact');
                Route::post('/{user}/contact', 'UserController@contact');

                Route::get('/{user}/sms', 'UserController@getCreateSms');
                Route::post('/{user}/sms', 'UserController@createSms');
                Route::get('/{user}/sms/history', 'UserController@smsHistory');
                Route::get('/{user}/sms/group/create', 'UserController@getCreateSmsGroup');
                Route::post('/{user}/sms/group/create', 'UserController@createSmsGroup');
                Route::get('/{user}/sms/group', 'UserController@smsGroups');
                Route::get('/{user}/sms/group/{group}', 'UserController@getEditSmsGroup');
                Route::post('/{user}/sms/group/{group}', 'UserController@editSmsGroup');
                Route::get('/{user}/sms/group/{group}/delete', 'UserController@deleteSmsGroup');

            });

            Route::middleware(['unsubscribed'])->group(function () {
                Route::get('/{user}/subscribe/', 'UserController@getSubscribe')->name('subscribe');
                Route::get('/{user}/subscription/downgrade/', 'UserController@downgrade');
            });
            Route::get('/{user}/subscription/upgrade/', 'UserController@getUpgrade');

        });

        #Payment Verification

        Route::get('/logout', 'Auth\LoginController@logout');
    });

    Route::post('/pay', 'Controller@initializeRave')->name('pay');
    Route::prefix('verify')->group(function () {
        Route::post('/hook', 'UserController@hook')->name('callback');
        Route::get('/subscribe/{reference}', 'UserController@subscribe');
        Route::get('/wallet/fund/{reference}', 'UserController@fundWallet');
        Route::get('/airtime/{reference}', 'Controller@guestAirtime');
        Route::get('/data/{reference}', 'Controller@guestData');

        Route::get('/smart_no/{type}/{number}', 'Controller@verifySmartCard');
        Route::get('/meter_no/{service}/{number}', 'Controller@verifyMeter');

        Route::post('/ussd', 'Controller@ussdHook');
        Route::get('/ussd', 'Controller@ussdHook');

    });

    Route::get('/home', function () {
        if (auth()->check()) {
            return redirect(auth()->user()->routePath());
        }
        return redirect('/');

    })->name('home');

    Route::post('/hook/transfer', 'Controller@monifyTransfer');
    Route::get('/hook/search', 'Controller@monifySearch');

    Route::get('/test', 'Controller@test');
    Route::get('/info', 'Controller@getInfo');

});