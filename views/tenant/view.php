<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var app\models\Tenant $model
 */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Tenants', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tenant-view">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    
<!--    <p>
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
                'phonenumber',
                'emailaddress:email',
                'datecreated'
            ],
        ]) ?>
        </div>
        <div class="col-md-3">
            <ul>
                <li><a href="<?= Yii::$app->urlManager-> createUrl(['tenant/create']) ?>">Create tenant</a> </li>
                <li><a href="<?= Yii::$app->urlManager-> createUrl(['tenant/index']) ?>">List tenants</a> </li>
            </ul>
        </div>
    </div>

</div>
