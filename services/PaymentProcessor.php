<?php
namespace app\services;

class PaymentProcessor implements IProcessPayment
{
    private $paymentMatcher;

    public function __construct(IMatchPayment $paymentMatcher) {
        $this->paymentMatcher = $paymentMatcher;
    }
            
    function Process(\yii\base\Object $payment)
    {
        $matchResults = $this->paymentMatcher->MatchTenantAccount($payment);
        $numMatches = count($matchResults);
        
        //exact match, therefore assign
        if ($numMatches == 1)
        {
            $exactMatch = $matchResults[0];
            $rental = \app\models\Rental::findOne($exactMatch['id']);
            $assignment = $rental->creditOnPaymentReceived($payment['amount'], $payment['id']);
            
            if ($exactMatch['rentalstatus'] == \app\models\Rental::STATUS_RENTAL_PENDING_DEPOSIT)
            {
                if ($rental->currentbalance >= 0)
                {
                    //update rentalstatus
                    $rental->rentalstatus = \app\models\Rental::STATUS_RENTAL_ACTIVE;
                                        
                    //create currentexpiry
                    $firstDayOfMonth = date('Y-m-01', strtotime($exactMatch['billingstartdate']));
                    $offset = $exactMatch['depositrentalperiodpaidfor'];
                    $expiryDate = date('Y-m-d', strtotime("+$offset months", strtotime($firstDayOfMonth)));
                    
                    $rentalExpiry = new \app\models\Currentrentalexpiry;
                    $rentalExpiry->rentalid = $exactMatch['id'];
                    $rentalExpiry->expirydate =  $expiryDate;//add depositmonthspaidfor + billingstart
                    $rentalExpiry->save();
                }
            }
            $rental->save();
        }
        //multiple matches, list under payment exceptions
        else
        {
            //do nothing
        }
    }
}
?>
