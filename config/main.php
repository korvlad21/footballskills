<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);
//
return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log', 'debug', 'gii'],
    'language' => 'ru-Ru',
    'name' => 'Альфа Декор',
    'modules' => [
        'gridview' =>  [
            'class' => '\kartik\grid\Module'
        ],
        'debug' => [
            'class' => '\yii\debug\Module',
            // uncomment and adjust the following to add your IP if you are not connecting from localhost.
            'allowedIPs' => ['*'],
        ],
        'gii' => [
            'class' => 'yii\gii\Module',
            'allowedIPs' => ['*'] // adjust this to your needs
        ],
    ],
    'components' => [
        'request' => [
            'baseUrl' => '/icms',
            'csrfParam' => '_csrf-backend',
            'cookieValidationKey' => '345678uh$%gjvDUFSGYH*9m_A*SFS^(&90w3t5',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
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

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '/login' => '/user/default/login',
                '/' => '/order',
            ],
        ],
        'sitemapGenerator' => [
            'class' => '\common\components\SitemapGeneratorComponent'
        ],
        'assetManager' => [
            'bundles' => [
                'kartik\form\ActiveFormAsset' => [
                    'bsDependencyEnabled' => false // do not load bootstrap assets for a specific asset bundle
                ],
            ],
        ],

    ],
    'params' => $params,

    'as beforeRequest' => [
        'class' => 'yii\filters\AccessControl',
        'rules' => [
            [
                'allow' => true,
                'actions' => ['login', 'error'],
            ],
            [
                'allow' => true,
                'roles' => ['@'],
            ],
        ],
        'denyCallback' => function () {
            return Yii::$app->response->redirect(['site/login']);
        },
    ],

];
