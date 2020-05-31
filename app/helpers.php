<?php

use App\Traits\BillPayment;
use App\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

//if (!function_exists("calPercentage")) {
function dublicateMessage($message = null)
{
    $min = dublicateTime() / 60;
    $message = $message == null ? "Dublicate Transaction, please try again in {$min} Minutes" : $message;
    return $message;
}

function dublicateTime()
{
    if (request()->wantsJson()) {

        if (request()->plathform == 'app') {
            return env("DUBLICATE_LIMIT", 180);
        } else {
            return 60;
        }

    }
    return env("DUBLICATE_LIMIT", 180);

    //return request()->wantsJson() ? 60 : env("DUBLICATE_LIMIT", 180);
    //return !request()->wantsJson() ? env("DUBLICATE_LIMIT", 180) : request()->type == 'app' ? env("DUBLICATE_LIMIT", 180) : 60;
}

function getPlathform()
{
    if (request()->wantsJson()) {

        if (request()->plathform == 'app') {
            return 'app';
        } else {
            return 'api';
        }

    }
    return 'web';

}

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
    return currencySymbol() . '' . numberFormat($number);
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

function calcCharges($amount, $charges = 1.5, $flatFee = 50)
{

    $charges = $charges / 100;

    $amount = $amount < 2500 ? $amount : $amount + $flatFee;

    $price = $amount / (1 - $charges);

    $price = $price - $amount > 2000 ? $amount + 2000 : $price;
    return ceil($price);

}

function getCable()
{
    $bills = $bills = config("settings.bills.cable");

    foreach ($bills as $k => $cable) {
        foreach ($cable as $key => $plan) {
            if ($plan['price'] < 5000) {
                $charges = 100;
            } elseif ($plan['price'] >= 5000 && $plan['price'] < 10000) {
                $charges = 150;
            } else {
                $charges = 200;

            }

            $bills[$k][$key]['charges'] = $charges;

        }
    }

    return collect($bills)->toArray();

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
            'mtn_sme' => 0,
            //'mtn_direct' => 0,
            'airtel' => 0,
            '9mobile' => 0,
            'glo' => 0,
        ];

    }

    $datas = !$user->is_reseller ? config("settings.individual.bills.data") : config("settings.subscriptions.{$user->lastSub()->name}.bills.data");
    unset($datas['mtn_direct']);

    return $datas;

}

function cableDiscount(User $user = null)
{
    if (!$user) {

        return [
            'startime' => 0,
            'gotv' => 0,
            'dstv' => 0,
        ];

    }

    return !$user->is_reseller ? config("settings.individual.bills.cable") : config("settings.subscriptions.{$user->lastSub()->name}.bills.cable");

}
function getLastString($string, $delimiter = '-')
{
    $strings = explode($delimiter, $string);

    return last($strings);
}

function getDataInfo()
{
    //return json_decode(Storage::get('data.json'), true)['data'];
    if (Storage::exists('data.json') && time() - json_decode(Storage::get('data.json'), true)['time'] < (60 * 60 * 24)) {
        config(["settings.bills.data" => json_decode(Storage::get('data.json'), true)['data']]);

    } else {
        fetchDataInfo();
        config(["settings.bills.data" => json_decode(Storage::get('data.json'), true)['data']]);

    }
}

function fetchDataInfo()
{
    Artisan::call('config:clear');

    $datas = [];
    $networks = config('settings.mobile_networks');
    unset($networks['mtn_sme']);
    unset($networks['mtn_sns']);

    foreach ($networks as $key => $value) {

        $fetchData = BillPayment::fetchDataInfo($key);
        $fetchData = collect($fetchData)->mapWithKeys(function ($plan, $k) {
            //$plan['price'] = ceil($plan['price'] / 5) * 5;
            $plan['topup_amount'] = ceil($plan['price'] / 5) * 5;
            $plan['id'] = convertDataAmount($plan['data_amount']);
            //$build = isset($plan['type']) ? $k.$plan['type'] :  $k;
            $plan['type'] = 'direct';

            return [$k => $plan];
        });

        $fetchData = $fetchData/* ->unique('price') */->sortBy('price')->values()->all();
        //return $fetchData->toArray();

        $datas[$key] = $fetchData;

        if (isset($datas['glo'])) {
            $filters = [[25, 50, 100], ["250", "500", "1000"]];
            $glo = collect($datas['glo'])->filter(function ($plan) use ($filters) {
                //foreach ($filters as $key => $filter) {
                return !in_array($plan['price'], $filters[0]) && !in_array($plan['data_amount'], $filters[1]);
                // }
            });

            $glo = $glo->sortBy('price')->values()->all();

            $datas['glo'] = $glo;
        }
    }
    $sme = [
        [
            'id' => "1GB",
            'topup_currency' => "NGN",
            'topup_amount' => 400,
            'price' => 1000,
            'data_amount' => "1000",
            'validity' => "30 days",
            'type' => 'sme',
        ],
        [
            'id' => "2GB",
            'topup_currency' => "NGN",
            'topup_amount' => 800,
            'price' => 2000,
            'data_amount' => "2000",
            'validity' => "30 days",
            'type' => 'sme',
        ],

        [
            'id' => "3GB",
            'topup_currency' => "NGN",
            'topup_amount' => 1200,
            'price' => 3000,
            'data_amount' => "3000",
            'validity' => "30 days",
            'type' => 'sme',
        ],
        [
            'id' => "5GB",
            'topup_currency' => "NGN",
            'topup_amount' => 2000,
            'price' => 5000,
            'data_amount' => "5000",
            'validity' => "30 days",
            'type' => 'sme',
        ],
    ];

    $datas = array_merge(array('mtn_sme' => $sme) + $datas);

    Storage::put('data.json', json_encode(['data' => $datas, 'time' => time()]));
    return $datas;
}

function formatedNumber($number)
{

    return Str::substr($number, -10);
}

function nigeriaNumber($number)
{
    return "0" . formatedNumber($number);
}

function convertDataAmount($number)
{
    $gb = $number / 1000;
    return $number < 1000 ? "{$number}MB" : "{$gb}GB";
}
function calSmsUnit($message)
{
    $length = strlen($message);
    return $length <= 160 ? 1 : ceil($length / 160);
}