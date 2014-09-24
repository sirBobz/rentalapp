<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $model->unitname;
$this->params['breadcrumbs'][] = ['label' => 'Rental account', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rental-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row">
        <div class="col-md-9">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'accountnumber',
                    'unitname',
                    'propertyname',
                    'propertycode',
                    'tenantname',
                    'datecreated',
                    'depositamount',
                    'rentalperiod',
                    'amountperperiod',
                    'currentbalance',
                    'depositrentalperiodpaidfor',
                    'lastpaymentdate',
                    'latepaymentcharge'
                ],
            ]) ?>
        </div>
        <div class="col-md-3">
            <ul>
                <!--<li><a href="<?= Yii::$app->urlManager-> createUrl(['unit/assign', 'unitid' => $model->id]) ?>">Assign to tenant</a> </li>-->
                
            </ul>
        </div>
    </div>
    
</div>
