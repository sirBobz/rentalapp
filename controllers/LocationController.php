<?php

namespace app\controllers;

use Yii;
use app\models\Location;
use app\models\LocationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * LocationController implements the CRUD actions for Location model.
 */
class LocationController extends Controller
{
    private $rc;
    
    public function __construct($id, $module, $config = array()) {
        $this->rc = new \ReflectionClass(get_class());
        parent::__construct($id, $module, $config);
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @permission viewlocation
     */
    public function actionIndex()
    {
        $perm = $this->rc->getMethod($this->action->actionMethod)->getDocComment();
        $perm = preg_replace('/\W/', "", $perm);
        $perm = substr($perm, 10);
        
        $can = Yii::$app->user->can($perm);
        if(!$can)
            throw new \yii\web\NotFoundHttpException('You do no have permission to access the requested page.');
        
        $searchModel = new LocationSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * @permission viewlocation
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
     * @permission addlocation
     */
    public function actionCreate()
    {
        $perm = $this->rc->getMethod($this->action->actionMethod)->getDocComment();
        $perm = preg_replace('/\W/', "", $perm);
        $perm = substr($perm, 10);
        
        $can = Yii::$app->user->can($perm);
        if(!$can)
            throw new \yii\web\NotFoundHttpException('You do no have permission to access the requested page.');
        
        $model = new Location;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * @permission editlocation
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
     * @permission deletelocation
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Location model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Location the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Location::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
