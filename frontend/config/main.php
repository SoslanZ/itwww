<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'language' => 'ru-RU',
    'id' => 'itwwwcc-app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'user' => [
            'identityClass' => 'frontend\models\OperAuth',
            //'enableAutoLogin' => true,
            'on afterLogin' => function($event) {
                    Yii::$app->user->identity->afterLogin($event);
                },
            'on beforeLogout' => function($event) {
                    Yii::$app->user->identity->afterLogout($event);
                }
        ],
        /*'user' => [
            'identityClass' => 'common\models\UserSimple',
            'enableAutoLogin' => true,
            'on afterLogin' => function($event) {
                    Yii::$app->user->identity->afterLogin($event);
                },
            'on beforeLogout' => function($event) {
                    Yii::$app->user->identity->afterLogout($event);
                }
        ],*/
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
    ],
    'params' => $params,
];
