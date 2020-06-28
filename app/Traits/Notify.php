<?php
namespace App\Traits;

use App\SmsNotification;
use App\Traits\BillPayment;
use App\Traits\MoniWalletBill;
use App\Transaction;
use App\User;
use Illuminate\Support\Facades\Http;

trait Notify
{
    use BillPayment;

    public static function chargeSms(Transaction $tran, $message)
    {
        /* $unit = calSmsUnit($message);
        $amount = env('SMS_CHARGE', 3) * $unit; */

        if ($tran->user->balance < $amount) {
            return ['error' => 'Insufficient balance to send sms'];
            //new Exception("Insufficient balance to send sms");
        }

        $ref = generateRef($tran->user);

        $sms = MoniWalletBill::sms($tran->user->nigeria_number, $message, $ref);

        if (is_array($result) && isset($result['error'])) {
            return;
        }

        $amount = $result['sms_pages'] * $result['units_used'] * env('SMS_CHARGE', 3);

        SmsNotification::create([
            'amount' => $amount,
            'user_id' => $tran->user->id,
            'transaction_id' => $tran->id,
            //'ref' => $ref,

        ]);

        Transaction::create([
            'amount' => $amount,
            'balance' => $tran->user->balance - $amount,
            'type' => 'debit',
            'desc' => "Sms alert for $tran->ref",
            'ref' => $ref,
            'user_id' => $tran->user->id,
            'reason' => 'sms',
            'plathform' => getPlathform(),
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
            ])->post($url, $fields);

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
    public static function appTopic($topic, $body, $title = '')
    {
        //return $user;
        $url = 'https://fcm.googleapis.com/fcm/send';

        $fields = [
            'to' => "/topics/$topic",

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
        ])->post($url, $fields);

        return $result;
    }
}