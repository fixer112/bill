<?php
return [
    'referral' => [
        'commision' => [1 => ['bonus' => 5.0, 'refer_bonus' => 50, 'point' => 100], 2 => ['bonus' => 3.0, 'refer_bonus' => 25, 'point' => 50], 3 => ['bonus' => 1.5, 'refer_bonus' => 10, 'point' => 20]],
        'levels' => ['novice' => 0, 'manager' => 10000, 'super manager' => 20000],
    ],

    'subscriptions' => [
        'basic' => ['amount' => 5000, 'discount' => 2, 'bonus' => 10, 'rate_limit' => 100],
        'silver' => ['amount' => 10000, 'discount' => 5, 'bonus' => 12, 'rate_limit' => 200],
        'gold' => ['amount' => 30000, 'discount' => 10, 'bonus' => 13, 'rate_limit' => 500],
        'premium' => ['amount' => 50000, 'discount' => 0.7, 'bonus' => 15, 'rate_limit' => 1000],
    ],
];