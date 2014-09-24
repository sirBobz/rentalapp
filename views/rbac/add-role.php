<?php
use yii\helpers\Html;

$this->title = 'Create new role';
$this->params['breadcrumbs'][] = ['label' => 'List System roles', 'url' => ['roles']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-header">
    <h1><?= Html::encode($this->title) ?></h1>
</div>

<?php $form = yii\widgets\ActiveForm::begin(); ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => 60]) ?>

    <?= $form->field($model, 'description')->textArea(['rows' => 3, 'cols' => 40]) ?>

    <div class="form-group">
        <?= Html::submitButton('Create', ['class' => 'btn btn-success']) ?>
    </div>
<?php yii\widgets\ActiveForm::end(); ?>