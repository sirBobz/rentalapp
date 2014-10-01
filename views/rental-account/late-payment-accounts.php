<?php
use yii\grid\GridView;
use miloschuman\highcharts\Highcharts;

$this->title = "Late payment accounts for this month";
?>
<div class="page-header">
    <h1><?= $this->title; ?></h1>
</div>
Distribution/owner

<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            'year',
            'month',
            'rentalref',
            'datecreated',
            'amountcharged'
        ],
    ]); ?>