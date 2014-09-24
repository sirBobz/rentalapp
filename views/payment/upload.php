<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>
<div class="payment-upload">
    <h1>Upload payment</h1>
    <?php $form = ActiveForm::begin([
        'id' => 'upload-form',
        'options' => ['class' => 'form-horizontal', 'enctype' => 'multipart/form-data'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-7\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]); ?>
    
    <?= $form->field($model, 'file')->fileInput() ?>
    
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <?= Html::submitButton('Go!', ['class' => 'btn btn-primary', 'name' => 'payment-upload-button']) ?>
        </div>
    </div>
    
    <?php ActiveForm::end(); ?>
</div>