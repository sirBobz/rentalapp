<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "login".
 *
 * @property string $id
 * @property string $entityref
 * @property string $emailaddress
 * @property string $password
 * @property string $datecreated
 * @property integer $createdbyref
 * @property integer $status
 *
 * @property Entity $entityref0
 */
class Login extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    const STATUS_INACTIVE = 1;
    const STATUS_ACTIVE = 2;
    
    public $newPassword;
    public $name;
    public $entitytype;
    public $item_name;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'login';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['entityref', 'emailaddress', 'password', 'datecreated', 'status'], 'required'],
            [['entityref', 'createdbyref', 'status'], 'integer'],
            [['datecreated'], 'safe'],
            [['emailaddress'], 'string', 'max' => 45],
            [['password'], 'string', 'max' => 128],
            [['emailaddress'], 'unique'],
            [['emailaddress'], 'filter', 'filter' => 'trim'],
            
            [['newPassword'], 'string', 'min' => 7],
            [['newPassword'], 'filter', 'filter' => 'trim'],
            [['newPassword'], 'required', 'on' => ['register']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'entityref' => 'Entityref',
            'emailaddress' => 'Email address',
            'password' => 'Password',
            'datecreated' => 'Datecreated',
            'createdbyref' => 'Createdbyref',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntityref0()
    {
        return $this->hasOne(Entity::className(), ['id' => 'entityref']);
    }
    
    public function getId() {
        return $this->id;
    }
    
    public static function findIdentity($id) {
        return static::findOne($id);
    }
    
    public static function findByEmail($email)
    {
        return static::findOne(["emailaddress" => $email]);
    }

    public function verifyPassword($password)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $this->password);
    }

    public function getAuthKey() {
        return $this->auth_key;
    }

    public static function findIdentityByAccessToken($token, $type = null) {
        return static::findOne(["api_key" => $token]);
    }

    public function validateAuthKey($authKey) {
        return $this->auth_key === $authKey;
    }
    
    public function beforeSave($insert) {
        $this->password = \yii\helpers\Security::generatePasswordHash($this->newPassword);
        $this->datecreated = date('Y-m-d H:i:s');
        $this->status = static::STATUS_ACTIVE;
        
        return parent::beforeSave($insert);
    }
    
    public static function loginStatusDropDown()
    {
        static $dropdown;
        if ($dropdown == NULL)
        {
            $reflection = new \ReflectionClass(get_called_class());
            $constants = $reflection->getConstants();
            
            foreach ($constants as $name => $value) {
                if(strpos($name, "STATUS_") === 0)
                {
                    $prettyName = str_replace("STATUS_", "", $name);
                    $prettyName = \yii\helpers\Inflector::humanize(strtolower($prettyName));
                    $dropdown[$value] = $prettyName;
                }
            }
        }
        return $dropdown;
    }
}
