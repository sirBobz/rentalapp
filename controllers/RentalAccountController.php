<?php

namespace app\controllers;

use \Yii;
use yii\filters\VerbFilter;

use app\models\LatePaymentRentalLogSearch;

class RentalAccountController extends \yii\web\Controller
{
    private $rc;
    
    public function __construct($id, $module, $config = array()) {
        $this->rc = new \ReflectionClass(get_class());
        parent::__construct($id, $module, $config);
    }

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
        $perm = $this->rc->getMethod($this->action->actionMethod)->getDocComment();
        $perm = preg_replace('/\W/', "", $perm);
        $perm = substr($perm, 10);
        
        $can = Yii::$app->user->can($perm);
        if(!$can)
            throw new \yii\web\NotFoundHttpException('You do no have permission to access the requested page.');
        
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
        $perm = $this->rc->getMethod($this->action->actionMethod)->getDocComment();
        $perm = preg_replace('/\W/', "", $perm);
        $perm = substr($perm, 10);
        
        $can = Yii::$app->user->can($perm);
        if(!$can)
            throw new \yii\web\NotFoundHttpException('You do no have permission to access the requested page.');
        
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
     * @permission listtenantrentalaccounts
     */
    public function actionAccountsForTenant($id)
    {
        $perm = $this->rc->getMethod($this->action->actionMethod)->getDocComment();
        $perm = preg_replace('/\W/', "", $perm);
        $perm = substr($perm, 10);
        
        $can = Yii::$app->user->can($perm);
        if(!$can)
            throw new \yii\web\NotFoundHttpException('You do no have permission to access the requested page.');
        
        $query = \app\models\Rental::find()
                ->select(['rental.id', 'rental.accountnumber', 'rental.datecreated', 'rental.rentalperiod', 
                    'rental.amountperperiod', 'rental.depositamount', 'rental.currentbalance', 
                    'rental.lastpaymentdate', 'rental.latepaymentcharge', 'rental.billingstartdate', 'rental.rentalstatus',
                    'u.name as unitname',
                    'p.name as propertyname', 'p.code as propertycode',
                    'e.name as tenantname',
                    'cre.expirydate as expirydate'])
                ->where(['rental.tenantref' => $id])
                ->innerJoin('unit u', 'unitref = u.id')
                ->innerJoin('property p', 'u.propertyref = p.id')
                ->innerJoin('entity e', 'tenantref = e.id')
                ->leftJoin('currentrentalexpiry cre', 'rental.id = cre.rentalid');
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
        ]);
        
        return $this->render('accounts-for-tenant', [
            'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * @permission viewrentaltxhistory
     */
    public function actionTransactionhistory($id)
    {
        $perm = $this->rc->getMethod($this->action->actionMethod)->getDocComment();
        $perm = preg_replace('/\W/', "", $perm);
        $perm = substr($perm, 10);
        
        $can = Yii::$app->user->can($perm);
        if(!$can)
            throw new \yii\web\NotFoundHttpException('You do no have permission to access the requested page.');
        
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
        $perm = $this->rc->getMethod($this->action->actionMethod)->getDocComment();
        $perm = preg_replace('/\W/', "", $perm);
        $perm = substr($perm, 10);
        
        $can = Yii::$app->user->can($perm);
        if(!$can)
            throw new \yii\web\NotFoundHttpException('You do no have permission to access the requested page.');
        
        return $this->render('search');
    }
    
    public function actionLatePaymentAccounts()
    {
        $perm = $this->rc->getMethod($this->action->actionMethod)->getDocComment();
        $perm = preg_replace('/\W/', "", $perm);
        $perm = substr($perm, 10);
        
        $can = Yii::$app->user->can($perm);
        if(!$can)
            throw new \yii\web\NotFoundHttpException('You do no have permission to access the requested page.');
        
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
    
    public function actionLatePaymentAccountsPdf()
    {
        $data = \app\models\Latepaymentrentallog::find()->select(['year', 'month', 'amountcharged', 
            'latepaymentrentallog.datecreated', 'e.name as tenantname'])
                ->innerJoin('rental r', 'rentalref = r.id')
                ->innerJoin('entity e', 'r.tenantref = e.id')
                ->where([
                    'year' => date('Y'),
                    'month' => (date('m') < 10) ? substr(date('m'), 1) : date('m')
                    ])->all();
        $content = $this->renderPartial('late-payment-accounts-pdf', ['data' => $data]);
        
        $pdf = \Yii::$app->pdf;
        $mpdf = $pdf->api;
        $mpdf->WriteHtml($content);
        echo $mpdf->Output('filename', 'D');
        
    }
    
    /**
     * @permission closerentalaccount
     */
    public function actionClose($id)
    {
        $perm = $this->rc->getMethod($this->action->actionMethod)->getDocComment();
        $perm = preg_replace('/\W/', "", $perm);
        $perm = substr($perm, 10);
        
        $can = Yii::$app->user->can($perm);
        if(!$can)
            throw new \yii\web\NotFoundHttpException('You do no have permission to access the requested page.');
                
        $rentalAccount = \app\models\Rental::find()
                ->where(['id' => $id])
                ->one();
        
        if($rentalAccount->rentalstatus != \app\models\Rental::STATUS_RENTAL_ACTIVE)
            throw new \yii\web\NotFoundHttpException('You cannot close an account that is not active!');
        
        
        //get login so that it can be disabled
        $login = \app\models\Login::find()->where(['entityref' => $id])
                ->one();
        
        $refundedAmount = $rentalAccount->close($login);
        $rentalAccount->save();
        
        return $this->renderAjax('close', [
            'amountRefunded' => $refundedAmount
        ]);
    }
    
    /**
     * @permission approvedepositrefund
     */
    public function actionDepositRefundPendingApproval()
    {
        $perm = $this->rc->getMethod($this->action->actionMethod)->getDocComment();
        $perm = preg_replace('/\W/', "", $perm);
        $perm = substr($perm, 10);
        
        $can = Yii::$app->user->can($perm);
        if(!$can)
            throw new \yii\web\NotFoundHttpException('You do no have permission to access the requested page.');
        
        $query = \app\models\DepositRefund::find()
                ->select(['ae.amount', 'ae.datecreated', 'l.emailaddress AS createdby', 
                    'r.accountnumber', 'e.name'])
                ->innerJoin('accountentry ae', 'depositrefund.accountentryref = ae.id')
                ->innerJoin('login l', 'ae.createdbyref = l.id')
                ->innerJoin('rental r', 'ae.rentalref = r.id')
                ->innerJoin('entity e', 'r.tenantref = e.id')
                ->where(['depositrefund.approvalstatus' => \app\models\DepositRefund::STATUS_PENDING_APPROVAL]);
        
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
        ]);
        
        return $this->render('deposit-refund-pending-approval', [
            'dataProvider' => $dataProvider
        ]);
    }
}
