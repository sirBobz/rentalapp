<?php
namespace app\services;
/**
 *
 * @author JLukoba
 */
interface IProcessPayment {
    function Process(\yii\base\Object $payment);
}

?>
