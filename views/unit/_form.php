<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
//use yii\jui\Accordion;
/**
 * @var yii\web\View $this
 * @var app\models\UnitForm $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="suite-form">

    <?php $form = ActiveForm::begin(['id' => 'suite-form', 
        'options' => ['class' => 'form-horizontal', 'style' => 'width:400px']]); ?>
    
    <?= Html::activeHiddenInput($model, 'propertyid') ?>
    
    <?= $form->field($model, 'creation_method')->dropDownList(['1' => 'Manually fill form', '2' => 'Automatically create suites'], 
    ['prompt' => '-Select a method-', 'id' => 'creation-method']) ?>
    
    <div style="display: none;" class="autoSuiteForm">
        <?= $form->field($model, 'numSuites')->textInput(['maxlength' => 5]) ?>
    </div>
    <div style="display: none;" class="manualSuiteForm">
        <?= $form->field($model, 'name')->textInput(['maxlength' => 150]) ?>

        <?= $form->field($model, 'description')->textArea(['rows' => 3, 'cols' => 40]) ?>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Create', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    <?php $this->registerJsFile('js/common.functions.js', [\yii\web\JqueryAsset::className()]); ?>
</div>
