<?php
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = $model->accountnumber;
$this->params['breadcrumbs'][] = ['label' => 'Rental Accounts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-header">
    <h1>Account number: <?= Html::encode($this->title) ?></h1>
</div>

<div class="row">
    <div class="col-sm-2">
        Current a/c balance
    </div>
    <div class="col-sm-2">
        <?= $model->currentbalance ?>
    </div>
    <div class="col-sm-offset-10">
        <?= Html::a('Close Account', ['rental-account/close', 'id' => $model->id], ['id' => 'closeAccount']) ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-2">
        Date Created
    </div>
    <div class="col-sm-2">
        <?= $model->datecreated ?>
    </div>
    <div class="col-sm-1"></div>
    <div class="col-sm-2">Billing start date</div>
    <div class="col-sm-2"><?= $model->billingstartdate ?></div>
</div>

<div class="row">
    <div class="col-sm-2">
        Rental period (months)
    </div>
    <div class="col-sm-2">
        <?= $model->rentalperiod ?>
    </div>
    <div class="col-sm-1"></div>
    <div class="col-sm-2">Amount per period</div>
    <div class="col-sm-2"><?= $model->amountperperiod ?></div>
</div>

<div class="row">
    <div class="col-sm-2">
        Last payment date
    </div>
    <div class="col-sm-2">
        <?= $model->lastpaymentdate ?>
    </div>
    <div class="col-sm-1"></div>
    <div class="col-sm-2">Late payment charge (%)</div>
    <div class="col-sm-2"><?= $model->latepaymentcharge ?></div>
</div>

<div class="row">
    <div class="col-sm-2">
        Tenant
    </div>
    <div class="col-sm-2">
        <?= $model->tenantname ?>
    </div>
    
</div>

<div class="row">
    <div class="col-sm-2">
        Property name
    </div>
    <div class="col-sm-2">
        <?= $model->propertyname ?>
    </div>
    <div class="col-sm-1"></div>
    <div class="col-sm-2">Property code</div>
    <div class="col-sm-2"><?= $model->propertycode ?></div>
</div>

<div class="row">
    <div class="col-sm-2">
        Unit name
    </div>
    <div class="col-sm-2">
        <?= $model->unitname ?>
    </div>
    
</div>

<div class="row">
    <div class="col-sm-2">
        Current expiry
    </div>
    <div class="col-sm-2">
        <?= $model->expirydate ?>
    </div>
    
</div>

<div class="row">
<?php
echo yii\jui\Tabs::widget([
    'items' => [
        [
            'label' => 'Transaction History',
            'headerOptions' => ['style' => 'font-weight:bold;'],
            'url' => ['rental-account/transactionhistory', 'id' => $model->id]
        ],
        [
            'label' => 'Sms History',
            'content' => 'content two',
            'headerOptions' => ['style' => 'font-weight:bold'],
            
        ]
    ]
]);
?>
</div>

<?php 
yii\bootstrap\Modal::begin(['header' => '<h2>Accout closed successfully</h2>', 'id' => 'modal']);
echo "<div id='modalContent'></div>";
yii\bootstrap\Modal::end();

$jsPath = Yii::getAlias('@web/js/common.functions.js');

$this->registerJsFile($jsPath, ['depends' => \yii\web\JqueryAsset::className()]);
?>
