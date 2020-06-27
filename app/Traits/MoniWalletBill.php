<?php
namespace App\Traits;

use Illuminate\Support\Facades\Http;

trait MoniWalletBill
{
//env("SSS_URL");

    public static function checkUssdStatus($ref)
    {

        $data = [
            'token' => env('USSD_TOKEN'),
            "refid" => $ref,
        ];

        $response = Http::get(env("USSD_URL") . '/status/?' . http_build_query($data))->throw();
        $response = $response->json();
        if (!$response['success']) {
            return errorMessage(errorMessage());
        }

        return $response;
    }

    public static function mtnSNS($number, $amount, $ref)
    {
        if (!env("ENABLE_BILL_PAYMENT") || !env("ENABLE_MTN_SNS", false)) {
            return errorMessage(env("ERROR_MESSAGE"));
        }

        $pin = env("MTN_PIN");

        $data = [
            'ussd' => "*777*$number*$amount*$pin#",
            "servercode" => env("MTN_SIMSERVER_TOKEN"),
            'token' => env('USSD_TOKEN'),
            "refid" => $ref,
        ];

        $response = Http::get(env("USSD_URL") . '/ussd?' . http_build_query($data))->throw();
        $response = $response->json();
        if (!$response['success']) {
            return errorMessage(errorMessage());
        }

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