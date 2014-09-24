<?php

use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\PropertyOwnerSearch $searchModel
 */

$this->title = 'Property Owners';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="property-owner-index">

    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Property Owner', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php    \yii\widgets\Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'phonenumber',
            'emailaddress:email',
            'datecreated',
            // 'entitytype',

            ['class' => 'yii\grid\ActionColumn',
                'template' => '{view}{update}'
            ],
        ],
    ]); ?>
<?php \yii\widgets\Pjax::end(); ?>
</div>
