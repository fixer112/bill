<?php

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

function currencyFormat(float $number)
{
    return 'NGN ' . numberFormat($number);
}