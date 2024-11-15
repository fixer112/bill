<?php
return [
    "from_date" => "",
    "social" => [
        'facebook' => 'https://web.facebook.com/moniwalletng',
        'twitter' => 'https://twitter.com/moniwalletng',
        'instagram' => 'https://instagram.com/moniwalletng',

    ],
    'recent_page' => 100,
    'per_page' => 1000,
    'mobile_networks' => ['mtn' => '15', 'mtn_sme' => '15', 'mtn_sns' => '15', 'airtel' => '1', '9mobile' => '2', 'glo' => '6'],
    'default' => [
        'airtime' => [
            'mtn' => 3,
            'mtn_sns' => 5.5,
            'airtel' => 3.6,
            'glo' => 5,
            '9mobile' => 5.5,
        ],
        'data' => [
            'mtn' => 3,
            'mtn_sme' => 18.75, //25, //21.25,
            'airtel' => 3.6,
            'glo' => 8.0,
            '9mobile' => 5.5,
        ],
        'cable' => [
            'startimes' => 1,
            'gotv' => 1,
            'dstv' => 1,
        ],
        'electricity' => 0,
        'sms' => 2,
    ],
    'bills' => [
        'sms' => [

            'basic' => 2,
            'basic_dnd' => 3,
        ],
        'airtime' => [
            'mtn' => ['min' => 50, 'max' => 20000],
            'mtn_sns' => ['min' => 50, 'max' => 50000],
            'airtel' => ['min' => 50, 'max' => 20000],
            '9mobile' => ['min' => 50, 'max' => 20000],
            'glo' => ['min' => 50, 'max' => 20000]],

        'data' => [
            'mtn_sme' => [
                [
                    'id' => "1GB",
                    'topup_currency' => "NGN",
                    'topup_amount' => 400,
                    'price' => 1000,
                    //'data_amount' => "1000",
                    'validity' => "30days",
                    'type' => 'sme',
                ],
                [
                    'id' => "2GB",
                    'topup_currency' => "NGN",
                    'topup_amount' => 800,
                    'price' => 2000,
                    //'data_amount' => "2000",
                    'validity' => "30days",
                    'type' => 'sme',
                ],

                [
                    'id' => "3GB",
                    'topup_currency' => "NGN",
                    'topup_amount' => 1200,
                    'price' => 3000,
                    //'data_amount' => "3000",
                    'validity' => "30days",
                    'type' => 'sme',
                ],
                [
                    'id' => "5GB",
                    'topup_currency' => "NGN",
                    'topup_amount' => 2000,
                    'price' => 5000,
                    //'data_amount' => "5000",
                    'validity' => "30days",
                    'type' => 'sme',
                ],
            ],
            /* 'mtn' => [

        ], */
        ],

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
                    'price' => 410,
                    'amount' => 410,
                    'duration' => '1 month',
                ],

                [
                    'name' => 'max',
                    'price' => 3280,
                    'amount' => 3280,
                    'duration' => '1 month',
                ],

                [
                    'name' => 'jinja',
                    'price' => 1640,
                    'amount' => 1640,
                    'duration' => '1 month',
                ],
                [
                    'name' => 'jolli',
                    'price' => 2460,
                    'amount' => 2460,
                    'duration' => '1 month',
                ],
            ],
            'dstv' => [
                [
                    'name' => 'padi',
                    'price' => 1850,
                    'amount' => 1850,
                    'duration' => '1 month',
                ],
                [
                    'name' => 'great wall standalone',
                    'price' => 1285,
                    'amount' => 1285,
                    'duration' => '1 month',
                ],
                [
                    'name' => 'compact',
                    'price' => 6975,
                    'amount' => 6975,
                    'duration' => '1 month',
                ],
                [
                    'name' => 'compact plus',
                    'price' => 10925,
                    'amount' => 10925,
                    'duration' => '1 month',
                ],
                [
                    'name' => 'premium',
                    'price' => 16200,
                    'amount' => 16200,
                    'duration' => '1 month',
                ],
                [
                    'name' => 'premium asia',
                    'price' => 18150,
                    'amount' => 18150,
                    'duration' => '1 month',
                ],
                [
                    'name' => 'asian bouquet',
                    'price' => 5540,
                    'amount' => 5540,
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
                    'price' => 2565,
                    'amount' => 2565,
                    'duration' => '1 month',
                ],
                [
                    'name' => 'confam',
                    'price' => 4615,
                    'amount' => 4615,
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
                'mtn_sns' => 1,
                'mtn' => 0.5,
                'airtel' => 0.5,
                '9mobile' => 0.5,
                'glo' => 1,
            ],
            'data' => [
                'mtn_sme' => 0, //12.5, //10, //4.5,
                'mtn' => 0.5,
                'airtel' => 0.5,
                '9mobile' => 0.5,
                'glo' => 1,
            ],
            'cable' => [
                'startimes' => 50,
                'gotv' => 50,
                'dstv' => 50,
            ],
            'electricity' => 50,
            'sms' => 4,
        ],
    ],

    'subscriptions' => [
        'basic' => ['amount' => 5000, 'discount' => 2, 'bonus' => 20, 'rate_limit' => 100, 'portal' => false, 'bills' => [
            'airtime' => [
                'mtn_sns' => 2,
                'mtn' => 1,
                'airtel' => 1.8,
                '9mobile' => 1.8,
                'glo' => 2.0,
            ],
            'data' => [
                'mtn_sme' => 5, //12.5, //7,
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
            'electricity' => 50,
            'sms' => 3,
        ],
        ],
        'silver' => ['amount' => 10000, 'discount' => 5, 'bonus' => 20, 'rate_limit' => 200, 'portal' => false, 'bills' => [
            'airtime' => [
                'mtn_sns' => 2.5,
                'mtn' => 2.5,
                'airtel' => 2.0,
                '9mobile' => 2.0,
                'glo' => 2.2,
            ],
            'data' => [
                'mtn_sme' => 7.5, //17.5, //15, //9,
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
            'electricity' => 50,
            'sms' => 2.5,
        ],
        ],

        'premium' => ['amount' => 30000, 'discount' => 0.7, 'bonus' => 5, 'rate_limit' => 1000, 'portal' => true, 'bills' => [
            'airtime' => [
                'mtn_sns' => 3,
                'mtn' => 3,
                'airtel' => 2.2,
                '9mobile' => 2.2,
                'glo' => 2.5,
            ],
            'data' => [
                'mtn_sme' => 12.5, //17, //12, //15,
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
            'electricity' => 50,
            'sms' => 2.5,
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