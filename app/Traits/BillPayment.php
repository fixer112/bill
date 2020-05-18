<?php
namespace App\Traits;

use App\Mail\lowBalance;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

trait BillPayment
{

    protected static function link($subLink = null, $query = null)
    {
        $user = env('MANG_USER');
        $pass = env('MANG_PASS');
        $credentials = "?userid={$user}&pass={$pass}&jsn=json";
        $link = "https://mobileairtimeng.com/httpapi/";

        $link = $subLink ? "{$link}{$subLink}{$credentials}" : "{$link}{$credentials}";

        return $query ? "{$link}&{$query}" : $link;
    }

    public static function balance()
    {
        //Session::put('balance', [0, time()]);

        if (Session::has('balance') && time() - Session::get('balance')[1] < 300) {
            if (Session::get('balance')[0] <= env('ALERT_MIN_BALANCE', 0)) {

                if (!Session::has('mailSent')) {
                    try {
                        Mail::to('support@moniwallet.com')->send(new lowBalance(session('balance')[0]));
                        Mail::to('moniwalletng@gmail.com')->send(new lowBalance(session('balance')[0]));

                    } catch (Exception $e) {
                    }
                    Session::put('mailSent', time());

                }

                if (Session::has('mailSent') && time() - Session::get('mailSent') > env('BALANCE_ALERT_INTERVAL', 3600)) {
                    try {
                        Mail::to('support@moniwallet.com')->send(new lowBalance(session('balance')[0]));
                        Mail::to('moniwalletng@gmail.com')->send(new lowBalance(session('balance')[0]));

                    } catch (Exception $e) {
                    }
                    Session::put('mailSent', time());

                }
            } else {
                Session::forget('mailSent');
            }

            return session('balance')[0];
        }

        $response = Http::get(self::link('balance.php'))->throw();

        /* if ($response->clientError() || $response->serverError()) {
        $response->body();
        } */

        Session::put('balance', [$response->body(), time()]);

        return (double) $response->body();

    }

    public static function testbill()
    {
        $result = [
            'code' => '100',
            'message' => "Recharge successful!",
            'exchangeReference' => "api_5eb1a8d78ad93",
            'user_ref' => "Olo5eb1a8d68e39a",
        ];
        //return $result;

        return self::checkError($result);

    }

    public static function checkError($response)
    {

        if ($response['code'] == '107') {
            return errorMessage('Invalid Phone Number');
        }

        if ($response['code'] == '108') {
            return errorMessage($response['message']);
        }

        if ($response['code'] == '102') {
            return errorMessage("Invalid amount");
        }

        if ($response['code'] == '103') {
            return errorMessage('Temporary network issue,Please try again later');
        }

        if ($response['code'] != '100') {
            return errorMessage();
        }

        return [];

    }

    public static function mtnAirtime($amount, $phoneNumber, $ref)
    {
        if (!env("ENABLE_BILL_PAYMENT")) {
            return errorMessage(env("ERROR_MESSAGE"));
        }

        self::balance();

        $response = Http::get(self::link('msharesell', "phone={$phoneNumber}&amt={$amount}&user_ref={$ref}"))->throw();

        if (isset(self::checkError($response->json())['error'])) {
            return self::checkError($response->json());

        }

        return $response->json();
    }

    public static function airtime($amount, $phoneNumber, $networkCode, $ref)
    {
        // return self::link(null, "network=15&phone=xxxxx&amt=500&user_ref=xxx");
        if (!env("ENABLE_BILL_PAYMENT")) {
            return errorMessage(env("ERROR_MESSAGE"));
        }

        self::balance();

        //$ref = generateRef();

        $response = Http::get(self::link(null, "network={$networkCode}&phone={$phoneNumber}&amt={$amount}&user_ref={$ref}"))->throw();

        //return $response->json();

        if (isset(self::checkError($response->json())['error'])) {
            return self::checkError($response->json());

        }

        return $response->json();
    }

    /* public static function data2()
    {

    $response = Http::post('https://www.speedydata.com.ng/api/data-order/create', [
    "public_key" => 'pub_VbAb5lqnivVGQOkNjzir',
    "private_key" => 'pri_6OATyKWh0nBdL3OjWdBkT2F13uOIIU',
    "phone" => '08106813749',
    "data_id" => 1,
    ])->throw();

    return $response->json();

    } */

    public static function data($amount, $phoneNumber, $networkCode, $ref)
    {
        if (!env("ENABLE_BILL_PAYMENT")) {
            return errorMessage(env("ERROR_MESSAGE"));
        }

        self::balance();

        $response = Http::get(self::link('datatopup.php', "network={$networkCode}&phone={$phoneNumber}&amt={$amount}&user_ref={$ref}"))->throw();

        // return $response->json();

        if (isset(self::checkError($response->json())['error'])) {
            return self::checkError($response->json());

        }

        return $response->json();

    }

    public static function dataMtn($amount, $phoneNumber, $networkCode, $ref)
    {

        if (!env("ENABLE_BILL_PAYMENT")) {
            return errorMessage(env("ERROR_MESSAGE"));
        }

        self::balance();

        $response = Http::get(self::link('datashare', "network=1&phone={$phoneNumber}&datasize={$amount}&user_ref={$ref}"))->throw();

        //return $response->json();

        if (isset(self::checkError($response->json())['error'])) {
            return self::checkError($response->json());

        }

        return $response->json();

    }

    public static function fetchDataInfo($info)
    {
        $response = Http::get(self::link('get-items', "service={$info}"))->throw();
        return $response->json()['products'];

    }

    public static function cableInfo($bill, $no)
    {
        $response = Http::get(self::link('customercheck', "bill={$bill}&smartno={$no}"))->throw();

        $result = $response->json();
        unset($result['code']);
        return $result;

    }

    public static function startimeCable($amount, $smart_no, $number)
    {
        if (!env("ENABLE_BILL_PAYMENT")) {
            return errorMessage(env("ERROR_MESSAGE"));
        }

        self::balance();

        $response = Http::get(self::link('startimes', "phone={$number}&amt={$amount}&smartno={$smart_no}"))->throw();

        if (isset(self::checkError($response->json())['error'])) {
            return self::checkError($response->json());

        }

        return $response->json();

    }

    public static function cable($type, $amount, $smart_no, $customer_name, $customer_number, $invoice, $number)
    {
        if (!env("ENABLE_BILL_PAYMENT")) {
            return errorMessage(env("ERROR_MESSAGE"));
        }

        self::balance();

        $response = Http::get(self::link('multichoice', "phone={$number}&amt={$amount}&smartno={$smart_no}&customer={$customer_name}&invoice={$smart_no}&billtype={$type}&customernumber={$customer_number}"))->throw();

        if (isset(self::checkError($response->json())['error'])) {
            return self::checkError($response->json());

        }

        return $response->json();

    }

    public static function sms($message, $numbers, $sender, $route = 1, $vtype = 1)
    {
        /* if (!env("ENABLE_BILL_PAYMENT")) {
        return errorMessage(env("ERROR_MESSAGE"));
        } */

        $user = env('MANG_USER');
        $pass = env('MANG_PASS');

        $data = [
            'username' => $user,
            'password' => $pass,
            'message' => $message,
            'mobile' => $numbers,
            'sender' => $sender,
            'route' => $route,
            'vtype' => $vtype,
        ];

        self::balance();

        $response = Http::asForm()->post("https://www.mobileairtimeng.com/smsapi/bulksms.php", $data)->throw();

        return $response;

    }

}