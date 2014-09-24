<?php
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'List System roles';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-header">
    <h1>System roles <small>for RBAC</small></h1>
</div>

<p>
    <?= Html::a('Create Role', ['add-role'], ['class' => 'btn btn-success']) ?>
</p>

<?= GridView::widget([
        'dataProvider' => $roles,
        'columns' => [
            'name',
            'description',
            [
                'attribute' => 'Action',
                'format' => 'raw',
                'value' => function($model){
                    return '<a href="'. Yii::$app->urlManager->createUrl(['rbac/role-permissions', 'role' => $model->name]) . '">Permissions</a>';
                }
            ]
        ],
    ]); ?>
