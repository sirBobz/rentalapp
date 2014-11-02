<?php
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Deposit refunds pending approval';
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
        'columns' => [
            [
                'attribute' => 'datecreated'
            ],
            [
                'attribute' => 'createdby',
            ],
            [
                'attribute' => 'amount'
            ],
            [
                'attribute' => 'accountnumber'
            ],
            [
                'attribute' => 'name'
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}'  
            ],
            
        ],
    ]); ?>
<?php 
\yii\widgets\Pjax::end(); 
?>