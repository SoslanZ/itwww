<?php
return [
    'language' => 'ru-RU',
    //'timeZone' => 'Asia/Yekaterinburg',
    'timeZone' => 'UTC',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
	'formatter' => [
		'nullDisplay' => '&nbsp;',
	]
    ],
];
