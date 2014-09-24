<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var Unit $model
 */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Units', 'url' => ['index', 'propertyid' => $model->propertyref]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="suite-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <!--<p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>-->

    <div class="row">
        <div class="col-md-9">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'name',
                    'propertyref',
                    'description',
                    'isavailable:boolean',
                ],
            ]) ?>
        </div>
        
        <div class="col-md-3">
            <?php
            if($model->isavailable):
                ?>
            <ul>
                <li><a href="<?= Yii::$app->urlManager-> createUrl(['unit/assign', 'unitid' => $model->id]) ?>">Assign to tenant</a> </li>
            </ul>
            <?php
                endif;
                ?>
        </div>
    </div>
    
</div>
