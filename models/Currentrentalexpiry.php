<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "currentrentalexpiry".
 *
 * @property string $rentalid
 * @property string $expirydate
 */
class Currentrentalexpiry extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'currentrentalexpiry';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rentalid', 'expirydate'], 'required'],
            [['rentalid'], 'integer'],
            [['expirydate'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rentalid' => 'Rentalid',
            'expirydate' => 'Expirydate',
        ];
    }
}
