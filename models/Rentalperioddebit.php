<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rentalperioddebit".
 *
 * @property string $id
 * @property string $accountentryref
 * @property string $datefrom
 * @property string $dateto
 *
 * @property Accountentry $accountentryref0
 */
class Rentalperioddebit extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rentalperioddebit';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['accountentryref', 'datefrom', 'dateto'], 'required'],
            [['accountentryref'], 'integer'],
            [['datefrom', 'dateto'], 'safe']
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
            'datefrom' => 'Datefrom',
            'dateto' => 'Dateto',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountentryref0()
    {
        return $this->hasOne(Accountentry::className(), ['id' => 'accountentryref']);
    }
}
