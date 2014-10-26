<?php

namespace app\controllers;

use Yii;
use app\models\UnitForm;
use app\models\Unit;
use app\models\UnitSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SuiteController implements the CRUD actions for Suite model.
 */
class UnitController extends Controller
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
     * @permission viewunit
     */
    public function actionIndex($propertyid)
    {
        $searchModel = new UnitSearch();
        $params = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($params);
        $property = \app\models\Property::find()
                ->select(['id', 'name', 'genre'])
                ->where(['id' => $params['propertyid']])
                ->one();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'property' => $property
        ]);
    }

    /**
     * @permission viewunit
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * @permission addunit
     */
    public function actionCreate($propertyid)
    {
        $model = new UnitForm;
        $model->propertyid = $propertyid;

        $formData = \Yii::$app->request->post('UnitForm');
        if(isset($formData))
        {
            //manual form
            if($formData['creation_method'] == 1)
            {
                $suite = new Unit;
                $suite->name = $formData['name'];
                $suite->propertyref = $formData['propertyid'];
                $suite->description = $formData['description'];
                $suite->isavailable = TRUE;
                
                $suite->save();
            }
            //auto
            if($formData['creation_method'] == 2)
            {
                //get max suite id for the property and continue from there
                $command = Yii::$app->db->createCommand("SELECT COUNT(1) FROM unit u WHERE u.propertyref=:propertyid");
                $command->bindValue(":propertyid", $formData['propertyid']);
                $count = $command->queryScalar();
        
                for($i = 0; $i < $formData['numSuites']; $i++)
                {
                    $suite = new Unit;
                    $suite->name = (string)($count + $i + 1);
                    $suite->propertyref = $formData['propertyid'];
                    $suite->isavailable = FALSE;
                    $suite->save();
                }
            }
            
            return $this->redirect(['index', 'propertyid' => $model->propertyid]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * @permission editunit
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
     * @permission deleteunit
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Suite model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Suite the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Unit::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
     * @permission assignunit
     */
    public function actionAssign()
    {
        $unitid = Yii::$app->request->queryParams['unitid'];
        $unit = Unit::find()->where(['id' => $unitid, 'isavailable' => TRUE])->with('property')->one();
        
        if($unit == NULL)
            throw new NotFoundHttpException('The unit has already been assigned!');
        
        $model = new \app\models\Rental;
        $model->lastpaymentdate = $unit->property->lastpaymentdate;
        $model->latepaymentcharge = $unit->property->latepaymentcharge;
        $model->rentalperiod = $unit->property->rentalperiod;
        $model->unitref = $unitid;
        $model->accountnumber = $unit->property->code . "-" . $unitid;
                
        $tenants = \app\models\Tenant::find()->where(['entitytype' => 'Tenant'])->
        select(['name as value', 'id'])->asArray()->all();
        
        if ($model->load(Yii::$app->request->post()))
        {
            $isDataSaved = FALSE;
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $isDataSaved = $model->save();
                $unit->assign();
                $unit->save();
                $model->debitDepositOnRentalCreation();
                $transaction->commit();
            } catch (yii\base\Exception $e)
            {
                $transaction->rollBack();
            }
            
            if ($isDataSaved)
                return $this->redirect(['rental', 'id' => $model->id]);
        }
        
        return $this->render('assign', [
            'model' => $model,
            'tenants' => $tenants
        ]);
    }
    
    public function actionRental($id)
    {
        $model = \app\models\Rental::find()->select(['rental.datecreated', 'rental.rentalperiod', 
            'rental.amountperperiod', 'rental.depositamount', 'rental.currentbalance', 'rental.depositrentalperiodpaidfor',
            'rental.lastpaymentdate', 'rental.latepaymentcharge', 'rental.accountnumber', 'u.name as unitname', 
            'p.name as propertyname', 'p.code as propertycode', 'e.name as tenantname'])->
                where(['rental.id' => $id])->
                innerJoin('unit u', 'unitref = u.id')->
                innerJoin('property p', 'u.propertyref = p.id')->
                innerJoin('entity e', 'tenantref = e.id')->
                one();
        
        return $this->render('rental', [
            'model' => $model
        ]);
    }
    
    public function actionUnoccupied()
    {
        $searchModel = new UnitSearch();
        $params = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($params);
        
        //pie data, unoccupied per propertyowner
        
        return $this->render('unoccupied', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionUptake()
    {
        $uptakePerGenre = \Yii::$app->db->createCommand("SELECT p.`genre`, count(u.`id`) AS unitsrented FROM `unit` u
	INNER JOIN `property` p ON u.`propertyref` = p.`id`
            INNER JOIN `entity` e ON p.`propertyownerref` = e.`id`
            WHERE u.`isavailable` = 0
            GROUP BY p.`genre`")->queryAll();
        
        $uptakePerType = \Yii::$app->db->createCommand("SELECT p.`type`, count(u.`id`) AS unitsrented FROM `unit` u
	INNER JOIN `property` p ON u.`propertyref` = p.`id`
            INNER JOIN `entity` e ON p.`propertyownerref` = e.`id`
            WHERE u.`isavailable` = 0
            GROUP BY p.`type`")->queryAll();
        
        return $this->render('uptake', [
            'uptakePerGenre' => $uptakePerGenre,
            'uptakePerType' => $uptakePerType
        ]);
    }
}
