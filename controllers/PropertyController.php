<?php

namespace app\controllers;

use Yii;
use app\models\Property;
use app\models\PropertySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PropertyController implements the CRUD actions for Property model.
 */
class PropertyController extends Controller
{
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
     * @permission viewproperty
     */
    public function actionIndex()
    {
        $searchModel = new PropertySearch;
        $params = Yii::$app->request->getQueryParams();
        $dataProvider = $searchModel->search($params);
        $owner = \app\models\PropertyOwner::find()
                ->where(['id' => $params['ownerid']])
                ->select('name')
                ->one();
        
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'ownerid' => $params['ownerid'],
            'owner' => $owner
        ]);
    }

    /**
     * @permission viewproperty
     */
    public function actionView($id)
    {
        $model = Property::find()->
                select(['property.id', 'property.name', 'description', 'property.datecreated', 'code', 
                    'type', 'genre', 'lastpaymentdate', 'latepaymentcharge', 'rentalperiod', 'propertyownerref',
                    'location.name AS locationname', 'login.emailaddress', 'e.name AS propertyowner'])->
                where(['property.id' => $id])->
                innerJoin('location', 'property.locationref = location.id')->
                innerJoin('login', 'property.createdbyref = login.id')->
                innerJoin('entity AS e', 'property.propertyownerref = e.id')->
                one();
        $model->type = $model->propertyTypeDropDown()[$model->type];
        $model->genre = $model->propertygenreDropDown()[$model->genre];
        
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * @permission addproperty
     */
    public function actionCreate()
    {
        $ownerid = Yii::$app->request->getQueryParams()['ownerid'];
        
        $model = new Property;
        $model->propertyownerref = $ownerid;
        $command = Yii::$app->db->createCommand("SELECT COUNT(1) FROM property p WHERE p.propertyownerref=:ownerid");
        $command->bindValue(":ownerid", $ownerid);
        $count = $command->queryScalar();
        $model->code = "EST" . $ownerid . "/" . (intval($count) + 1);
        $propertyOwner = \app\models\PropertyOwner::find()
                ->where(['id' => $ownerid])
                ->select('name')
                ->one();
        
        $isDataSaved = FALSE;
        if ($model->load(Yii::$app->request->post())/* && $model->save()*/) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $isDataSaved = $model->save();
                $transaction->commit();
            } catch (yii\base\Exception $e)
            {
                $transaction->rollBack();
            }
            
            if ($isDataSaved)
                return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'owner' => $propertyOwner
            ]);
        }
    }

    /**
     * @permission editproperty
     */
    public function actionUpdate($id)
    {
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
     * @permission deleteproperty
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Property model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Property the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Property::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
