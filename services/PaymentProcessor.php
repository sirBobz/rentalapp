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
            $exactMatch = $matchResults;
            
            $rental = \app\models\Rental::findOne($exactMatch['id']);
            $assignment = $rental->creditOnPaymentReceived($payment['amount'], $payment['id']);
            
            if ($exactMatch['rentalstatus'] == \app\models\Rental::STATUS_RENTAL_PENDING_DEPOSIT)
            {
                //if ($rental->currentbalance >= $rental->depositamount)
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
                    $rentalExpiry->expirydate =  date("Y-m-t", strtotime("-1 month", strtotime($expiryDate)));
                    $rentalExpiry->save();
                }
            }
            else if ($exactMatch['rentalstatus'] == \app\models\Rental::STATUS_RENTAL_ACTIVE)
            {
                if($rental->currentbalance >= $rental->amountperperiod)
                {
                    //if currentexpiry does not exist, create one,
                    //else update it
                    $currentExpiry = \app\models\Currentrentalexpiry::find()
                            ->where(['rentalid' => $exactMatch['id']])
                            ->one();
                    
                    //unmatched (zero deposit rental account)
                    if($currentExpiry == FALSE)
                    {
                        $firstDayOfMonth = date('Y-m-01', strtotime($exactMatch['billingstartdate']));
                        $offset = floor($rental->currentbalance/$rental->amountperperiod);
                        $expiryDate = date('Y-m-d', strtotime("+$offset months", strtotime($firstDayOfMonth)));

                        $rentalExpiry = new \app\models\Currentrentalexpiry;
                        $rentalExpiry->rentalid = $exactMatch['id'];
                        $rentalExpiry->expirydate =  $rentalExpiry->expirydate =  date("Y-m-t", strtotime("-1 month", strtotime($expiryDate)));
                        $rentalExpiry->save();
                    }
                    //try updating it
                    else {
                        $additions = floor($rental->currentbalance/$rental->amountperperiod);
                        
                        if($additions > 0)
                        {
                            $additions *= $rental->rentalperiod;
                            $lastDayOfCurrentExpiryMonth = date('Y-m-t', strtotime($currentExpiry['expirydate']));
                            $newExpiryDate = date('Y-m-d', strtotime("+$additions months", strtotime($lastDayOfCurrentExpiryMonth)));
                            
                            $currentExpiry->expirydate = $newExpiryDate;
                            $currentExpiry->save();
                        }
                    }
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
