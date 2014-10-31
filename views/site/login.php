<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\models\LoginForm $model
 */
$this->title = 'Please sign in';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h2 class="form-signin-heading"><?= Html::encode($this->title) ?></h2>

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => ['class' => 'form-signin', 'role' => 'form', 'style' => 'width:300px'],
        /*'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],*/
    ]); ?>

    <?= $form->field($model, 'username')->textInput() ?>

    <?= $form->field($model, 'password')->passwordInput() ?>
    
    <div class="form-group">
        <?= Html::a('I forgot my password', ['password-reset']) ?>
    </div>
    
    <div class="form-group">
        
            <?= Html::submitButton('Sign in', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
        
    </div>

    <?php ActiveForm::end(); ?>

</div>
