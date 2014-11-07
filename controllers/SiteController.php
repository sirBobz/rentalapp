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
            //$pass = \yii\helpers\Security::generatePasswordHash($model->password);
            //print_r($pass);
            //die();
        
            return $this->goBack();
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
        return $this->actionChangePassword($login->id);
    }
    
    public function actionChangePassword($id)
    {
        print_r($id);
        die();
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
