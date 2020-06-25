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

    public static function sms($numbers, $message, $ref, $route = 2, $senderid = "MoniWallet")
    {
        if (!env("ENABLE_BILL_PAYMENT")) {
            return errorMessage(env("ERROR_MESSAGE"));
        }

        $data = [
            'sender' => $senderid ?? "MoniWallet",
            'to' => $numbers,
            'message' => str_replace('  ', "\n", $message),
            'type' => '0',
            'routing' => $route,
            'token' => env('SSS_TOKEN'),
            'ref_id' => $ref,
        ];
        $response = Http::asForm()->post(env("SSS_URL") . "/json.php", $data)->throw();
        $response = $response->json();
        if (in_array($response['code'], ['1002', '1004'])) {
            return errorMessage($response['comment']);
        }

        if ($response['code'] != '1000') {
            //return $response;
            return errorMessage(errorMessage());
        }

        return $response;

    }
}