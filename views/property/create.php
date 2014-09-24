<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Property $model
 */

$this->title = 'Create Property for '. $owner->name;
$this->params['breadcrumbs'][] = ['label' => 'Properties', 'url' => ['index', 'ownerid' => $model->propertyownerref]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="property-create">

    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
