<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\PropertyOwner $model
 */

$this->title = 'Create Tenant';
$this->params['breadcrumbs'][] = ['label' => 'Tenants', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tenant-create">

    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
