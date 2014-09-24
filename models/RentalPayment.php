<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rentalpayment".
 *
 * @property string $id
 * @property string $accountentryref
 * @property string $paymentref
 * @property string $description
 *
 * @property Accountentry $accountentryref0
 * @property Payment $paymentref0
 */
class RentalPayment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rentalpayment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['accountentryref', 'paymentref'], 'required'],
            [['accountentryref', 'paymentref'], 'integer'],
            [['description'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'accountentryref' => 'Accountentryref',
            'paymentref' => 'Paymentref',
            'description' => 'Description',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountentryref0()
    {
        return $this->hasOne(Accountentry::className(), ['id' => 'accountentryref']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentref0()
    {
        return $this->hasOne(Payment::className(), ['id' => 'paymentref']);
    }
}
