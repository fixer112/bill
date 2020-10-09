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
            return 0;
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

function numberFormat(float $number, $sep = ',')
{
    return number_format($number, 2, '.', $sep);
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

function calcCharges($amount, $charges = 1.5, $flatFee = 0)
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
            'mtn_sns' => 0,
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
            'startimes' => 0,
            'gotv' => 0,
            'dstv' => 0,
        ];

    }

    return !$user->is_reseller ? config("settings.individual.bills.cable") : config("settings.subscriptions.{$user->lastSub()->name}.bills.cable");

}
function electricityDiscount(User $user = null)
{
    if (!$user) {

        return 0;

    }
    return !$user->is_reseller ? config("settings.individual.bills.electricity") : config("settings.subscriptions.{$user->lastSub()->name}.bills.electricity");

}

function smsDiscount(User $user = null)
{
    if (!$user) {

        return 4;

    }

    return !$user->is_reseller ? config("settings.individual.bills.sms") : config("settings.subscriptions.{$user->lastSub()->name}.bills.sms");

}

function getLastString($string, $delimiter = '-')
{
    $strings = explode($delimiter, $string);

    return last($strings);
}

function getElectricityInfo()
{

    if (Storage::exists('electricity.json') && time() - json_decode(Storage::get('electricity.json'), true)['time'] < (60 * 60 * 24)) {
        config(["settings.bills.electricity" => json_decode(Storage::get('electricity.json'), true)['electricity']]);

    } else {
        fetchElectricityInfo();

        config(["settings.bills.electricity" => json_decode(Storage::get('electricity.json'), true)['electricity']]);

    }

}

function fetchElectricityInfo()
{
    Artisan::call('config:clear');

    $electricity = BillPayment::fetchElectricityInfo();

    $data = array_merge(['products' => $electricity], ['charges' => 100]);

    Storage::put('electricity.json', json_encode(['electricity' => $data, 'time' => time()]));

    return $data;
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
    //unset($networks['airtel']);

    //try {
    //code...
    foreach ($networks as $key => $value) {

        $fetchData = BillPayment::fetchDataInfo($key);
        if (!$fetchData) {
            continue;
        }
        $fetchData = collect($fetchData)->mapWithKeys(function ($plan, $k) {
            $plan['id'] = getDataID($plan['data']);
            $plan['topup_amount'] = ceil($plan['amount'] / 5) * 5;
            $plan['topup_currency'] = 'NGN';
            $plan['price'] = $plan['amount'];
            unset($plan['amount']);
            $plan['validity'] = getBetween(substr($plan['data'], strpos($plan['data'], "-") + 1), '(', ')');
            unset($plan['data']);

            //$plan['id'] = convertDataAmount($plan['data_amount']);
            //$build = isset($plan['type']) ? $k.$plan['type'] :  $k;

            $plan['type'] = 'direct';

            return [$k => $plan];

        });

        $fetchData = $fetchData/* ->sortBy('amount')->values()->all() */;
        //return $fetchData->toArray();

        $datas[$key] = $fetchData->filter(function ($plan) {
            return $plan['price'] <= 5000;
        })->sortBy('amount')->values()->all();

        /* if (isset($datas['glo'])) {
    $filters = [[25, 50, 100], ["250", "500", "1000"]];
    $glo = collect($datas['glo'])->filter(function ($plan) use ($filters) {
    //foreach ($filters as $key => $filter) {
    return !in_array($plan['price'], $filters[0]) && !in_array($plan['data_amount'], $filters[1]);
    // }
    });

    $glo = $glo->sortBy('price')->values()->all();

    $datas['glo'] = $glo;
    } */
    }
    /* } catch (\Throwable $th) {
    //throw $th;
    } */

    $defaltConfig = config('settings.bills.data');
    $datas = array_merge($defaltConfig + $datas);
    //$newConfig = [...$defaltConfig, ...$datas];

    Storage::put('data.json', json_encode(['data' => $datas, 'time' => time()]));
    return $datas;
}

function getDataID($content)
{
    return explode('(', explode('-', $content, 2)[0], 2)[0];

}

function getBetween($content, $start, $end)
{
    $r = explode($start, $content);
    if (isset($r[1])) {
        $r = explode($end, $r[1]);
        return $r[0];
    }
    return $content;
}

function formatedNumber($number)
{
    if (strlen($number) < 10) {
        return "";
    }

    return Str::substr($number, -10);
}

function nigeriaNumber($number)
{

    return !formatedNumber($number) ? "" : "0" . formatedNumber($number);
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

function getRaveMetaValue(array $metas, String $name)
{
    foreach ($metas as $meta) {
        if ($meta['metaname'] == $name) {
            return $meta['metavalue'];
        }
    }
    return null;
}

function formatStringsArray($numbers): array
{
    $numbers = explode(',', str_replace(' ', '', $numbers));
    /*  $numbers = array_map(function ($number) {
    return filter_var($number, FILTER_SANITIZE_NUMBER_INT);
    }, $numbers);
     */
    $numbers = collect($numbers)->filter(function ($number) {

        return $number != "";
    });

    return $numbers->toArray();

}

function formatPhoneNumberArray($numbers): array
{
    $numbers = explode(',', str_replace(' ', '', $numbers));

    $numbers = collect($numbers)->map(function ($number) {
        filter_var($number, FILTER_SANITIZE_NUMBER_INT);

        $number = nigeriaNumber($number);

        return filter_var($number, FILTER_SANITIZE_NUMBER_INT);
    });

    $numbers = $numbers->filter(function ($number) {

        return $number != "";
    });

    return $numbers->toArray();

}

function motto()
{
    return "\n...Convinience at it's peak.";
}

function inSuspendID(User $user)
{
    $userIDs = explode(',', env('SUSPEND_USERS'));

    return in_array($user->id, $userIDs);

}