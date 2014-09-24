<?php
namespace app\services;

use app\models\Payment;

class IpnPaymentReceiver extends \yii\base\Object implements IReceiveIpnPayment
{
    public function parse(array $ipnData) {
        if (!is_numeric($ipnData['amount']))
            return NULL;
                
        $parseDate = date_parse($ipnData['paymentdate']);
        $dateparseErrors = $parseDate['error_count'];
        if ($dateparseErrors > 0)
            return NULL;
        
        if(!is_numeric($ipnData['ipnid']))
            return NULL;
        
        $payment = new Payment($ipnData['amount'], isset($ipnData['ipnid']) ? $ipnData['ipnid'] : null, 
                $ipnData['paidinby'], $ipnData['paymentdate'], $ipnData['paymentphone'], 
                isset($ipnData['paymentreference']) ? $ipnData['paymentreference'] : null, 
                $ipnData['receiptnumber']);
        $payment->save();
        
        return($payment);
    }

    public function Acknowledge(\yii\base\Object $payment) {
        if($payment == NULL)
            exit("Error composing payment");
        else
            exit("Payment received successfully");
    }
    
    public function notifyUponPaymentCreation(\yii\base\Object $payment)
    {
        /*$payment->on(\app\models\Payment::EVENT_PAYMENT_CREATED, function ($event)
        {
            print_r($event->sender);
        });*/
        $payment->notifyUponCreation();
    }
}
?>
