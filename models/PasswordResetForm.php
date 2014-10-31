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
            \Yii::$app->session->setFlash('error', 'Can\'t find that email. Sorry');
        
        //send reset link to requestor's
        Yii::$app->mailer->compose('site/requestPasswordReset', 
            [
                'emailaddress' => $model->emailaddress,
                'id' => \Yii::$app->getSecurity()->generatePasswordHash($tenantLogin->id)
            ])
        ->setTo($this->emailaddress)
        ->setFrom('daniel@lukoba.com')
        ->setSubject("Reset your password for '$tenantLogin->emailaddress'")
        ->send();
    }
    
    private function emailExists($email)
    {
        $exists = Login::find()->where(['emailaddress' => $email])->exists();
        return $exists;
    }
}
?>
