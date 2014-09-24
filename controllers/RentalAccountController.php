<?php

namespace app\controllers;

class RentalAccountController extends \yii\web\Controller
{
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
}
