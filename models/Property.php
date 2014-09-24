<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "property".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $datecreated
 * @property integer $createdbyref
 * @property string $datedestroyed
 * @property integer $destroyedbyref
 * @property string $propertyownerref
 * @property string $code
 * @property integer $locationref
 * @property integer $type
 * @property integer $genre
 * @property integer $lastpaymentdate
 * @property string $latepaymentcharge
 * @property integer $rentalperiod
 * @property string $lat
 * @property string $long
 *
 * @property Location $locationref0
 * @property Propertyowner $propertyownerref0
 * @property Unit[] $units
 * @property Viewableproperty[] $viewableproperties
 */
class Property extends \yii\db\ActiveRecord
{
    const PROPERTYTYPE_COMMERCIAL = 1;
    const PROPERTYTYPE_RESIDENTIAL = 2;
    const PROPERTYTYPE_BOTH = 3;
    
    const PROPERTYGENRE_BUNGALOW = 1;
    const PROPERTYGENRE_FLATS = 2;
    const PROPERTYGENRE_HOSTELS = 3;
    const PROPERTYGENRE_OFFICESPACE = 4;
    
    public $locationname;
    public $emailaddress;
    public $propertyowner;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'property';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'datecreated', 'createdbyref', 'propertyownerref', 'code', 'locationref', 'type', 'genre', 'lastpaymentdate', 'latepaymentcharge'], 'required'],
            [['datecreated', 'datedestroyed'], 'safe'],
            [['createdbyref', 'destroyedbyref', 'propertyownerref', 'locationref', 'type', 'genre', 'lastpaymentdate', 'rentalperiod'], 'integer'],
            [['latepaymentcharge', 'lat', 'long'], 'number'],
            [['name'], 'string', 'max' => 100],
            [['description'], 'string', 'max' => 200],
            [['code'], 'string', 'max' => 20],
            [['code'], 'unique']
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
            'description' => 'Description',
            'datecreated' => 'Datecreated',
            'createdbyref' => 'Created By',
            /*'datedestroyed' => 'Datedestroyed',
            'destroyedbyref' => 'Destroyedbyref',*/
            'propertyownerref' => 'Property Owner',
            'code' => 'Property Code',
            'locationref' => 'Location',
            'type' => 'Type',
            'genre' => 'Genre',
            'lastpaymentdate' => 'Last payment date',
            'latepaymentcharge' => 'Late payment charge (%)',
            'rentalperiod' => 'Rental billing period (months)',
            'lat' => 'Latitude',
            'long' => 'Longitude',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocationref0()
    {
        return $this->hasOne(Location::className(), ['id' => 'locationref']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPropertyownerref0()
    {
        return $this->hasOne(Propertyowner::className(), ['entityref' => 'propertyownerref']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnits()
    {
        return $this->hasMany(Unit::className(), ['propertyref' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getViewableproperties()
    {
        return $this->hasMany(Viewableproperty::className(), ['establishmentref' => 'id']);
    }
    
    public function beforeValidate() {
                
        if (parent::beforeValidate())
        {
            if ($this->isNewRecord)
            {
                $this->datecreated = date('Y-m-d H:i:s');
                $this->createdbyref = Yii::$app->user->id;
                
            }
        }
        return TRUE;
    }
    
    public static function propertyTypeDropDown()
    {
        static $dropdown;
        if ($dropdown == NULL)
        {
            $reflection = new \ReflectionClass(get_called_class());
            $constants = $reflection->getConstants();
            
            foreach ($constants as $name => $value) {
                if(strpos($name, "PROPERTYTYPE_") === 0)
                {
                    $prettyName = str_replace("PROPERTYTYPE_", "", $name);
                    $prettyName = \yii\helpers\Inflector::humanize(strtolower($prettyName));
                    $dropdown[$value] = $prettyName;
                }
            }
        }
        return $dropdown;
    }
    
    public static function propertyGenreDropDown()
    {
        static $dropdown;
        if ($dropdown == NULL)
        {
            $reflection = new \ReflectionClass(get_called_class());
            $constants = $reflection->getConstants();
            
            foreach ($constants as $name => $value) {
                if(strpos($name, "PROPERTYGENRE_") === 0)
                {
                    $prettyName = str_replace("PROPERTYGENRE_", "", $name);
                    $prettyName = \yii\helpers\Inflector::humanize(strtolower($prettyName));
                    $dropdown[$value] = $prettyName;
                }
            }
        }
        return $dropdown;
    }
    
    public static function forDropDown()
    {
        static $dropdown;
        if ($dropdown == NULL)
        {
            $properties = static::find()->all();
            
            foreach ($properties as $property) {
                $dropdown[$property->id] = $property->name;
            }
        }
        return $dropdown;
    }
}
