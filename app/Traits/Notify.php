<?php
namespace App\Traits;

use App\SmsNotification;
use App\Traits\BillPayment;
use App\Transaction;
use Illuminate\Support\Facades\Http;

trait Notify
{
    use BillPayment;

    public static function chargeSms(Transaction $tran, $message)
    {
        $unit = calSmsUnit($message);
        $amount = env('SMS_CHARGE', 3) * $unit;

        if ($tran->user->balance < $amount) {
            return ['error' => 'Insufficient balance to send sms'];
            //new Exception("Insufficient balance to send sms");
        }

        $sms = self::sms($message, $tran->user->nigeria_number);

        SmsNotification::create([
            'amount' => $amount,
            'user_id' => $tran->user->id,
            'transaction_id' => $tran->id,

        ]);

        $tran->user->update(['balance' => $tran->user->balance - $amount]);
    }

    public static function app(User $user, $body, $title = '')
    {
        //return $user;
        $url = 'https://fcm.googleapis.com/fcm/send';

        //$client = new Client();
        $results = [];
        foreach ($user->app_token as $token) {
            $fields = [
                'to' => $token,
                /*  "notification" => [
                "body" => $body, "title" => $title,
                ], */
                "priority" => "high",
                "data" => [
                    "click_action" => "FLUTTER_NOTIFICATION_CLICK",
                    "body" => $body,
                    "title" => $title,
                ],

            ];
            $result = Http::withHeaders([
                'Authorization' => 'key=' . env('FCM_LEGACY_KEY'),
                'Content-Type' => 'application/json',
            ], )
                ->post($url, $fields);

            /* $client->post($url, [
            'json' =>
            $fields
            ,
            'headers' => [
            'Authorization' => 'key=' . env('FCM_LEGACY_KEY'),
            'Content-Type' => 'application/json',
            ],
            ]); */

            //return
            //echo $token;
            // return
            //array_push($results, json_decode($result->getBody(), true));
            array_push($results, $result, true);

        }
        return $results;
    }
}