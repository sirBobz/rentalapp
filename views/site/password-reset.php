<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = "Forgot password";
?>
<div class="page-header">
    <h1><?= $this->title ?></h1>
</div>

<?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => ['class' => 'form-signin', 'role' => 'form', 'style' => 'width:300px'],
    ]); ?>

    <?= $form->field($model, 'emailaddress')->textInput() ?>

<div class="form-group">
    <?= Html::submitButton('Reset password', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
</div>

<?php ActiveForm::end(); ?>