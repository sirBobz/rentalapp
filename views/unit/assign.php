<?php
use yii\jui\AutoComplete;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\web\JsExpression;

$this->title = "Assign rental unit";
$this->params['breadcrumbs'][] = ['label' => 'Rental unit', 'url' => ['view', 'id' => $model->unitref]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-header">
    <h1><?= Html::encode($this->title) ?></h1>
</div>

<div class="rentalassignment-form">
    <?php $form = ActiveForm::begin([
        'options' => [
            'style' => 'width:400px'
        ]
    ]); ?>
    <div class="form-group">
        <label class="control-label" for="company">Tenant</label>
        <?= AutoComplete::widget([
        'name' => 'country',
        'clientOptions' => [
            'source' => $tenants,
            'minLength' => 2,
            'autoFill' => TRUE,
            'select' => new JsExpression("function(event, ui){
                $('#rental-tenantref').val(ui.item.id)
            }")
        ]
        ]); ?>
    </div>
    
    <?= Html::activeHiddenInput($model, 'tenantref') ?>
    
    <?= Html::activeHiddenInput($model, 'unitref') ?>
    
    <?= $form->field($model, 'rentalperiod')->textInput(['maxlength' => 200]) ?>
    
    <?= $form->field($model, 'amountperperiod')->textInput(['maxlength' => 200]) ?>
    
    <?= $form->field($model, 'depositamount')->textInput(['maxlength' => 200]) ?>
    
    <?= $form->field($model, 'depositrentalperiodpaidfor')->textInput(['maxlength' => 200]) ?>
    
    <?= $form->field($model, 'lastpaymentdate')->textInput(['maxlength' => 200]) ?>
    
    <?= $form->field($model, 'latepaymentcharge')->textInput(['maxlength' => 200]) ?>
    
    <div class="form-group">
        <b>Billing start date</b><br />
    <?= \yii\jui\DatePicker::widget(['model' => $model,
        'attribute' => 'billingstartdate',
        'clientOptions' => [
            'dateFormat' => 'dd-mm-yy'
        ]]) ?>
    </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    
    <?php ActiveForm::end(); ?>
</div>