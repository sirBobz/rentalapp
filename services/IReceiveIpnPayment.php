<?php
namespace app\services;
/**
 *
 * @author JLukoba
 */
interface IReceiveIpnPayment extends IParsePayment, INotifyUponPaymentCreation {
    function Acknowledge(\yii\base\Object $ipnData);
}

?>
