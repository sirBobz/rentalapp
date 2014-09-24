<?php
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Rental Accounts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-header">
    <h1><?= Html::encode($this->title) ?></h1>
</div>

<?php 
\yii\widgets\Pjax::begin(); 
?>

<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'tenantname',
                'label' => 'Tenant'
            ],
            [
                'attribute' => 'billingstartdate',
            ],
            [
                'attribute' => 'amountperperiod',
                'label' => 'Amount/Period'
            ],
            [
                'attribute' => 'depositamount',
                'label' => 'Deposit'
            ],
            [
                'attribute' => 'currentbalance',
                'label' => 'A/c balance'
            ],
            'accountnumber',
            [
                'attribute' => 'rentalstatus',
                'label' => 'Status',
                'filter' => \app\models\Rental::rentalStatusDropDown(),
                'value' => function($model, $index, $dataColumn){
                    return \app\models\Rental::rentalStatusDropDown()[$model->rentalstatus];
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}'  
            ],
            /*[
                'attribute' => 'Action',
                'format' => 'raw',
                'value' => function($model){
                    return '<a href="'. Yii::$app->urlManager->createUrl(['rental-account/view', 'id' => $model->id]) . '">View</a>';
                }
            ]*/
        ],
    ]); ?>
<?php 
\yii\widgets\Pjax::end(); 
?>