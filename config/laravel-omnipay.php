<?php

return [

	// The default gateway to use
	'default' => 'alipay',

	// Add in each gateway here
	'gateways' => [
		'paypal' => [
			'driver'  => 'PayPal_Express',
			'options' => [
				'solutionType'   => '',
				'landingPage'    => '',
				'headerImageUrl' => ''
			]
		],
        'WechatPay' => [
            'MerchantID' => '',
            'AppID' => '',
            'Key' => '',
            'NU_Native' => '/pay/notify/wechatpay_native/',
        ],
        // alipay pc
        'Alipay_LegacyExpress' => [
            'RU' => '/pay/return/alipay_legacyexpress/',
            'NU' => '/pay/notify/alipay_legacyexpress/',
            'PartnerID' => '',
            'SellerID' => '',
            'SellerEmail' => '',
            'Key' => '',
        ],
        // alipay wap (mobile)
        'Alipay_AopWap' => [
            'RU' => '/pay/return/alipay_aopwap/',
            'NU' => '/pay/notify/alipay_aopwap/',
            'AppID' => '',
            'PrivateKey' => '',

            'PublicKey' => '',
        ],
	],
];