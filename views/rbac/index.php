<?php
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'List users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-header">
    <h1><?= Html::encode($this->title) ?></h1>
</div>

<?php yii\widgets\Pjax::begin(); ?>
<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'datecreated',
            'emailaddress',
            [
                'attribute' => 'status',
                'label' => 'Status',
                'filter' => \app\models\Login::loginStatusDropDown(),
                'value' => function($model, $index, $dataColumn){
                    return \app\models\Login::loginStatusDropDown()[$model->status];
                }
            ],
            [
                'attribute' => 'Action',
                'format' => 'raw',
                'value' => function($model){
                    return '<a href="'. Yii::$app->urlManager->createUrl(['rbac/role-to-user', 'id' => $model->id]) . '">Roles</a>';
                }
            ]
        ],
    ]); ?>
<?php yii\widgets\Pjax::end(); ?>