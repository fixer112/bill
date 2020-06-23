<?php
namespace App\Traits;

use Illuminate\Support\Facades\Http;

trait MoniWalletBill
{
//env("SSS_URL");

    public static function mtnSNS($number, $amount)
    {
        $pin = env("MTN_PIN");

        $data = [
            'ussd' => "*777*$number*$amount*$pin",
            'simserver_token' => "hfjhfdjhfjdhf", //env('MTN_SIMSERVER_TOKEN'),
            'token' => env('SSS_TOKEN'),
        ];

        $response = Http::asForm()->post(env("SSS_URL") . "/ussd.php", $data)->throw();
        return $response;
    }

    public static function sms($numbers, $message, $senderid = "MoniWallet", $route = 2)
    {
        $data = [
            'sender' => $senderid,
            'to' => $numbers,
            'message' => $message,
            'type' => '0',
            'routing' => 3,
            'token' => env('SSS_TOKEN'),
        ];
        $response = Http::asForm()->post(env("SSS_URL") . "/json.php", $data)->throw();
        return $response;

    }
}