<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "depositrefund".
 *
 * @property string $id
 * @property string $accountentryref
 * @property integer $approvalstatus
 *
 * @property Accountentry $accountentryref0
 */
class DepositRefund extends \yii\db\ActiveRecord
{
    const STATUS_PENDING_APPROVAL = 1;
    const STATUS_GRANTED = 2;
    const STATUS_DECLINED = 3;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'depositrefund';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['accountentryref', 'approvalstatus'], 'required'],
            [['accountentryref', 'approvalstatus'], 'integer']
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
