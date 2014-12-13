<?php
use yii\grid\GridView;

$this->title = "Rental accounts for " . Yii::$app->user->identity->emailaddress;
?>
<div class="page-header">
    <h1><?= $this->title; ?></h1>
</div>

<?php yii\widgets\Pjax::begin(); ?>
<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            'accountnumber',
            [
                'attribute' => 'rentalperiod',
                'label' => 'rental period (months)'
            ],
            'depositamount',
            'datecreated',
            'amountperperiod',
            'currentbalance',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}'  
            ]
        ],
    ]); ?>
<?php yii\widgets\Pjax::end(); ?>