<?php

use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var app\models\UnitSearch $searchModel
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title = 'List of unoccupied units';
//$this->params['breadcrumbs'][] = $this->title;
?>

<div class="suite-index">

    <div class="page-header">
        <h1><?= $this->title; ?></h1>
    </div>
    
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
<?php yii\widgets\Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'name',
            [
                'attribute' => 'propertyname',
                'label' => 'Property',  
                //'filter' => \app\models\Property::forDropDown()
            ],
            'propertyowner',
            'description'
        ],
    ]); ?>
<?php yii\widgets\Pjax::end(); ?>
</div>
