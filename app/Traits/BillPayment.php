<?php
namespace App\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

trait BillPayment
{

    protected static function link($subLink = null, $query = null)
    {
        $user = env('MANG_USER');
        $pass = env('MANG_PASS');
        $credentials = "?userid={$user}&pass={$pass}";
        $link = "https://mobileairtimeng.com/httpapi/";

        $link = $subLink ? "{$link}{$subLink}{$credentials}" : "{$link}{$credentials}";

        return $query ? "{$link}&{$query}" : $link;
    }
    public static function balance()
    {

        if (Session::has('balance') && time() - Session::get('balance')[1] < 300) {

            //return time() - Session::get('balance')[1];
            return session('balance')[0];
        }

        $response = Http::get(self::link('balance.php'))->throw();

        /* if ($response->clientError() || $response->serverError()) {
        $response->body();
        } */

        Session::put('balance', [$response->body(), time()]);

        return (double) $response->body();

    }

    public static function airtime($amount, $phoneNumber, $networkCode)
    {
        // return self::link(null, "network=15&phone=xxxxx&amt=500&user_ref=xxx");
        if (self::balance() < $amount) {
            return errorMessage();
        }

        $ref = generateRef();

        $response = Http::get(self::link(null, "network={$networkCode}&phone={$phoneNumber}&amt={$amount}&user_ref={$ref}"))->throw();

        //return $response->status();
        if (str_contains($response->body(), '107')) {
            return errorMessage('Invalid Phone Number');
        }

        if (str_contains($response->body(), '102')) {
            return errorMessage("Invalid amount");
        }

        if (!str_contains($response->body(), '100')) {
            return errorMessage();
        }

        return $response->body();
    }

    public static function data($amount)
    {
        if (self::balance() < $amount) {
            return self::errorMessage();
        }

    }

}