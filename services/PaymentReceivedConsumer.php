<?php
namespace app\services;

use app\services\IProcessPayment;

class PaymentReceivedConsumer
{
    protected $paymentProcessor;
    
    public function __construct(IProcessPayment $paymentProcessor) {
        $this->paymentProcessor = $paymentProcessor;
    }

    public function processEvent($event)
    {
        if ($event->message != NULL)
            $this->paymentProcessor->Process($event->message);
        
    }
}
?>