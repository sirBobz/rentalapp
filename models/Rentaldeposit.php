<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rentaldeposit".
 *
 * @property string $id
 * @property string $accountentryref
 *
 * @property Accountentry $accountentryref0
 */
class Rentaldeposit extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rentaldeposit';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['accountentryref'], 'required'],
            [['accountentryref'], 'integer']
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
