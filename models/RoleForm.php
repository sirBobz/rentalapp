<?php
namespace app\models;

class RoleForm extends \yii\base\Model
{
    public $name;
    public $description;
    
    public function rules()
    {
        return [
          [['name'], 'required']  
        ];
    }
    
    public function attributeLabels() {
        return [
            'name' => 'Name',
            'description' => 'Description'
        ];
    }
}
?>
