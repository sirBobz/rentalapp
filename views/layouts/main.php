<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

/**
 * @var \yii\web\View $this
 * @var string $content
 */
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>
    <div class="wrap">
        <?php
            NavBar::begin([
                'brandLabel' => 'Rental App',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => [
                    ['label' => 'Home', 'url' => ['/site/index']],
                    ['label' => 'Payments',
                        'items' => [
                            [
                                'label' => 'Upload', 'url' => ['payment/upload']
                            ],
                            [
                                'label' => 'Simulate IPN form', 'url' => ['payment/simulate']
                            ],
                            [
                                'label' => 'Pending actioning', 'url' => ['payment/listpendingassignment']
                            ]
                        ]
                    ],
                    ['label' => 'Properties', 
                        'items' => [
                            [
                                'label' => 'Property owners', 'url' => ['property-owner/index']
                            ],
                            [
                                'label' => 'Rental Accounts', 'url' => ['rental-account/index']
                            ]
                        ]
                    ],
                    ['label' => 'People',
                        'items' => [
                            [
                                'label' => 'Tenants', 'url' => ['tenant/index']
                            ],
                            [
                                'label' => 'System users', 'url' => ['rbac/index']
                            ]
                        ]
                    ],
                    ['label' => 'Reports', 
                        'items' => [
                            [
                                'label' => 'Late payments', 'url' => ['site/users']
                            ]
                        ]
                    ],
                    ['label' => 'System',
                        'items' => [
                            [
                                'label' => 'System roles', 'url' => ['rbac/roles']
                            ],
                            [
                                'label' => 'System permissions', 'url' => ['rbac/permissions']
                            ]
                        ]
                    ],
                    Yii::$app->user->isGuest ?
                        ['label' => 'Login', 'url' => ['/site/login']] :
                        ['label' => 'Logout (' . Yii::$app->user->identity->emailaddress . ')',
                            'url' => ['/site/logout'],
                            'linkOptions' => ['data-method' => 'post']],
                ],
            ]);
            NavBar::end();
        ?>

        <div class="container">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?php 
            if(Yii::$app->session->hasFlash('success')): ?>
            <div class="alert alert-success">
                <?= Yii::$app->session->getFlash('success') ?>
            </div>
            <?php 
            elseif (Yii::$app->session->hasFlash('error')): ?>
            <div class="alert alert-danger">
                <?= Yii::$app->session->getFlash('error') ?>
            </div>
            <?php endif; ?>
            <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p class="pull-left">&copy; Rental App <?= date('Y') ?></p>
            <p class="pull-right"><?= Yii::powered() ?></p>
        </div>
    </footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
