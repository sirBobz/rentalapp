<?php

namespace app\controllers;

use \Yii;
use yii\filters\VerbFilter;

use app\models\LatePaymentRentalLogSearch;

class RentalAccountController extends \yii\web\Controller
{
    public function behaviors() {
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
     * @permission viewrentalaccount
     */
    public function actionIndex()
    {
        $searchModel = new \app\models\RentalAccountSearch();
        $params = \Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($params);
        /*$query = \app\models\Rental::find();
        
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
        ]);*/
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @permission viewrentalaccount
     */
    public function actionView($id)
    {
        $rental = \app\models\Rental::find()
                ->select(['rental.id', 'rental.accountnumber', 'rental.datecreated', 'rental.rentalperiod', 
                    'rental.amountperperiod', 'rental.depositamount', 'rental.currentbalance', 
                    'rental.lastpaymentdate', 'rental.latepaymentcharge', 'rental.billingstartdate', 'rental.rentalstatus',
                    'u.name as unitname',
                    'p.name as propertyname', 'p.code as propertycode',
                    'e.name as tenantname',
                    'cre.expirydate as expirydate'])
                ->where(['rental.id' => $id])
                ->innerJoin('unit u', 'unitref = u.id')
                ->innerJoin('property p', 'u.propertyref = p.id')
                ->innerJoin('entity e', 'tenantref = e.id')
                ->leftJoin('currentrentalexpiry cre', 'rental.id = cre.rentalid')
                ->one();
        
        return $this->render('view', [
            'model' => $rental
        ]);
    }

    /**
     * @permission viewrentaltxhistory
     */
    public function actionTransactionhistory($id)
    {
        $query = (new \yii\db\Query())
                ->from('transactionhistory')
                ->where(['rentalref' => $id])
                ->orderBy(['id' => SORT_DESC]);
        
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
        ]);
                
        return $this->renderAjax('transaction-history', [
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * @permission searchrental
     */
    public function actionSearch()
    {
        return $this->render('search');
    }
    
    public function actionLatePaymentAccounts()
    {
        $searchModel = new LatePaymentRentalLogSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        $pieChartData = \Yii::$app->db->createCommand("SELECT e.name, COUNT(l.`id`) AS lateaccounts FROM `latepaymentrentallog` l
            INNER JOIN `rental` r ON l.`rentalref` = r.`id`
            INNER JOIN `unit` u ON r.`unitref` = u.`id`
            INNER JOIN `property` p ON u.`propertyref` = p.`id`
            INNER JOIN `entity` e ON p.`propertyownerref` = e.`id`
            WHERE l.`year` = YEAR( current_date( ) )
            AND l.`month` = MONTH( current_date( ) ) 
            GROUP BY e.`id`
            ORDER BY lateaccounts DESC
            LIMIT 0, 5")->queryAll();
        
        return $this->render('late-payment-accounts', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'pieChartData' => $pieChartData
        ]);
    }
}
