<?php
use yii\grid\GridView;
use miloschuman\highcharts\Highcharts;
use yii\web\JsExpression;

$this->title = "Late payment accounts for this month";
?>
<div class="page-header">
    <h1><?= $this->title; ?></h1>
</div>

<?php
$data = array();
foreach($pieChartData as $item)
{
    $arrItem = array();
    $arrItem['name'] = $item['name'];
    $arrItem['y'] = (int)$item['lateaccounts'];
    $data[] = $arrItem;
}

echo Highcharts::widget([
    'scripts' => [
        'themes/grid-light',
    ],
    'options' => [
        'title' => [
            'text' => 'Distribution per owner',
        ],
        
        'series' => [
            [
                'type' => 'pie',
                'name' => '# late customers',
                'data' => $data,
                //'center' => [800, 100],
                'size' => 150,
                'showInLegend' => FALSE,
                'dataLabels' => [
                    'enabled' => FALSE,
                ],
            ],
        ],
    ]
]);
?>
<?php 
echo yii\helpers\Html::a('<i class="fa glyphicon glyphicon-hand-up"></i> Export to pdf', 
    ['/rental-account/late-payment-accounts-pdf'], 
    [
        'class' => 'btn btn-danger',
        'target' => '_blank',
        'data-toggle' => 'tooltip'
    ]);
yii\widgets\Pjax::begin(); ?>
<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            'year',
            [
                'attribute' => 'month',
                'label' => 'month',
                'value' => function($model, $index, $dataColumn){
                    return date('F', mktime(0, 0, 0, $model->month, 10));
                }
            ],
            [
                'attribute' => 'tenantname',
                'label' => 'Tenant'
            ],
            'datecreated',
            'amountcharged'
        ],
    ]); ?>
<?php yii\widgets\Pjax::end(); ?>