<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rental".
 *
 * @property string $id
 * @property string $unitref
 * @property string $datecreated
 * @property string $createdbyref
 * @property string $datedestroyed
 * @property string $destroyedbyref
 * @property integer $rentalperiod
 * @property string $amountperperiod
 * @property string $tenantref
 * @property string $depositamount
 * @property string $currentbalance
 * @property integer $depositrentalperiodpaidfor
 * @property integer $lastpaymentdate
 * @property string $latepaymentcharge
 * @property string $accountnumber
 * @property int $rentalstatus
 * @property date $billingstartdate
 *
 * @property Accountentry[] $accountentries
 * @property Unit $unitref0
 * @property Tenant $tenantref0
 */
class Rental extends \yii\db\ActiveRecord
{
    const STATUS_RENTAL_PENDING_DEPOSIT = 1;
    const STATUS_RENTAL_ACTIVE = 2;
    const STATUS_RENTAL_CLOSED = 3;
    
    public $unitname;
    public $propertyname;
    public $propertycode;
    public $tenantname;
    public $expirydate;
            
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rental';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['unitref', 'datecreated', 'createdbyref', 'rentalperiod', 'amountperperiod', 'tenantref', 'depositamount', 'currentbalance', 'depositrentalperiodpaidfor', 'lastpaymentdate', 'accountnumber', 'billingstartdate'], 'required'],
            [['unitref', 'createdbyref', 'destroyedbyref', 'rentalperiod', 'tenantref', 'depositrentalperiodpaidfor', 'lastpaymentdate', 'rentalstatus'], 'integer'],
            [['datecreated', 'datedestroyed'], 'safe'],
            [['amountperperiod', 'depositamount', 'currentbalance', 'latepaymentcharge'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'unitref' => 'Unit',
            'datecreated' => 'Datecreated',
            'createdbyref' => 'Created by',
            'rentalperiod' => 'Rental period (months)',
            'amountperperiod' => 'Amount per period',
            'tenantref' => 'Tenant',
            'depositamount' => 'Deposit amount',
            'currentbalance' => 'Current account balance',
            'depositrentalperiodpaidfor' => 'Deposit rental periods paid for',
            'lastpaymentdate' => 'Last payment date',
            'latepaymentcharge' => 'Late payment charge (%)',
            'tenantname' => 'Tenant',
            'propertyname' => 'Property name',
            'unitname' => 'Unit name',
            'propertycode' => 'Property code',
            'accountnumber' => 'Account number'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountentries()
    {
        return $this->hasMany(Accountentry::className(), ['rentalref' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnitref0()
    {
        return $this->hasOne(Unit::className(), ['id' => 'unitref']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTenantref0()
    {
        return $this->hasOne(Tenant::className(), ['entityref' => 'tenantref']);
    }
    
    public function beforeValidate() {
        if (parent::beforeValidate())
        {
            if ($this->isNewRecord)
            {
                $this->datecreated = date('Y-m-d H:i:s');
                $this->createdbyref = Yii::$app->user->id;
                $this->currentbalance = 0;
                
                if($this->depositamount > 0)
                    $this->rentalstatus = self::STATUS_RENTAL_PENDING_DEPOSIT;
                else
                    $this->rentalstatus = self::STATUS_RENTAL_ACTIVE;
            }
            return TRUE;
        }
    }
    
    public function debitDepositOnRentalCreation()
    {
        $this->debitAccountBalance($this->depositamount);
        
        if ($this->depositamount > 0)
        {
            $accountEntry = new AccountEntry;
            $accountEntry->amount = $this->depositamount;
            $accountEntry->createdbyref = \Yii::$app->user->id;
            $accountEntry->datecreated = date('Y-m-d H:i:s');
            $accountEntry->rentalref = $this->id;
            $accountEntry->type = AccountEntry::ACCOUNTTYPE_DEBIT;
            $accountEntry->save();
            
            $rentalDeposit = new Rentaldeposit;
            $rentalDeposit->accountentryref = $accountEntry->id;
            $rentalDeposit->save();
        }
        if($this->depositamount == 0)
        {
            //create rentalperioddebit
            $accountEntry = new AccountEntry;
            $accountEntry->amount = $this->amountperperiod;
            $accountEntry->createdbyref = \Yii::$app->user->id;
            $accountEntry->datecreated = date('Y-m-d H:i:s');
            $accountEntry->rentalref = $this->id;
            $accountEntry->type = AccountEntry::ACCOUNTTYPE_DEBIT;
            $accountEntry->save();
            
            $rentalPeriodDebit = new Rentalperioddebit;
            $rentalPeriodDebit->accountentryref = $accountEntry->id;
            $rentalPeriodDebit->datefrom = $this->billingstartdate;
            $rentalPeriodDebit->dateto = date('Y-m-t', strtotime($this->billingstartdate));
            $rentalPeriodDebit->save();
        }
    }
    
    public function debitAccountBalance($amount)
    {
        $this->currentbalance -= $amount;
    }
    
    public function creditAccountBalance($amount)
    {
        $this->currentbalance += $amount;
    }
    
    public function creditOnPaymentReceived($amountpaidin, $paymentid)
    {
        $this->creditAccountBalance($amountpaidin);
        
        $accountEntry = new AccountEntry;
        $accountEntry->amount = $amountpaidin;
        $accountEntry->createdbyref = \Yii::$app->user->id;
        $accountEntry->datecreated = date('Y-m-d H:i:s');
        $accountEntry->rentalref = $this->id;
        $accountEntry->type = AccountEntry::ACCOUNTTYPE_CREDIT;
                
        $isSuccessful = FALSE;
        $transaction = \Yii::$app->db->beginTransaction();
        try
        {
            $accountEntry->save();
            
            $rentalPayment = new RentalPayment;
            $rentalPayment->accountentryref = $accountEntry->id;
            $rentalPayment->paymentref = $paymentid;
            $rentalPayment->save();
            
            $transaction->commit();
            $isSuccessful = TRUE;
        }
        catch (\yii\base\Exception $e)
        {
            $transaction->rollBack();
        }
        return $isSuccessful;
    }
    
    public function creditOnManualPaymentAssignment($amountpaidin, $paymentid)
    {
        $this->creditAccountBalance($amountpaidin);
        
        $accountEntry = new AccountEntry;
        $accountEntry->amount = $amountpaidin;
        $accountEntry->createdbyref = \Yii::$app->user->id;
        $accountEntry->datecreated = date('Y-m-d H:i:s');
        $accountEntry->rentalref = $this->id;
        $accountEntry->type = AccountEntry::ACCOUNTTYPE_CREDIT;
                
        $isSuccessful = FALSE;
        $transaction = \Yii::$app->db->beginTransaction();
        try
        {
            $accountEntry->save();
            
            $rentalPayment = new RentalPayment;
            $rentalPayment->accountentryref = $accountEntry->id;
            $rentalPayment->paymentref = $paymentid;
            $rentalPayment->save();
            
            $transaction->commit();
            $isSuccessful = TRUE;
        }
        catch (\yii\base\Exception $e)
        {
            $transaction->rollBack();
        }
        return $isSuccessful;
    }
    
    public function close($login)
    {
        $this->rentalstatus = static::STATUS_RENTAL_CLOSED;
        
        $unit = $this->unitref0;
        $unit->unAssign();
        $unit->save();
        
        $accounts = \app\models\Rental::find()
                ->where(['tenantref' => $this->tenantref])
                ->all();
        
        if(count($accounts) == 1)
        {
            $login->disable();
            $login->save();
        }
        
        //refund deposit(dr)
        $refundableAmount = $this->depositamount - ($this->depositrentalperiodpaidfor * $this->amountperperiod);
        if($refundableAmount > 0)
        {
            $this->debitDepositOnAccountClosure($refundableAmount);
        }
        return $refundableAmount;
    }
    
    public function debitDepositOnAccountClosure($refundableAmount)
    {
        $this->debitAccountBalance($refundableAmount);
        
        $accountEntry = new AccountEntry;
        $accountEntry->amount = $refundableAmount;
        $accountEntry->createdbyref = \Yii::$app->user->id;
        $accountEntry->datecreated = date('Y-m-d H:i:s');
        $accountEntry->rentalref = $this->id;
        $accountEntry->type = AccountEntry::ACCOUNTTYPE_DEBIT;
        $accountEntry->save();

        $depositRefund = new DepositRefund;
        $depositRefund->accountentryref = $accountEntry->id;
        $depositRefund->approvalstatus = DepositRefund::STATUS_PENDING_APPROVAL;
        $depositRefund->save();
    }

    public static function rentalStatusDropDown()
    {
        static $dropdown;
        if ($dropdown == NULL)
        {
            $reflection = new \ReflectionClass(get_called_class());
            $constants = $reflection->getConstants();
            
            foreach ($constants as $name => $value) {
                if(strpos($name, "STATUS_RENTAL_") === 0)
                {
                    $prettyName = str_replace("STATUS_RENTAL_", "", $name);
                    $prettyName = \yii\helpers\Inflector::humanize(strtolower($prettyName));
                    $dropdown[$value] = $prettyName;
                }
            }
        }
        return $dropdown;
    }
}
