<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$form = ActiveForm::begin(['id' => 'changepasswd-form',
        'options' => ['role' => 'form', 'style' => 'width:300px'],]);
?>
<div class="row">
    <?= $form->field($model, 'new_password')->passwordInput() ?>
</div>
<div class="row">
    <?= $form->field($model, 'repeat_password')->passwordInput() ?>
</div>
<div class="form-group">
    <?= Html::submitButton('Change password', ['class' => 'btn btn-primary']) ?>
</div>
<?php ActiveForm::end(); ?>