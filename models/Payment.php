<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "payment".
 *
 * @property string $id
 * @property string $receiptnumber
 * @property string $amount
 * @property string $datecreated
 * @property string $paymentdate
 * @property string $ipnid
 * @property string $paidinby
 * @property string $paymentphonenumber
 * @property string $paymentreference
 * @property int $paymentstatus
 *
 * @property Rentalpayment[] $rentalpayments
 */
class Payment extends \yii\db\ActiveRecord
{
    const STATUS_PAYMENT_PENDING_ASSIGNMENT = 1;
    const STATUS_PAYMENT_ASSIGNED = 2;
    const STATUS_PAYMENT_REVERSED = 3;
    
    const EVENT_PAYMENT_COMPOSED = "paymentComposed";

    /*public function __construct($amount, $ipnid, $paidinby, $paymentdate, $paymentphone, 
            $paymentreference = null, $receiptnumber) {
        $this->datecreated = date('Y-m-d H:i:s');
        $this->amount = $amount;
        $this->ipnid = $ipnid;
        $this->paidinby = $paidinby;
        $this->paymentdate = $paymentdate;
        $this->paymentphonenumber = $paymentphone;
        $this->paymentreference = $paymentreference;
        $this->receiptnumber = $receiptnumber;
        $this->paymentstatus = self::STATUS_PAYMENT_PENDING_ASSIGNMENT;
    }*/

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['receiptnumber', 'amount', 'datecreated', 'paymentdate', 'paidinby', 'paymentphonenumber'], 'required'],
            [['amount'], 'number'],
            [['datecreated', 'paymentdate'], 'safe'],
            [['ipnid'], 'integer'],
            [['receiptnumber'], 'string', 'max' => 20],
            [['paidinby', 'paymentphonenumber', 'paymentreference'], 'string', 'max' => 45],
            [['receiptnumber'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'receiptnumber' => 'Receipt number',
            'amount' => 'Amount',
            'datecreated' => 'Datecreated',
            'paymentdate' => 'Paymentdate',
            'ipnid' => 'Ipnid',
            'paidinby' => 'Paid by',
            'paymentphonenumber' => 'Payment phonenumber',
            'paymentreference' => 'Payment reference',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRentalpayments()
    {
        return $this->hasMany(Rentalpayment::className(), ['paymentref' => 'id']);
    }
        
    public function notifyUponCreation()
    {
        $event = new \app\events\PaymentComposedEvent();
        $event->message = $this;
        
        $this->trigger(self::EVENT_PAYMENT_COMPOSED, $event);
    }
    
    public function beforeValidate() {
        if(parent::beforeValidate())
        {
            $this->datecreated = date('Y-m-d H:i:s');
            $this->paymentstatus = self::STATUS_PAYMENT_PENDING_ASSIGNMENT;
                
            return TRUE;
        }
    }
    
    public function reverse()
    {
        $this->paymentstatus = self::STATUS_PAYMENT_REVERSED;
    }
}
