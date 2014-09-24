<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "entity".
 *
 * @property string $id
 * @property string $name
 * @property string $phonenumber
 * @property integer $entitytype
 * @property string $emailaddress
 * @property string $datecreated
 *
 * @property Login[] $logins
 * @property Propertyowner $propertyowner
 * @property Tenant $tenant
 */
class Entity extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'entity';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'phonenumber', 'entitytype', 'emailaddress', 'datecreated'], 'required'],
            //[['entitytype'], 'integer'],
            [['datecreated'], 'safe'],
            [['name'], 'string', 'max' => 200],
            [['phonenumber'], 'string', 'max' => 15],
            [['emailaddress'], 'string', 'max' => 45],
            [['emailaddress'], 'unique']
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
            'phonenumber' => 'Phonenumber',
            'entitytype' => 'Entitytype',
            'emailaddress' => 'Emailaddress',
            'datecreated' => 'Datecreated',
        ];
    }

    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLogins()
    {
        return $this->hasMany(Login::className(), ['entityref' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPropertyOwnerData()
    {
        return $this->hasOne(PropertyOwnerData::className(), ['entityref' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTenant()
    {
        return $this->hasOne(Tenant::className(), ['entityref' => 'id']);
    }
    
    public static function instantiate($attributes) {
        //$class = $attributes['entitytype'];
        $cls = get_called_class();
        $model = new $cls;
        
        return $model;
    }
    
    public function beforeValidate() {
        if (parent::beforeValidate())
        {
            if($this->isNewRecord)
            {
                $cls = get_class($this);
                $this->entitytype = substr($cls, strrpos($cls, '\\') + 1);
                $this->datecreated = date('Y-m-d H:i:s');
            }
            return TRUE;
        }
    }


    /*public function beforeSave($insert) {
        if($this->isNewRecord){
            $this->entitytype = get_class($this);
        }
        return parent::beforeSave($insert);
    }*/
}
