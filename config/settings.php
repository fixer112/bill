<?php
return [
    "social" => [
        'facebook' => 'https://web.facebook.com/moniwalletng',
        'twitter' => 'https://twitter.com/moniwalletng',
        'instagram' => 'https://instagram.com/moniwalletng',

    ],
    'recent_page' => 10,
    'per_page' => 1000,
    'mobile_networks' => ['mtn' => '15', 'mtn_sme' => '15', 'mtn_sns' => '15', 'airtel' => '1', '9mobile' => '2', 'glo' => '6'],
    'bills' => [

        'airtime' => [
            'mtn' => ['min' => 50, 'max' => 50000],
            'mtn_sns' => ['min' => 50, 'max' => 50000],
            'airtel' => ['min' => 50, 'max' => 50000],
            '9mobile' => ['min' => 50, 'max' => 50000],
            'glo' => ['min' => 50, 'max' => 50000]],
        'cable' => [
            'startimes' => [
                [
                    'name' => 'nova',
                    'price' => 900,
                    'amount' => 900,
                    'duration' => '1 month',
                ],
                [
                    'name' => 'basic',
                    'price' => 1300,
                    'amount' => 1300,
                    'duration' => '1 month',
                ],
                [
                    'name' => 'classic',
                    'price' => 1900,
                    'amount' => 1900,
                    'duration' => '1 month',
                ],
                [
                    'name' => 'super',
                    'price' => 3800,
                    'amount' => 3800,
                    'duration' => '1 month',
                ],
            ],
            'gotv' => [
                [
                    'name' => 'lite',
                    'price' => 400,
                    'amount' => 400,
                    'duration' => '1 month',
                ],
                [
                    'name' => 'value',
                    'price' => 1250,
                    'amount' => 1250,
                    'duration' => '1 month',
                ],
                [
                    'name' => 'plus',
                    'price' => 1900,
                    'amount' => 1900,
                    'duration' => '1 month',
                ],
                [
                    'name' => 'max',
                    'price' => 3200,
                    'amount' => 3200,
                    'duration' => '1 month',
                ],
                [
                    'name' => 'super',
                    'price' => 3800,
                    'amount' => 3800,
                    'duration' => '1 month',
                ],
                [
                    'name' => 'lite',
                    'price' => 1050,
                    'amount' => 1050,
                    'duration' => '4 month',
                ],
                [
                    'name' => 'jinja',
                    'price' => 1600,
                    'amount' => 1600,
                    'duration' => '1 month',
                ],
                [
                    'name' => 'jolli',
                    'price' => 2400,
                    'amount' => 2400,
                    'duration' => '1 month',
                ],
            ],
            'dstv' => [
                [
                    'name' => 'access',
                    'price' => 2000,
                    'amount' => 2000,
                    'duration' => '1 month',
                ],
                [
                    'name' => 'family',
                    'price' => 4000,
                    'amount' => 4000,
                    'duration' => '1 month',
                ],
                [
                    'name' => 'compact',
                    'price' => 6800,
                    'amount' => 6800,
                    'duration' => '1 month',
                ],
                [
                    'name' => 'compact plus',
                    'price' => 10650,
                    'amount' => 10650,
                    'duration' => '1 month',
                ],
                [
                    'name' => 'premium',
                    'price' => 15800,
                    'amount' => 15800,
                    'duration' => '1 month',
                ],
                [
                    'name' => 'premium asia',
                    'price' => 17700,
                    'amount' => 17700,
                    'duration' => '1 month',
                ],
                [
                    'name' => 'asian bouquet',
                    'price' => 5400,
                    'amount' => 5400,
                    'duration' => '1 month',
                ],
                [
                    'name' => 'fta plus',
                    'price' => 1600,
                    'amount' => 1600,
                    'duration' => '1 month',
                ],
                [
                    'name' => 'yanga',
                    'price' => 2500,
                    'amount' => 2500,
                    'duration' => '1 month',
                ],
                [
                    'name' => 'confam',
                    'price' => 4500,
                    'amount' => 4500,
                    'duration' => '1 month',
                ],
            ],
        ],

    ],
    'referral' => [
        'commision' => [1 => ['bonus' => 5.0, 'refer_bonus' => 100, 'point' => 100]/* , 2 => ['bonus' => 3.0, 'refer_bonus' => 50, 'point' => 50], 3 => ['bonus' => 1.5, 'refer_bonus' => 25, 'point' => 20] */],
        'levels' => ['novice' => 0, 'manager' => 10000, 'super manager' => 20000],
    ],

    'individual' => [
        'bills' => [
            'airtime' => [
                'mtn_sns' => 2,
                'mtn' => 0.7,
                'airtel' => 0.5,
                '9mobile' => 0.5,
                'glo' => 1,
            ],
            'data' => [
                'mtn_sme' => 17.5, //10, //4.5,
                'mtn' => 0.7,
                'airtel' => 0.5,
                '9mobile' => 0.5,
                'glo' => 1,
            ],
            'cable' => [
                'startimes' => 50,
                'gotv' => 50,
                'dstv' => 50,
            ],
        ],
    ],

    'subscriptions' => [
        'basic' => ['amount' => 5000, 'discount' => 2, 'bonus' => 20, 'rate_limit' => 100, 'portal' => false, 'bills' => [
            'airtime' => [
                'mtn_sns' => 3,
                'mtn' => 2.0,
                'airtel' => 1.8,
                '9mobile' => 1.8,
                'glo' => 2.0,
            ],
            'data' => [
                'mtn_sme' => 17.5, //12.5, //7,
                'mtn' => 2.0,
                'airtel' => 1.8,
                '9mobile' => 1.8,
                'glo' => 4,
            ],
            'cable' => [
                'startimes' => 60,
                'gotv' => 60,
                'dstv' => 60,
            ],
        ],
        ],
        'silver' => ['amount' => 10000, 'discount' => 5, 'bonus' => 20, 'rate_limit' => 200, 'portal' => false, 'bills' => [
            'airtime' => [
                'mtn_sns' => 3,
                'mtn' => 2.5,
                'airtel' => 2.0,
                '9mobile' => 2.0,
                'glo' => 2.2,
            ],
            'data' => [
                'mtn_sme' => 17.5, //15, //9,
                'mtn' => 2.5,
                'airtel' => 2.0,
                '9mobile' => 2.0,
                'glo' => 5,
            ],
            'cable' => [
                'startimes' => 70,
                'gotv' => 70,
                'dstv' => 70,
            ],
        ],
        ],

        'premium' => ['amount' => 60000, 'discount' => 0.7, 'bonus' => 10, 'rate_limit' => 1000, 'portal' => true, 'bills' => [
            'airtime' => [
                'mtn_sns' => 4,
                'mtn' => 3,
                'airtel' => 2.2,
                '9mobile' => 2.2,
                'glo' => 2.5,
            ],
            'data' => [
                'mtn_sme' => 17.5, //17, //12, //15,
                'mtn' => 3,
                'airtel' => 2.2,
                '9mobile' => 2.2,
                'glo' => 7,
            ],
            'cable' => [
                'startimes' => 90,
                'gotv' => 90,
                'dstv' => 90,
            ],
        ],
        ],

        /* 'super' => ['amount' => 150000, 'discount' => 0.7, 'bonus' => 20, 'rate_limit' => 5000, 'portal' => true, 'bills' => [
    'airtime' => [
    'mtn' => 3.3,
    'airtel' => 2.5,
    '9mobile' => 2.5,
    'glo' => 2.9,
    ],
    'data' => [
    'mtn_sme' => 15, //16.6,
    'mtn' => 3.3,
    'airtel' => 2.5,
    '9mobile' => 2.5,
    'glo' => 8,
    ],
    'cable' => [
    'startimes' => 90,
    'gotv' => 90,
    'dstv' => 90,
    ],
    ],
    ], */
    ],
];