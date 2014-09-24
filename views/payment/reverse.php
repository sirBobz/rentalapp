<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use yii\jui\AutoComplete;
use yii\web\JsExpression;

$this->title = 'Reverse an unmatched payment';
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
        <?= Html::submitButton('Reverse', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
