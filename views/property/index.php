<?php

use yii\helpers\Html;
use yii\grid\GridView;

use app\models\Property;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\PropertySearch $searchModel
 */

$this->title = 'Properties for '. $owner->name;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="property-index">

    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Property', ['create', 'ownerid' => $ownerid], ['class' => 'btn btn-success']) ?>
    </p>
<?php    \yii\widgets\Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            //'description',
            //'datecreated',
            //'createdbyref',
            // 'datedestroyed',
            // 'destroyedbyref',
            // 'propertyownerref',
            'code',
            [
                'attribute' => 'locationref',
                'label' => 'Location',
                'filter' => \app\models\Location::forDropDown(),
                'value' => function($model, $index, $dataColumn){
                    return \app\models\Location::forDropDown()[$model->locationref];
                }
            ],
            [
                'attribute' => 'type',
                'label' => 'Type',
                'filter' => Property::propertyTypeDropDown(),
                'value' => function($model, $index, $dataColumn){
                    return Property::propertyTypeDropDown()[$model->type];
                }
            ],
            [
                'attribute' => 'genre',
                'label' => 'Genre',
                'filter' => \app\models\Property::propertyGenreDropDown(),
                'value' => function($model, $index, $dataColumn){
                    return \app\models\Property::propertyGenreDropDown()[$model->genre];
                }
            ],
            ['class' => 'yii\grid\ActionColumn',
                'template' => '{view}{update}'
            ],
        ],
    ]); ?>
<?php \yii\widgets\Pjax::end(); ?>
</div>
