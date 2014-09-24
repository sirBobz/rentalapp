<?php

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

\Yii::$container->set('app\services\IReceiveIpnPayment', 'app\services\IpnPaymentReceiver');
\Yii::$container->set('app\services\IProcessPayment', 'app\services\PaymentProcessor');
\Yii::$container->set('app\services\IMatchPayment', 'app\services\PaymentMatcher');

$config = require(__DIR__ . '/../config/web.php');

(new yii\web\Application($config))->run();
