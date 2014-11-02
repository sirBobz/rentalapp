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
        if(!$exists)
        {
            \Yii::$app->session->setFlash('error', 'Can\'t find that email. Sorry');
            
            return false;
        }
        
        //send reset link to requestor's
        $result = Yii::$app->mailer->compose('site/requestPasswordReset', 
            [
                'id' => \Yii::$app->getSecurity()->generatePasswordHash($this->emailaddress)
            ])
        ->setTo($this->emailaddress)
        ->setFrom('daniel@lukoba.com')
        ->setSubject("Reset your password for '$this->emailaddress'")
        ->send();
        
        return $result;
    }
    
    private function emailExists($email)
    {
        $exists = Login::find()->where(['emailaddress' => $email])->exists();
        return $exists;
    }
}
?>
