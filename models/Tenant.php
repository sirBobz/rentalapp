<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tenant".
 *
 * @property string $id
 * @property string $name
 * @property string $phonenumber
 * @property string $entitytype
 * @property string $emailaddress
 * @property string $datecreated
 *
 * @property TenantData $tenantData
 */
class Tenant extends Entity
{
    public $data;
    
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
    public function getPropertyOwner()
    {
        return $this->hasOne(PropertyOwner::className(), ['entityref' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTenantData()
    {
        return $this->hasOne(TenantData::className(), ['entityref' => 'id']);
    }

    function defaultScope()
    {
        return array('condition' => "entitytype='Tenant'");
    }
}
