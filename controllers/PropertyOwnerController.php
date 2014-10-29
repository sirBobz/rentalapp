<?php

namespace app\controllers;

use Yii;
use app\models\PropertyOwner;
use app\models\PropertyOwnerSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PropertyOwnerController implements the CRUD actions for PropertyOwner model.
 */
class PropertyOwnerController extends Controller
{
    private $rc;
    
    public function __construct($id, $module, $config = array()) {
        $this->rc = new \ReflectionClass(get_class());
        parent::__construct($id, $module, $config);
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
    * @permission viewpropertyowner
    */
    public function actionIndex()
    {
        $perm = $this->rc->getMethod($this->action->actionMethod)->getDocComment();
        $perm = preg_replace('/\W/', "", $perm);
        $perm = substr($perm, 10);
        
        $can = Yii::$app->user->can($perm);
        if(!$can)
            throw new \yii\web\NotFoundHttpException('You do no have permission to access the requested page.');
        
        $searchModel = new PropertyOwnerSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * @permission viewpropertyowner
     */
    public function actionView($id)
    {
        $perm = $this->rc->getMethod($this->action->actionMethod)->getDocComment();
        $perm = preg_replace('/\W/', "", $perm);
        $perm = substr($perm, 10);
        
        $can = Yii::$app->user->can($perm);
        if(!$can)
            throw new \yii\web\NotFoundHttpException('You do no have permission to access the requested page.');
        
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * @permission addpropertyowner
     */
    public function actionCreate()
    {
        $perm = $this->rc->getMethod($this->action->actionMethod)->getDocComment();
        $perm = preg_replace('/\W/', "", $perm);
        $perm = substr($perm, 10);
        
        $can = Yii::$app->user->can($perm);
        if(!$can)
            throw new \yii\web\NotFoundHttpException('You do no have permission to access the requested page.');
        
        $model = new PropertyOwner;
        $model->data = new \app\models\PropertyOwnerData;

        if ($model->load(Yii::$app->request->post())) {
            $model->data->attributes = $_POST['PropertyOwnerData'];
            
            if($model->save())
            {
                $model->data->entityref = $model->id;
                if($model->data->save())
                {
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * @permission editpropertyowner
     */
    public function actionUpdate($id)
    {
        $perm = $this->rc->getMethod($this->action->actionMethod)->getDocComment();
        $perm = preg_replace('/\W/', "", $perm);
        $perm = substr($perm, 10);
        
        $can = Yii::$app->user->can($perm);
        if(!$can)
            throw new \yii\web\NotFoundHttpException('You do no have permission to access the requested page.');
        
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * @permission deletepropertyowner
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the PropertyOwner model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return PropertyOwner the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PropertyOwner::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
