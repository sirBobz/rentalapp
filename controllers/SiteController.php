<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
 
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        //if user not logged in, navigate to login page
        if(\Yii::$app->user->isGuest)
        {
            return $this->redirect(['/site/login']);
        }
        
        //if user is tenant, navigate to tenant home page
        $userId = \Yii::$app->user->id;
        $role = \Yii::$app->authManager->getRolesByUser($userId);

        if(key_exists('tenant', $role))
        {
            $rentalAccounts = \app\models\Login::find()
                    ->select(['r.id', 'r.tenantref'])
                    ->where(['login.id' => $userId])
                    ->innerJoin('rental r', 'entityref = r.tenantref')
                    ->all();

            //tenant has many accounts
            if(count($rentalAccounts) > 1)
                return $this->redirect(['/rental-account/accounts-for-tenant', 'id' => $rentalAccounts[0]['tenantref']]);
            if(count($rentalAccounts) == 1)
                return $this->redirect(['/rental-account/view', 'id' => $rentalAccounts[0]['id']]);
        }
        if(key_exists('admin', $role))
        {
            return $this->redirect(['unit/uptake']);
        }
        
        //if user is admin navigate to reports page
        return $this->render('index');
    }

    public function actionLogin()
    {
        //user already logged in, go to the home page
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $userId = \Yii::$app->user->id;
            $role = \Yii::$app->authManager->getRolesByUser($userId);
            
            if(key_exists('tenant', $role))
            {
                $rentalAccounts = \app\models\Login::find()
                        ->select(['r.id', 'r.tenantref'])
                        ->where(['login.id' => $userId])
                        ->innerJoin('rental r', 'entityref = r.tenantref')
                        ->all();
                
                //tenant has many accounts
                if(count($rentalAccounts) > 1)
                    return $this->redirect(['/rental-account/accounts-for-tenant', 'id' => $rentalAccounts[0]['tenantref']]);
                if(count($rentalAccounts) == 1)
                    return $this->redirect(['/rental-account/view', 'id' => $rentalAccounts[0]['id']]);
            }
        
            if(key_exists('admin', $role))
            {
                return $this->redirect(['unit/uptake']);
            }
            
            //return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }
    
    public function actionPasswordReset()
    {
        //user already logged in, go to the home page
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new \app\models\PasswordResetForm();
        
        if ($model->load(Yii::$app->request->post()))
        {
            $result = $model->reset();
            
            if($result == TRUE)
            {
                \Yii::$app->session->setFlash('success', 'Please check your email for link to reset your password');
            }
        }
        
        return $this->render('password-reset', [
            'model' => $model
        ]);
    }
    
    public function actionVerifyEmail($hash, $email)
    {
        $result = \Yii::$app->getSecurity()->validateData($hash, $email);
        if($result === FALSE)
            throw new \yii\base\InvalidValueException('System was unable to validate the request');
        
        //update login status to active
        $login = \app\models\Login::find()->where(['emailaddress' => $email])->one();
        $login->activate();
        $login->save();
        
        $model = new LoginForm();
        $model->username = $email;
        $model->password = $email;
        
        $model->login();
        return $this->redirect(['/site/change-password', 'id' => $login->id]);
    }
    
    public function actionVerifyPasswordReset($hash, $email)
    {
        $result = \Yii::$app->getSecurity()->validateData($hash, $email);
        if($result === FALSE)
            throw new \yii\base\InvalidValueException('System was unable to validate the request');
        
        $login = \app\models\Login::find()->where(['emailaddress' => $email])->one();
        
        return $this->redirect(['/site/change-password', 'id' => $login->id]);
    }
    
    public function actionChangePassword($id)
    {
        $model = new \app\models\PasswordChangeForm();
        $model->id = $id;
        
        if($model->load(Yii::$app->request->post()))
        {
            $login = \app\models\Login::find()->where(['id' => $id])->one();
            $login->updatePassword($model->new_password);
            $login->save();
            
            Yii::$app->session->setFlash('success', 'Password changed successfully');
            $this->goHome();
        }
        
        return $this->render('change-password', [
            'model' => $model
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    public function actionAbout()
    {
        return $this->render('about');
    }
}
