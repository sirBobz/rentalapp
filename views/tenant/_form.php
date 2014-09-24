<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\Tenant $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="tenant-form">

    <?php $form = ActiveForm::begin(['options' => [
        'style' => 'width:400px'
    ]]); ?>
    
    <?= $form->field($model, 'name')->textInput(['maxlength' => 200]) ?>

    <?= $form->field($model, 'phonenumber')->textInput(['maxlength' => 15]) ?>

    <?= $form->field($model, 'emailaddress')->textInput(['maxlength' => 45]) ?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
