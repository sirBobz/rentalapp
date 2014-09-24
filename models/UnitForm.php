<?php
namespace app\models;

use yii\base\Model;

class UnitForm extends Model
{
    public $name;
    public $propertyid;
    public $description;
    public $creation_method;
    public $numSuites;
    
    public function rules() {
        return [
          ['numSuites', 'integer']  
        ];
    }
    
    public function attributeLabels() {
        return [
          'numSuites' => 'Number of suites'  
        ];
    }
}
?>
