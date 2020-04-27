<?php

use App\Traits\BillPayment;
use App\User;
use Illuminate\Support\Facades\Storage;

//if (!function_exists("calPercentage")) {

function successMessage($message = "An error occured, Please try again later")
{
    return ['success' => $message];
}

function errorMessage($message = "An error occured, Please try again later")
{
    return ['error' => $message];
}
function calPercentageAmount(float $amount, float $percentage)
{
    return ($percentage / 100) * $amount;
}
//}
function calDiscountAmount(float $amount, float $percentage)
{
    return $amount - calPercentageAmount($amount, $percentage);
}

function numberFormat(float $number)
{
    return number_format($number, 2);
}

function wholeNumberFormat(float $number)
{
    return number_format($number);
}

function currencySymbol()
{
    return '₦';
}

function currencyFormat(float $number)
{
    return currencySymbol() . ' ' . numberFormat($number);
}

function generateRef(User $user = null)
{
    $id = $user ? $user->id : Str::random(1);

    return Str::random(7) . $id . Str::random(8);
}

function removeCharges($charged, $amount)
{

    return $charged - ($charged - $amount);
}

function calcCharges($amount)
{
    $flatFee = 100;
    $charges = 1.5 / 100;

    $amount = $amount < 2500 ? $amount : $amount + $flatFee;

    $price = $amount / (1 - $charges);

    $price = $price - $amount > 2000 ? $amount + 2000 : $price;
    return ceil($price);

}

function airtimeDiscount(User $user = null)
{

    if (!$user) {

        return [
            'mtn' => 0,
            'airtel' => 0,
            '9mobile' => 0,
            'glo' => 0,
        ];

    }

    return !$user->is_reseller ? config("settings.individual.bills.airtime") : config("settings.subscriptions.{$user->lastSub()->name}.bills.airtime");

}

function dataDiscount(User $user = null)
{

    if (!$user) {

        return [
            'mtn' => 0,
            'airtel' => 0,
            '9mobile' => 0,
            'glo' => 0,
        ];

    }

    return !$user->is_reseller ? config("settings.individual.bills.data") : config("settings.subscriptions.{$user->lastSub()->name}.bills.data");

}

function getLastString($string, $delimiter = '-')
{
    $strings = explode($delimiter, $string);

    return last($strings);
}

function getDataInfo()
{
    //return json_decode(Storage::get('data.json'), true)['data'];
    if (Storage::exists('data.json') && time() - json_decode(Storage::get('data.json'), true)['time'] < (60 * 60 * 24 * 5)) {
        config(["settings.bills.data" => json_decode(Storage::get('data.json'), true)['data']]);

    } else {
        fetchDataInfo();
        config(["settings.bills.data" => json_decode(Storage::get('data.json'), true)['data']]);

    }
}

function fetchDataInfo()
{

    $datas = [];
    $networks = config('settings.mobile_networks');
    unset($networks['mtn']);

    foreach ($networks as $key => $value) {

        $data = config("settings.bills.data.{$key}");

        if ($data == null) {

            $fetchData = BillPayment::fetchDataInfo($key);

            //return $fetchData;

            $fetchData = collect($fetchData)->mapWithKeys(function ($plan, $k) {
                $plan['price'] = ceil($plan['price'] / 5) * 5;
                $plan['topup_amount'] = ceil($plan['price'] / 5) * 5;

                return [$k => $plan];
            });

            //return $fetchData->toArray();

            $datas[$key] = $fetchData;

        }

    }
    //return $datas;
    //$datas['time'] = time();
    $datas['mtn'] = [
        [
            'id' => "Mtn-1GB",
            'topup_currency' => "NGN",
            'topup_amount' => 450,
            'price' => 450,
            'data_amount' => "1000",
            'validity' => "30 days",
        ],
        [
            'id' => "Mtn-2GB",
            'topup_currency' => "NGN",
            'topup_amount' => 900,
            'price' => 900,
            'data_amount' => "2000",
            'validity' => "30 days",
        ],

        [
            'id' => "Mtn-3GB",
            'topup_currency' => "NGN",
            'topup_amount' => 1300,
            'price' => 1300,
            'data_amount' => "3000",
            'validity' => "30 days",
        ],
        [
            'id' => "Mtn-5GB",
            'topup_currency' => "NGN",
            'topup_amount' => 2000,
            'price' => 2000,
            'data_amount' => "5000",
            'validity' => "30 days",
        ],
    ];

    Storage::put('data.json', json_encode(['data' => $datas, 'time' => time()]));
    return $datas;
}