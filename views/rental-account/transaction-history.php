<?php
use yii\grid\GridView;
?>

<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'datecreated',
            'credit',
            'debit',
            'accounttype',
            'description'
        ],
    ]); ?>
