<?php
namespace app\models;

use Yii;
use yii\base\Model;

class PasswordChangeForm extends Model
{
    public $id;
    public $new_password;
    public $repeat_password;

    public function rules()
    {
        return [
            [['new_password', 'old_password'], 'required'],
            [['repeat_password'], 'compare', 'compareAttribute' => 'new_password']
        ];
    }
}
?>
