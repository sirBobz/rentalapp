<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "suite".
 *
 * @property string $id
 * @property string $name
 * @property integer $propertyref
 * @property string $description
 * @property boolean $isavailable
 *
 * @property Rental[] $rentals
 * @property Property $property
 */
class Unit extends \yii\db\ActiveRecord
{
    public $propertyname;
    public $propertyowner;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'unit';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'propertyref'], 'required'],
            [['propertyref'], 'integer'],
            [['isavailable'], 'boolean'],
            [['name'], 'string', 'max' => 150],
            [['description'], 'string', 'max' => 200]
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
            'propertyref' => 'Property',
            'description' => 'Description',
            'isavailable' => 'Is Available',
        ];
    }
    
    public function assign()
    {
        if ($this->isavailable == FALSE)
            throw new Exception;
        
        $this->isavailable = FALSE;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRentals()
    {
        return $this->hasMany(Rental::className(), ['suiteref' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProperty()
    {
        return $this->hasOne(Property::className(), ['id' => 'propertyref']);
    }
    
    public static function availabilityDropDown()
    {
        static $dropdown;
        if ($dropdown == NULL)
        {
            $dropdown['0'] = 'No';
            $dropdown['1'] = 'Yes';
            
        }
        return $dropdown;
    }
}
