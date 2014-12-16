<?php
use miloschuman\highcharts\Highcharts;

$this->title = "Units uptake";
?>
<div class="page-header">
    <h1><?= $this->title; ?></h1>
</div>
<?php
$perGenre = array();
foreach($uptakePerGenre as $item)
{
    $arrItem = array();
    $arrItem['name'] = \app\models\Property::propertyGenreDropDown()[$item['genre']];
    $arrItem['y'] = (int)$item['unitsrented'];
    $perGenre[] = $arrItem;
}

$perType = array();
foreach($uptakePerType as $item)
{
    $arrItem = array();
    $arrItem['name'] = \app\models\Property::propertyTypeDropDown()[$item['type']];
    $arrItem['y'] = (int)$item['unitsrented'];
    $perType[] = $arrItem;
}

?>
<div class="row">
    <div class="col-sm-6">
    <?php
    echo Highcharts::widget([
        'scripts' => [
            'themes/grid-light',
        ],
        'options' => [
            'title' => [
                'text' => 'Grouping of units uptake per genre',
            ],

            'series' => [
                [
                    'type' => 'pie',
                    'name' => '# rented units',
                    'data' => $perGenre,
                    //'center' => [50, 100],
                    'size' => 150,
                    'showInLegend' => FALSE,
                    'dataLabels' => [
                        'enabled' => FALSE,
                    ],
                ],
            ],
        ]
    ]);
    ?>
    </div>
    <div class="col-sm-6">
    <?php
    echo Highcharts::widget([
        'scripts' => [
            'themes/grid-light',
        ],
        'options' => [
            'title' => [
                'text' => 'Grouping of units uptake per type',
            ],

            'series' => [
                [
                    'type' => 'pie',
                    'name' => '# rented units',
                    'data' => $perType,
                    //'center' => [800, 100],
                    'size' => 150,
                    'showInLegend' => FALSE,
                    'dataLabels' => [
                        'enabled' => FALSE,
                    ],
                ],
            ],
        ]
    ]);
    ?>
    </div>
</div>