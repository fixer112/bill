<?php

use App\User;

//if (!function_exists("calPercentage")) {

function calPercentageAmount(float $amount, float $percentage)
{
    return ($percentage / 100 * $amount);
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
    return 'NGN';
}

function currencyFormat(float $number)
{
    return currencySymbol() . ' ' . numberFormat($number);
}

function generateRef(User $user)
{
    return Str::random(7) . $user->id . Str::random(8);
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