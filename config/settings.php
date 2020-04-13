<?php
return [
    'referral' => [
        'commision' => [1 => ['bonus' => 5.0, 'point' => 100], 2 => ['bonus' => 3.0, 'point' => 50], 3 => ['bonus' => 1.5, 'point' => 20]],
        'levels' => ['novice' => 0, 'manager' => 10000, 'super manager' => 20000],
    ],

    'subscriptions' => [
        'basic' => ['amount' => 5000, 'discount' => 2, 'bonus' => 10],
        'silver' => ['amount' => 10000, 'discount' => 5, 'bonus' => 12],
        'gold' => ['amount' => 30000, 'discount' => 10, 'bonus' => 13],
        'premium' => ['amount' => 50000, 'discount' => 0.7, 'bonus' => 15],
    ],
];