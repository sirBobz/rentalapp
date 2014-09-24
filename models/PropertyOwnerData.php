<?php
namespace app\models;

use Yii;

/**
 * This is the model class for table "propertyowner".
 *
 * The followings are the available columns in table 'propertyowner':
 * @property integer $entityref
 * @property string $krapin
 */
class PropertyOwnerData extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'propertyowner';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['krapin', 'entityref'], 'required'],
            [['entityref'], 'integer'],
            [['krapin'], 'string', 'max' => 20],
            [['krapin'], 'unique']
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProperties()
    {
        return $this->hasMany(Property::className(), ['propertyownerref' => 'entityref']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntity()
    {
        return $this->hasOne(Entity::className(), ['id' => 'entityref']);
    }
}
?>
