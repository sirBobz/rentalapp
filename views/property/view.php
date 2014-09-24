<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var app\models\Property $model
 */

$this->title = $model->name . " " . $model->genre;
$this->params['breadcrumbs'][] = ['label' => 'Properties', 'url' => ['index', 'ownerid' => $model->propertyownerref]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="property-view">

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
                'description',
                'propertyowner:text:Property Owner',
                'code',
                'locationname:text:Location',
                'type',
                'lastpaymentdate',
                'latepaymentcharge',
                'rentalperiod',
                'emailaddress:text:Created By',
            ],
        ]) ?>
        </div>
        <div class="col-md-3">
            <ul>
                <li><a href="<?= Yii::$app->urlManager-> createUrl(['unit/create', 'propertyid' => $model->id]) ?>">Create unit(s)</a> </li>
                <li><a href="<?= Yii::$app->urlManager-> createUrl(['unit/index', 'propertyid' => $model->id]) ?>">List units</a> </li>
            </ul>
        </div>
    </div>

</div>
