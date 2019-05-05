<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'chr',
    'name' => 'Country House Realty',
    'language' => 'ru-RU',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
		'@npm'   => '@vendor/npm-asset',
		'@uploadDir' => '/uploads',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => $params['cookieValidationKey'],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\tables\User',
            'enableAutoLogin' => true,
            'loginUrl' => '/user/login',
        ],
        'errorHandler' => [
            'errorAction' => 'property/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'logFile' => '@runtime/logs/my.log',
                    'categories' => ['app\*'],
                    'levels' => ['trace'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
				'' => 'property/index',
                'property/<action:(admin|create|error|update|delete|send-object-to-archive|restore-object-to-archive|recovery-data|delete-photo)>' => 'property/<action>',
				[
					'pattern' => 'property/<page:\d+>',
					'route' => 'property/list',
					'defaults' => ['page' => 1],
				],
                [
                    'pattern' => 'property/<slug>',
                    'route' => 'property/view',
                ],
                [
                    'pattern' => 'property/id/<id:\d+>',
                    'route' => 'property/view-by-id',
                ],
                'direction' => 'direction/index',
                'contact' => 'article/contact',
            ],
        ],
        'assetManager' => [
            'class' => 'yii\web\AssetManager',
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'js' => [
                        'jquery.min.js'
                    ]
                ],
                'yii\bootstrap4\BootstrapAsset' => [
                    'css' => [
                        'css/bootstrap.min.css',
                    ]
                ],
                'yii\bootstrap4\BootstrapPluginAsset' => [
                    'js' => [
                        'js/bootstrap.min.js',
                    ]
                ]
            ]
		],
    ],
    'params' => $params,
    'modules' => [
        'gridview' =>  [
            'class' => '\kartik\grid\Module'
        ]
    ],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1', '*'],
    ];
}

return $config;
