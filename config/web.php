<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'VrYNkkv4J4_w-6DfYPaVRFQk12SbuNwi',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => array('login')
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
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
        'db' => $db,

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                "" => "site/index",
                "sql-test" =>"sql-test/index",
                "login" => "site/login",
                "logout" => "site/logout",
                "create-user" => "site/create-user",
                "create-project" => "project/create-project",
                "edit-project/<action:(set|get)>/<id:\d+>" => "project/edit-project",
                "remove-project/<id:\d+>" => "project/remove-project",
                "create-task/<id:\d+>" => "task/create-task",
                "edit-task/<action:(set|get)>/<id:\d+>" => "task/edit-task",
                "remove-task/<id:\d+>" => "task/remove-task",
                "task-up/<project_id:\d+>/<id:\d+>" => "task/task-up",
                "task-down/<project_id:\d+>/<id:\d+>" => "task/task-down",
                "refresh-task/<id:\d+>" => "task/refresh-task",
            ],
        ],

    ],
    'params' => $params,
];

if (false) {  //YII_ENV_DEV
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
