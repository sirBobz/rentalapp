<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "latepaymentrentallog".
 *
 * @property string $id
 * @property integer $year
 * @property integer $month
 * @property string $rentalref
 * @property string $amountcharged
 * @property string $datecreated
 *
 * @property Rental $rentalref0
 */
class Latepaymentrentallog extends \yii\db\ActiveRecord
{
    public $tenantname;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'latepaymentrentallog';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['year', 'month', 'rentalref', 'amountcharged', 'datecreated'], 'required'],
            [['year', 'month', 'rentalref'], 'integer'],
            [['amountcharged'], 'number'],
            [['datecreated'], 'safe'],
            [['year', 'month', 'rentalref'], 'unique', 'targetAttribute' => ['year', 'month', 'rentalref'], 'message' => 'The combination of Year, Month and Rentalref has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'year' => 'Year',
            'month' => 'Month',
            'rentalref' => 'Rentalref',
            'amountcharged' => 'Amountcharged',
            'datecreated' => 'Datecreated',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRentalref0()
    {
        return $this->hasOne(Rental::className(), ['id' => 'rentalref']);
    }
}
