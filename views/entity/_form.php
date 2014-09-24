<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\Entity $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="entity-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 200]) ?>

    <?= $form->field($model, 'phonenumber')->textInput(['maxlength' => 15]) ?>

    <?= $form->field($model, 'entitytype') ?>

    <?= $form->field($model, 'emailaddress')->textInput(['maxlength' => 45]) ?>

    <?= $form->field($model, 'datecreated')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
