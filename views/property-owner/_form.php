<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\PropertyOwner $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="property-owner-form">

    <?php $form = ActiveForm::begin([
        'options' => ['style' => 'width:400px']
    ]); ?>
    
    <?= $form->field($model, 'name')->textInput(['maxlength' => 200]) ?>

    <?= $form->field($model, 'phonenumber')->textInput(['maxlength' => 15]) ?>

    <?= $form->field($model, 'emailaddress')->textInput(['maxlength' => 45]) ?>
    
    <?= $form->field($model->data, 'krapin')->textInput(['maxlength' => 20]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
