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
        $link = "http://mobileairtimeng.com/httpapi/";

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

        try {

            $response = Http::get(self::link('balance.php'))->throw();

        } catch (\Throwable $th) {

            throw new Exception('An Error Occured');
        }

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

        try {

            $response = Http::get(self::link('msharesell', "phone={$phoneNumber}&amt={$amount}&user_ref={$ref}"))->throw();
        } catch (\Throwable $th) {

            throw new Exception('An Error Occured');
        }

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

        try {

            $response = Http::get(self::link(null, "network={$networkCode}&phone={$phoneNumber}&amt={$amount}&user_ref={$ref}")) /* ->throw() */;
        } catch (\Throwable $th) {

            throw new Exception('An Error Occured');
        }

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

        try {

            $response = Http::get(self::link('datatopup.php', "network={$networkCode}&phone={$phoneNumber}&amt={$amount}&user_ref={$ref}")) /* ->throw() */;
        } catch (\Throwable $th) {

            throw new Exception('An Error Occured');
        }

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

        try {

            $response = Http::get(self::link('datashare', "network=1&phone={$phoneNumber}&datasize={$amount}&user_ref={$ref}")) /* ->throw() */;
        } catch (\Throwable $th) {

            throw new Exception('An Error Occured');
        }

        //return $response->json();

        if (isset(self::checkError($response->json())['error'])) {
            return self::checkError($response->json());

        }

        return $response->json();

    }

    public static function fetchDataInfo($info)
    {
        try {
            $response = Http::get(self::link('get-items', "tv={$info}")) /* ->throw() */;
        } catch (\Throwable $th) {

            throw new Exception('An Error Occured');
        }

        return $response->json()['products'] ?? $response->json();

    }
    public static function fetchElectricityInfo()
    {
        try {

            $response = Http::get(self::link('power-lists')) /* ->throw() */;
        } catch (\Throwable $th) {

            throw new Exception('An Error Occured');
        }

        return $response->json()['result'] ?? $response->json();

    }

    public static function cableInfo($bill, $no)
    {
        try {
            $response = Http::get(self::link('customercheck', "bill={$bill}&smartno={$no}")) /* ->throw() */;
        } catch (\Throwable $th) {

            throw new Exception('An Error Occured');
        }

        $result = $response->json();
        unset($result['code']);
        return $result;

    }

    public static function electricityInfo($service, $meterno)
    {
        try {
            $response = Http::get(self::link('power-validate', "service=$service&meterno=$meterno")) /* ->throw() */;
        } catch (\Throwable $th) {

            throw new Exception('An Error Occured');
        }

        $result = $response->json();
        //unset($result['code']);
        return $result;

    }

    public static function electricity($service, $meterno, $type, $amount, $ref)
    {
        if (!env("ENABLE_BILL_PAYMENT")) {
            return errorMessage(env("ERROR_MESSAGE"));
        }

        self::balance();

        try {
            $response = Http::get(self::link('power-pay', "user_ref=$ref&service=$service&meterno=$meterno&mtype=$type&amt=$amount")) /* ->throw() */;
        } catch (\Throwable $th) {
            throw new Exception('An Error Occured');
        }

        if (isset(self::checkError($response->json())['error'])) {
            return self::checkError($response->json());

        }

        return $response->json();

    }

    public static function startimeCable($amount, $smart_no, $number)
    {
        return errorMessage(env("ERROR_MESSAGE"));

        if (!env("ENABLE_BILL_PAYMENT")) {
            return errorMessage(env("ERROR_MESSAGE"));
        }

        self::balance();

        try {

            $response = Http::get(self::link('startimes', "phone={$number}&amt={$amount}&smartno={$smart_no}")) /* ->throw() */;
        } catch (\Throwable $th) {

            throw new Exception('An Error Occured');
        }

        if (isset(self::checkError($response->json())['error'])) {
            return self::checkError($response->json());

        }

        return $response->json();

    }

    public static function cable($type, $amount, $smart_no, $customer_name, $customer_number, $invoice, $number)
    {
        //return errorMessage(env("ERROR_MESSAGE"));

        if (!env("ENABLE_BILL_PAYMENT")) {
            return errorMessage(env("ERROR_MESSAGE"));
        }

        self::balance();

        try {

            $response = Http::get(self::link('multichoice', "phone={$number}&amt={$amount}&smartno={$smart_no}&customer={$customer_name}&invoice={$invoice}&billtype={$type}&customernumber={$customer_number}")) /* ->throw() */;
        } catch (\Throwable $th) {

            throw new Exception('An Error Occured');
        }

        if (isset(self::checkError($response->json())['error'])) {
            return self::checkError($response->json());

        }

        return $response->json();

    }

    public static function sms($message, $numbers, $sender = "MoniWallet", $route = 1, $vtype = 1, $error = true)
    {
        if (!env("ENABLE_BILL_PAYMENT") && $error) {
            return errorMessage(env("ERROR_MESSAGE"));
        }

        $user = env('MANG_USER');
        $pass = env('MANG_PASS');

        $data = [
            'username' => $user,
            'password' => $pass,
            'message' => str_replace('  ', "\n", $message),
            'mobile' => $numbers,
            'sender' => $sender,
            'route' => $route,
            'vtype' => $vtype,
        ];

        self::balance();

        try {
            $response = Http::asForm()->post("http://www.mobileairtimeng.com/smsapi/bulksms.php", $data) /* ->throw() */;

        } catch (\Throwable $th) {

            throw new Exception('An Error Occured');
        }

        return $response;

    }

}