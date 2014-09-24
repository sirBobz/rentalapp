<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "entity".
 *
 * @property string $id
 * @property string $name
 * @property string $phonenumber
 * @property string $entitytype
 * @property string $emailaddress
 * @property string $datecreated
 *
 * @property Login[] $logins
 * @property PropertyOwnerData $propertyOwnerData
 * @property Tenant $tenant
 */
class PropertyOwner extends Entity
{
    public $data;
    
    /*function __construct() {
        $this->data = $this->hasOne(PropertyOwnerData::className(), ['entityref' => 'id']);
    }*/


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
    
    function defaultScope()
    {
        return array('condition' => "entitytype='PropertyOwner'");
    }
    
    /*public function beforeValidate() {
                
        if (parent::beforeValidate())
        {
            $this->entitytype = Entity::ENTITYTYPE_PROPERTYOWNER;
            $this->datecreated = date('Y-m-d H:i:s');
            
            return TRUE;
        }
    }*/
}
