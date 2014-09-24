<?php
namespace app\services;

interface INotifyUponPaymentCreation {
    function notifyUponPaymentCreation(\yii\base\Object $payment);
}

?>
