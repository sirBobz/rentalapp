<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\PropertySearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="property-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'description') ?>

    <?= $form->field($model, 'datecreated') ?>

    <?= $form->field($model, 'createdbyref') ?>

    <?php // echo $form->field($model, 'datedestroyed') ?>

    <?php // echo $form->field($model, 'destroyedbyref') ?>

    <?php // echo $form->field($model, 'propertyownerref') ?>

    <?php // echo $form->field($model, 'code') ?>

    <?php // echo $form->field($model, 'locationref') ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'genre') ?>

    <?php // echo $form->field($model, 'lastpaymentdate') ?>

    <?php // echo $form->field($model, 'latepaymentcharge') ?>

    <?php // echo $form->field($model, 'rentalperiod') ?>

    <?php // echo $form->field($model, 'lat') ?>

    <?php // echo $form->field($model, 'long') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
