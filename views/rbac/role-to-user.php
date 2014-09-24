<?php
use yii\helpers\Html;

$this->params['breadcrumbs'][] = ['label' => 'List Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->emailaddress;
?>
<div class="page-header">
    <h1>Change user role</h1>
</div>

<div class="form-group">
    Current role: <b><?= $model->item_name ?></b>
</div>
<?php $form = yii\widgets\ActiveForm::begin(); ?>
<div class="form-group">
    Select new role
</div>
<div class="form-group">
    <?= Html::dropDownList('role', '', \yii\helpers\ArrayHelper::map($roles, 'name', 'name'), ['prompt' => '-Select new role-']) ?>
</div>
 
<div class="form-group">
    <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
</div>
<?php
yii\widgets\ActiveForm::end();
?>
