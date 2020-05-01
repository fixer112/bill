<?php
return [
    'mobile_networks' => ['mtn' => '15', 'airtel' => '1', '9mobile' => '2', 'glo' => '6'],
    'bills' => [

        'airtime' => [
            'mtn' => ['min' => 50, 'max' => 50000],
            'airtel' => ['min' => 50, 'max' => 50000],
            '9mobile' => ['min' => 50, 'max' => 50000],
            'glo' => ['min' => 50, 'max' => 50000]],

    ],
    'referral' => [
        'commision' => [1 => ['bonus' => 5.0, 'refer_bonus' => 50, 'point' => 100], 2 => ['bonus' => 3.0, 'refer_bonus' => 25, 'point' => 50], 3 => ['bonus' => 1.5, 'refer_bonus' => 10, 'point' => 20]],
        'levels' => ['novice' => 0, 'manager' => 10000, 'super manager' => 20000],
    ],

    'individual' => [
        'bills' => [
            'airtime' => [
                'mtn' => 0.7,
                'airtel' => 0,
                '9mobile' => 0,
                'glo' => 1,
            ],
            'data' => [
                'mtn' => 4.7,
                'airtel' => 0,
                '9mobile' => 0,
                'glo' => 1,
            ],
        ],
    ],

    'subscriptions' => [
        'basic' => ['amount' => 5000, 'discount' => 2, 'bonus' => 20, 'rate_limit' => 100, 'bills' => [
            'airtime' => [
                'mtn' => 1,
                'airtel' => 1,
                '9mobile' => 1,
                'glo' => 1.2,
            ],
            'data' => [
                'mtn' => 7.1,
                'airtel' => 1,
                '9mobile' => 1,
                'glo' => 2,
            ],
        ],
        ],
        'silver' => ['amount' => 10000, 'discount' => 5, 'bonus' => 20, 'rate_limit' => 200, 'bills' => [
            'airtime' => [
                'mtn' => 1.5,
                'airtel' => 1,
                '9mobile' => 1,
                'glo' => 1.7,
            ],
            'data' => [
                'mtn' => 9.5,
                'airtel' => 1,
                '9mobile' => 1,
                'glo' => 3,
            ],
        ],
        ],
        'gold' => ['amount' => 30000, 'discount' => 10, 'bonus' => 20, 'rate_limit' => 500, 'bills' => [
            'airtime' => [
                'mtn' => 2,
                'airtel' => 1.5,
                '9mobile' => 1.5,
                'glo' => 2.0,
            ],
            'data' => [
                'mtn' => 11,
                'airtel' => 1.5,
                '9mobile' => 1.5,
                'glo' => 5,
            ],
        ],
        ],
        'premium' => ['amount' => 50000, 'discount' => 0.7, 'bonus' => 20, 'rate_limit' => 1000, 'bills' => [
            'airtime' => [
                'mtn' => 3,
                'airtel' => 2.5,
                '9mobile' => 2.5,
                'glo' => 2.9,
            ],
            'data' => [
                'mtn' => 16.6,
                'airtel' => 2.5,
                '9mobile' => 2.5,
                'glo' => 7,
            ],
        ],
        ],
    ],
];
