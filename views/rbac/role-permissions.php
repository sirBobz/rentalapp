<?php
use yii\helpers\Html;

$this->title = $role->name;
$this->params['breadcrumbs'][] = ['label' => 'System Roles', 'url' => ['roles']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-header">
    <h1>Permissions under role <small><?= $role->name ?></small></h1>
</div>

<?php 
$form = yii\widgets\ActiveForm::begin();

foreach ($allPermissions as $permission)
{
    $exists = \app\components\MultidimensionArraySearchHelper::Search($permission, $assignedPermissions);
    //echo $exists;
    echo "<input type='checkbox' name='perm[]' value='$permission->name' ".(($exists != null) ? 'checked' : "") ." /> ";
    print($permission->name);
    echo "<br />";
}
?>
<div class="form-group">
    <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
</div>
<?php
yii\widgets\ActiveForm::end();
?>
