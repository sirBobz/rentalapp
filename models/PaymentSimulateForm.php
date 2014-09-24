<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * PaymentSimulateForm is the model behind the simulate payment form.
 */
class PaymentSimulateForm extends Model
{
    public $amount;
    public $paymentphone;
    public $receiptnumber;
    public $paidinby;
    public $paymentdate;
    public $ipnid;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['amount', 'paymentphone', 'paidinby', 'receiptnumber'], 'required'],
            
        ];
    }

    public function attributeLabels() {
        return [
            'amount' => 'Amount',
            'paymentphone' => 'Payment phone',
            'paidinby' => 'Paid By',
            'paymentreference' => 'Payment Reference'
        ];
    }
}
