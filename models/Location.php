<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "location".
 *
 * @property integer $id
 * @property string $name
 *
 * @property Property[] $properties
 */
class Location extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'location';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProperties()
    {
        return $this->hasMany(Property::className(), ['locationref' => 'id']);
    }
    
    public static function forDropDown()
    {
        static $dropdown;
        if ($dropdown == NULL)
        {
            $locations = static::find()->all();
            
            foreach ($locations as $location) {
                $dropdown[$location->id] = $location->name;
            }
        }
        return $dropdown;
    }
}
