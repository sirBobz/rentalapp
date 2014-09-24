<?php

namespace app\controllers;

use Yii;
use app\models\Login;
use app\components\MultidimensionArraySearchHelper;

class RbacController extends \yii\web\Controller
{
    /**
     * @permission viewusers
     */
    public function actionIndex()
    {
        $searchModel = new \app\models\UserSearch;
        $params = Yii::$app->request->getQueryParams();
        $dataProvider = $searchModel->search($params);
                
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * @permission viewrole
     */
    public function actionRoles()
    {
        $auth = Yii::$app->authManager;
        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $auth->getRoles()
        ]);        
        
        return $this->render('roles', [
            'roles' => $dataProvider
        ]);
    }
    
    /**
     * @permission addrole
     */
    public function actionAddRole()
    {
        $model = new \app\models\RoleForm;
          
        if($model->load(\Yii::$app->request->post()))
        {
            $role = new \yii\rbac\Role;
            $role->name = $model->name;
            $role->type = \yii\rbac\Item::TYPE_ROLE;
            $role->description = $model->description;
            $role->createdAt = time();
            $role->updatedAt = time();
            
            \Yii::$app->authManager->add($role);
            
            return $this->redirect(['roles']);
        }
        
        return $this->render('add-role', [
            'model' => $model
        ]);
    }

    /**
     * @permission viewroleperms
     */
    public function actionRolePermissions($role)
    {
        $rc = new \ReflectionClass(get_class());
        $perm = $rc->getMethod($this->action->actionMethod)->getDocComment();
        $perm = preg_replace('/\W/', "", $perm);
        $perm = substr($perm, 10);
        
        /*$can = Yii::$app->user->can($perm);
        if(!$can)
            throw new \yii\web\NotFoundHttpException('You do no have permission to access the requested page.');
        */
        
        $auth = \Yii::$app->authManager;
        $model = $auth->getRole($role);
        $assignedPermissions = $auth->getPermissionsByRole($role);
        $allPermissions = $auth->getPermissions();
        
        $postedData = \Yii::$app->request->post();
        if(isset($postedData) && !empty($postedData))
        {
            foreach ($postedData['perm'] as $data) 
            {
                
                $exists = array_key_exists($data, $assignedPermissions);
                if (!$exists)
                {    
                    $auth->addChild($model, $allPermissions[$data]);
                }
            }
            //redirect to success page
        }
        
        return $this->render('role-permissions', [
            'role' => $model,
            'assignedPermissions' => $assignedPermissions,
            'allPermissions' => $allPermissions
        ]);
    }

    /**
     * @permission viewperms
     */
    public function actionPermissions()
    {
        $controllers = Yii::$app->metaInfo->getAll();
        
        /*$role = $auth->getRole('admin');
        $auth->assign($role, 1);*/
        //$can = Yii::$app->user->can('createPropertyOwner');
        $post = Yii::$app->request->post();
        
        if(isset($post) && !empty($post))
        {
            $auth = Yii::$app->authManager;
        
            /*
             * check if the posted permissions already exist in the db
             * skip those that already to
             */
            $existingPerms = (new \yii\db\Query())
                    ->select('name')
                    ->from('auth_item')
                    ->where([
                        'type' => 2,
                        'name' => $post['perm']
                    ])
                    ->all();
            
            foreach ($post['perm'] as $perm)
            {
                $foundKey = MultidimensionArraySearchHelper::Search($perm, $existingPerms);
                
                //tis a new permission add it to the db
                if(empty($foundKey))
                {
                    $persistedPerm = $auth->createPermission($perm);
                    $auth->add($persistedPerm);
                }
            }
            
        }
        
        return $this->render('permissions', $controllers);
    }

    /**
     * @permission assignroles
     */
    public function actionRoleToUser($id)
    {
        $auth = \Yii::$app->authManager;
        $model = Login::find()
                ->select(['login.emailaddress', 'login.status', 'e.name', 'login.datecreated', 
                    'e.entitytype', 'ai.item_name'])
                ->where(['login.id' => $id])
                ->innerJoin('entity e', 'login.entityref = e.id')
                ->leftJoin('auth_assignment ai', 'login.id = ai.user_id')
                ->one();
        $roles = $auth->getRoles();
                
        $postedData = \Yii::$app->request->post();
        if(isset($postedData) && !empty($postedData))
        {
            $newRole = $postedData['role'];
            $role = $roles[$postedData['role']];
            $auth->assign($role, $id);
            
            //redirect to result page
        }
        
        return $this->render('role-to-user', [
            'model' => $model,
            'roles' => $roles
        ]);
    }
}
