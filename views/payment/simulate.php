<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\models\SimulateForm $model
 */
$this->title = 'Simulate incoming IPN payment';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payment-simulate">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    
    <?php $form = ActiveForm::begin([
        'id' => 'simulate-form',
        'options' => [/*'class' => 'form-horizontal'*/'style' => 'width:400px'],
        /*'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-7\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],*/
    ]); ?>

    <?= $form->field($model, 'amount') ?>

    <?= $form->field($model, 'paymentphone') ?>

    <?= $form->field($model, 'paidinby') ?>

    <div class="form-group">
        <?= Html::submitButton('Go!', ['class' => 'btn btn-primary', 'name' => 'payment-simulate-button']) ?>
        
    </div>

    <?php ActiveForm::end(); ?>

</div>
