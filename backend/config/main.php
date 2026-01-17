<?php

use yii\log\FileTarget;

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
                    'class' => FileTarget::class,
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
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/auth',
                    'pluralize' => false,
                    'extraPatterns' => [
                        'POST login' => 'login',
                        'POST signup' => 'signup',
                    ],
                ],

                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/user',
                    'extraPatterns' => [
                        'GET me' => 'me',
                        'PUT me' => 'update-me',
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/animal',
                    'pluralize' => true,
                    'extraPatterns' => [
                        'PUT <id:\d+>' => 'update',
                        'GET my' => 'myanimals',
                        'GET meta' => 'meta',
                        'GET edit/<id:\d+>' => 'edit',
                        'POST {id}/view' => 'add-view',
                        'GET stats' =>'stats',
                    ],
                ],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'api/message', 'pluralize' => true,],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/application',
                    'pluralize' => false,
                    'extraPatterns' => [
                        //VERBO na URL // actionSent no ApplicationController
                        'GET sent' => 'sent',
                        //VERBO na URL // actionReceived no ApplicationController
                        'GET received' => 'received',
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['api/file'],
                    'pluralize' => false,
                    'extraPatterns' => [
                        'POST delete' => 'delete',
                        // ver file por ID
                        'GET animal/<animal_id:\d+>' => 'view-animal',
                        // upload múltiplo
                        'POST animal-photos' => 'create',

                        // avatar
                        'GET avatar/<user_id:\d+>' => 'view-avatar',

                        // delete imagem
                        'DELETE <id:\d+>' => 'delete',
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/comment',
                    'pluralize' => true,

                ],
            ],
        ],

    ],
    'params' => $params,
];
