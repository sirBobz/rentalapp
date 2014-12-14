<?php
use kartik\mpdf\Pdf;

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'extensions' => require(__DIR__ . '/../vendor/yiisoft/extensions.php'),
    'components' => [
        'request' => [
            'enableCookieValidation' => TRUE,
            'enableCsrfValidation' => TRUE,
            'cookieValidationKey' => 'deirewoe80w870defkljpsiqad;saipu23o43efjd'
        ],
        'urlManager' => [
          'enablePrettyUrl' => TRUE
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager'
        ],
        'metaInfo' => [
            'class' => 'app\components\Metadata'
        ],
        /*'amqp' => [
            'class' => 'app\components\Qp'
        ],*/
        'cache' => [
            'class' => 'yii\caching\XCache',
        ],
        'user' => [
            'identityClass' => 'app\models\Login',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => FALSE,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.gmail.com',
                'username' => 'daniel@lukoba.com',
                'password' => '43ffstop',
                'port' => '587',
                'encryption' => 'tls'
            ]
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
        'db' => require(__DIR__ . '/db.php'),
        'pdf' => [
            'class' => Pdf::className(),
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_DOWNLOAD,
            'mode' => Pdf::MODE_UTF8
        ]
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';
}

return $config;
