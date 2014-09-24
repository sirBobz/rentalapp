<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var app\models\PropertyOwner $model
 */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Property Owners', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="property-owner-view">

    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    
<!--    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        
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
                <li><a href="<?= Yii::$app->urlManager-> createUrl(['property/create', 'ownerid' => $model->id]) ?>">Create property</a> </li>
                <li><a href="<?= Yii::$app->urlManager-> createUrl(['property/index', 'ownerid' => $model->id]) ?>">List properties</a> </li>
            </ul>
        </div>
    </div>

</div>
