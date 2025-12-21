<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'api' => [
            'class' => 'backend\modules\api\ModuleAPI',
        ],

    ],
    //
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],

        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
            'loginUrl' => ['/site/login'], //*********
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                ['class' => 'yii\rest\UrlRule',
                    'controller' => 'api/user',
                    'extraPatterns' => [
                        'GET me' => 'me',
                    ],
                ],
                ['class' => 'yii\rest\UrlRule','controller' => 'api/animal'],
                //'extraPatterns' => [
                    //'GET animalsComplete' => 'animalsComplete', // contagem é 'actionAnimalsComplete'
                    //'GET nomes' => 'nomes',
                    //'GET {id}/preco' => 'preco',
                    //'GET preco/{nomeprato}' => 'precopornome',
                    //'DELETE {nomeprato}' => 'delpornome',
                    //'PUT {nomeprato}' => 'putprecopornome',
                    //'POST vazio' => 'postpratovazio',
                //],
//                'tokens' => [
//                    '{id}' => '<id:\\d+>',
//                    '{nomeprato}' => '<nomeprato:\\w+>', //[a-zA-Z0-9_] 1 ou + vezes
//                ],
            ],
        ],

    ],
    'params' => $params,
];
