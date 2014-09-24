<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use yii\jui\AutoComplete;
use yii\web\JsExpression;

$this->title = 'Assign an unmatched payment';
$this->params['breadcrumbs'][] = ['label' => 'Pending payments', 'url' => ['listpendingassignment']];
$this->params['breadcrumbs'][] = $this->title;

?>
<h1><?= Html::encode($this->title) ?></h1>

<div class="row">
        <div class="col-md-9">
            <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'receiptnumber',
                'amount',
                'paymentdate',
                'datecreated',
                'paidinby',
                'paymentphonenumber',
                'paymentreference'
            ],
        ]) ?>
        </div>
</div>
<div class="row">
    <?php $form = ActiveForm::begin(); ?>
    <div class="form-group">
        <label class="control-label" for="comboTenant">Tenant</label>
        <?= AutoComplete::widget([
        'name' => 'comboTenant',
        'clientOptions' => [
            'source' => $tenants,
            'minLength' => 2,
            'autoFill' => TRUE,
            'select' => new JsExpression("function(event, ui){
                var id = ui.item.id;
                $('#tenantref').val(id);
                
                $.get('".Yii::$app->urlManager->createUrl(['tenant/listrentals'])."'+'?id='+id, function(data){
                    $('#rentalaccount').html(data)
                })
            }")
        ]
        ]); ?>
        <?= Html::hiddenInput('tenantref', null, ['id' => 'tenantref'])/* activeHiddenInput($model, 'tenantref')*/ ?>
        <?= Html::hiddenInput('id', $model->id) ?>
    </div>
    <div class="form-group">
        <label class="control-label" for="rentalaccount">Rental</label>
        <?= \kartik\helpers\Html::dropDownList('rentalaccount', null, ['Prompt' => 'Select rental a/c'], 
    ['id' => 'rentalaccount']) ?>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Assign', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
