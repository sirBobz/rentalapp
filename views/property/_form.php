<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\Property $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="property-form">

    <?php $form = ActiveForm::begin([
        'options' => ['style' => 'width:400px']
    ]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 100]) ?>
    
    <?= $form->field($model, 'genre')->dropDownList($model::propertyGenreDropDown(), ['prompt' => '-Select a property genre-']) ?>
    
    <?= $form->field($model, 'type')->dropDownList($model->propertyTypeDropDown(), ['prompt' => '-Select a property type-']) ?>

    <?= $form->field($model, 'locationref')->dropDownList(\yii\helpers\ArrayHelper::map(app\models\Location::find()->all(), 'id', 'name'), ['prompt' => '-Select a location-']) ?>

    <?= $form->field($model, 'lastpaymentdate')->textInput() ?>

    <?= $form->field($model, 'latepaymentcharge')->textInput(['maxlength' => 10]) ?>

    <?php 
    $arr = array();
    for($i = 1; $i < 4; $i++)
    {
        $arr[$i] = $i; 
    }
    echo $form->field($model, 'rentalperiod')->dropDownList($arr, ['prompt' => '-Select billing period-']);
            ?>

    <?= $form->field($model, 'lat')->textInput(['maxlength' => 10]) ?>

    <?= $form->field($model, 'long')->textInput(['maxlength' => 10]) ?>

    <?= $form->field($model, 'description')->textArea(['rows' => 3, 'cols' => 30]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
