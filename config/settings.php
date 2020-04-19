<?php
return [
    'mobile_networks' => ['mtn' => '15', 'airtel' => 1, '9mobile' => '2', 'glo' => '6'],
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
                'mtn' => 1,
                'airtel' => 1,
                '9mobile' => 1,
                'glo' => 1,
            ],
            'data' => [
                'mtn' => 2,
                'airtel' => 2,
                '9mobile' => 2,
                'glo' => 2,
            ],
        ],
    ],

    'subscriptions' => [
        'basic' => ['amount' => 5000, 'discount' => 2, 'bonus' => 10, 'rate_limit' => 100, 'bills' => [
            'airtime' => [
                'mtn' => 1.5,
                'airtel' => 1.5,
                '9mobile' => 1.5,
                'glo' => 1.5,
            ],
            'data' => [
                'mtn' => 5,
                'airtel' => 5,
                '9mobile' => 5,
                'glo' => 5,
            ],
        ],
        ],
        'silver' => ['amount' => 10000, 'discount' => 5, 'bonus' => 12, 'rate_limit' => 200, 'bills' => [
            'airtime' => [
                'mtn' => 1.6,
                'airtel' => 1.6,
                '9mobile' => 1.6,
                'glo' => 1.6,
            ],
            'data' => [
                'mtn' => 5,
                'airtel' => 5,
                '9mobile' => 5,
                'glo' => 5,
            ],
        ],
        ],
        'gold' => ['amount' => 30000, 'discount' => 10, 'bonus' => 13, 'rate_limit' => 500, 'bills' => [
            'airtime' => [
                'mtn' => 1.8,
                'airtel' => 1.8,
                '9mobile' => 1.8,
                'glo' => 1.8,
            ],
            'data' => [
                'mtn' => 10,
                'airtel' => 10,
                '9mobile' => 10,
                'glo' => 10,
            ],
        ],
        ],
        'premium' => ['amount' => 50000, 'discount' => 0.7, 'bonus' => 15, 'rate_limit' => 1000, 'bills' => [
            'airtime' => [
                'mtn' => 2,
                'airtel' => 2,
                '9mobile' => 2,
                'glo' => 2,
            ],
            'data' => [
                'mtn' => 15,
                'airtel' => 15,
                '9mobile' => 15,
                'glo' => 15,
            ],
        ],
        ],
    ],
];