<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "accountentry".
 *
 * @property string $id
 * @property integer $type
 * @property string $datecreated
 * @property string $rentalref
 * @property string $amount
 * @property string $createdbyref
 *
 * @property Rental $rentalref0
 * @property Rentalpayment[] $rentalpayments
 * @property Transfer[] $transfers
 */
class AccountEntry extends \yii\db\ActiveRecord
{
    const ACCOUNTTYPE_CREDIT = 1;
    const ACCOUNTTYPE_DEBIT = 2;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'accountentry';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'datecreated', 'rentalref', 'amount', 'createdbyref'], 'required'],
            [['type', 'rentalref'], 'integer'],
            [['datecreated'], 'safe'],
            [['amount'], 'number'],
            [['createdbyref'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'datecreated' => 'Datecreated',
            'rentalref' => 'Rentalref',
            'amount' => 'Amount',
            'createdbyref' => 'Createdbyref',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRentalref0()
    {
        return $this->hasOne(Rental::className(), ['id' => 'rentalref']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRentalpayments()
    {
        return $this->hasMany(Rentalpayment::className(), ['accountentryref' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransfers()
    {
        return $this->hasMany(Transfer::className(), ['accountentryref' => 'id']);
    }
}
