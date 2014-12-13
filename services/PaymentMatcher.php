<?php
namespace app\services;

class PaymentMatcher implements IMatchPayment
{
    function MatchTenantAccount(\yii\base\Object $payment) {
        $accountNumber = $payment['paymentreference'];
        $phoneNumber = $payment['paymentphonenumber'];
        
        //this is in query form
        $rentalMatch = \app\models\Rental::find()->select(['rental.id', 'rental.accountnumber', 
            'rental.rentalstatus', 'rental.depositamount', 'rental.depositrentalperiodpaidfor', 
            'rental.billingstartdate'])->
        innerJoin('entity e', 'rental.tenantref = e.id')->
                where([
                    'rental.datedestroyed' => null, 
                    'rental.rentalstatus' => [\app\models\Rental::STATUS_RENTAL_PENDING_DEPOSIT, 
                        \app\models\Rental::STATUS_RENTAL_ACTIVE]]);
                
        if(!empty($accountNumber))
        {
            $exactMatch = $rentalMatch->where(['rental.accountnumber' => $accountNumber])->one();
            
            //exact match found
            if($exactMatch != NULL)
                return $exactMatch;
        }
        if(!empty($phoneNumber))
        {
            $matches = $rentalMatch->where(['e.phonenumber' => $phoneNumber])->all();
            return $matches;
        }
        /*if (!empty($accountNumber))
            $rentalMatch->where(['rental.accountnumber' => $accountNumber]);
        if(!empty($payment['paymentphonenumber']))
            $rentalMatch->where(['e.phonenumber' => $payment['paymentphonenumber'], 
                'e.entitytype' => 'Tenant']);
        
        $matches = $rentalMatch->asArray()->all();
        
        return $matches;*/
    }
}
?>
