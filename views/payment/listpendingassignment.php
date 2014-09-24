<?php
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Payments pending actioning';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>
<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'receiptnumber',
            'amount',
            'paymentdate',
            'datecreated',
            'paidinby',
            'paymentphonenumber',
            'paymentreference',
            [
                'attribute' => 'Action',
                'format' => 'raw',
                'value' => function($model){
                    return '<a href="'. Yii::$app->urlManager->createUrl(['payment/assign', 'id' => $model->id]) . '">Assign</a>'.
                            ' | '.
                            '<a href="'. Yii::$app->urlManager->createUrl(['payment/reverse', 'id' => $model->id]) . '">Reverse</a>';
                }
            ]
        ],
    ]); ?>