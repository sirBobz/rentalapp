<?php

use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var app\models\UnitSearch $searchModel
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title = 'Units for '. $property->name . ' ' . \app\models\Property::propertyGenreDropDown()[$property->genre];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="suite-index">

    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Unit', ['create', 'propertyid' => $property->id], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            [
                'attribute' => 'propertyname',
                'label' => 'Property',  
                'filter' => \app\models\Property::forDropDown()
            ],
            'description',
            [
                'attribute' => 'isavailable',
                //'type' => 'boolean',
                'label' => 'Availability',
                'filter' => \app\models\Unit::availabilityDropDown(),
                'value' => function($model, $index, $dataColumn){
                    return app\models\Unit::availabilityDropDown()[$model->isavailable];
                }
            ],
            //'isavailable:boolean',
            ['class' => 'yii\grid\ActionColumn',
                'template' => '{view}{update}'
            ]
        ],
    ]); ?>

</div>
