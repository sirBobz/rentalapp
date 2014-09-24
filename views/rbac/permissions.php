<?php
//use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>
<div class="page-header">
    <h1>Sync any permissions with Db</h1>
</div>

<?php
ActiveForm::begin();
?>
<div class="form-group">
    <?= Html::submitButton('Sync', ['class' => 'btn btn-success']) ?>
</div>
<?php
foreach ($controllers as $controller)
{
    echo "<div class='row'>";
        echo "<h4>". $controller['name'] . "</h4>";
        
        echo "<ul>";
        foreach ($controller['actions'] as $action)
        {
            $perm = $action[1];
            
            if(!empty($perm))
            {
                //print("<input type='checkbox' name='". $perm . "' />" . $action[0] . " - " . $perm);
                print("<input type='hidden' name='perm[". $perm . "]' value='". $perm . "' />" . $action[0] . " - " . $perm);
                print("<br />");
            }
        }
        echo "</ul>";
    echo "</div>";
}
yii\bootstrap\ActiveForm::end();
?>
