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
<?php 
$ret = Yii::$app->mailer->compose()
        ->setTo('dlukoba@yahoo.com')
        ->setFrom('daniel@lukoba.com')
        ->setSubject('sample')
        ->setTextBody('body')
        ->send(); 

$m = Yii::$app->mailer;
var_dump($ret);
die();

?>

<?php $this->beginBody() ?>
    <div class="wrap">
        <?php
        $keyname = "roles.actions";
        $cache = Yii::$app->cache;
        
        $exists = $cache->exists($keyname);
        if(!$exists)
        {
            $allRolesAndRespectiveActions = Yii::$app->db->createCommand("SELECT `parent`, `child` FROM `auth_item_child`")
                ->queryAll();
            $encoded = json_encode($allRolesAndRespectiveActions);
            
            //expire in 5 mins
            $cache->add($keyname, $encoded, 300);
        }
        $encodedRolesActions = $cache->get($keyname);
        $decodedRolesActionsArray = json_decode($encodedRolesActions);
        $userRoles = Yii::$app->authManager->getRolesByUser(Yii::$app->user->id);
        $rolesForUser = [];
        foreach ($userRoles as $key => $value) {
          $rolesForUser[] = $key;
        }
        
        $actionsForUser = [];
        foreach ($decodedRolesActionsArray as $roleAction)
        {
            $roleAction->parent;
            $exists = in_array($roleAction->parent, $rolesForUser);
            
            if($exists)
                $actionsForUser[] = $roleAction->child;
            
        }
        //$allowable = in_array('uploadpayment', $actionsForUser);
        //var_dump(Yii::$app->user->id);
        //die();
        
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
                    ['label' => 'Home', 'url' => ['/site/index'], 'visible' => true],
                    ['label' => 'Payments',
                        'visible' => (Yii::$app->user->id == NULL)? false: true,
                        'items' => [
                            [
                                'label' => 'Upload', 'url' => ['payment/upload'], 'visible' => in_array('uploadpayment', $actionsForUser)
                            ],
                            [
                                'label' => 'Simulate IPN form', 'url' => ['payment/simulate'], 'visible' => in_array('simulatepayment', $actionsForUser)
                            ],
                            [
                                'label' => 'Pending actioning', 'url' => ['payment/listpendingassignment'], 'visible' => in_array('paymentforactioning', $actionsForUser)
                            ]
                        ]
                    ],
                    ['label' => 'Properties', 
                        'visible' => (Yii::$app->user->id == NULL)? false: true,
                        'items' => [
                            [
                                'label' => 'Property owners', 'url' => ['property-owner/index'], 'visible' => in_array('viewpropertyowner', $actionsForUser)
                            ],
                            [
                                'label' => 'Rental Accounts', 'url' => ['rental-account/index'], 'visible' => in_array('viewrentalaccount', $actionsForUser)
                            ]
                        ]
                    ],
                    ['label' => 'People',
                        'visible' => (Yii::$app->user->id == NULL)? false: true,
                        'items' => [
                            [
                                'label' => 'Tenants', 'url' => ['tenant/index'], 'visible' => in_array('viewrentalaccount', $actionsForUser)
                            ],
                            [
                                'label' => 'System users', 'url' => ['rbac/index'], 'visible' => in_array('viewusers', $actionsForUser)
                            ]
                        ]
                    ],
                    ['label' => 'Reports', 
                        'visible' => (Yii::$app->user->id == NULL)? false: true,
                        'items' => [
                            [
                                'label' => 'Late payment accounts', 'url' => ['rental-account/late-payment-accounts'], 'visible' => in_array('viewrentalaccount', $actionsForUser)
                            ]
                            ,
                            /*[
                                'label' => 'late payment accounts over months'
                            ],*/
                            [
                                'label' => 'Unoccupied units', 'url' => ['unit/unoccupied', 'UnitSearch[isavailable]' => 1], 'visible' => in_array('viewrentalaccount', $actionsForUser)
                            ],
                            [
                                'label' => 'Property type uptake', 'url' => ['unit/uptake'], 'visible' => in_array('viewunit', $actionsForUser)
                            ],
                            [
                                'label' => 'Concentrated locations', 'visible' => in_array('viewrentalaccount', $actionsForUser)
                            ],
                            [
                                'label' => 'Most upcoming locations', 'visible' => in_array('viewrentalaccount', $actionsForUser)
                            ]
                        ]
                    ],
                    ['label' => 'System',
                        'visible' => (Yii::$app->user->id == NULL)? false: true,
                        'items' => [
                            [
                                'label' => 'System roles', 'url' => ['rbac/roles'], 'visible' => in_array('viewrole', $actionsForUser)
                            ],
                            [
                                'label' => 'System permissions', 'url' => ['rbac/permissions'], 'visible' => in_array('viewroleperms', $actionsForUser)
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
<?= kartik\helpers\Html::csrfMetaTags() ?>
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
