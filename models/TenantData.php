<?php
namespace app\models;

use Yii;

/**
 * This is the model class for table "tenant".
 *
 * The followings are the available columns in table 'tenant':
 * @property integer $entityref
 
 * @property Rental[] $rentals
 * @property Entity $entityref0

 */
class TenantData extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tenant';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['entityref'], 'required'],
            [['entityref'], 'integer']
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRentals()
    {
        return $this->hasMany(Rental::className(), ['tenantref' => 'entityref']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntityref0()
    {
        return $this->hasOne(Entity::className(), ['id' => 'entityref']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getViewableproperties()
    {
        return $this->hasMany(Viewableproperty::className(), ['tenantref' => 'entityref']);
    }
}
?>
