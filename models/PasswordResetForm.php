<?php
namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class PasswordResetForm extends Model
{
    public $emailaddress;
    
    public function rules()
    {
        return [
            [['emailaddress'], 'email'],
            [['emailaddress'], 'required']
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'emailaddress' => 'Email address'
        ];
    }
    
    public function reset()
    {
        //confirm email exists
        $exists = $this->emailExists($this->emailaddress);
        var_dump($exists);
        \Yii::$app->session->setFlash('error', 'Can\'t find that email. Sorry');
        //die();
        
        //send reset link to requestor's
    }
    
    private function emailExists($email)
    {
        $exists = Login::find()->where(['emailaddress' => $email])->exists();
        return $exists;
    }
}
?>
