<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\PropertyOwner $model
 */

$this->title = 'Create Property Owner';
$this->params['breadcrumbs'][] = ['label' => 'Property Owners', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="property-owner-create">

    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
