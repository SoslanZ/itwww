<?php

namespace common\models;

class UserSimple extends \yii\base\Object implements \yii\web\IdentityInterface
{
    public $id;
    public $username;
    public $password;
    public $authKey;
    public $accessToken;
    
    private static $users = [
        '100' => [
            'id' => '100',
            'username' => 'admin',
            'password' => '123',
            'authKey' => 'test100key',
            'accessToken' => '100-token',
        ],
    ];

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return isset(self::$users[$id]) ? new static(self::$users[$id]) : null;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        foreach (self::$users as $user) {
            if (strcasecmp($user['username'], $username) === 0) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $password;
    }
    
    public function afterLogin($event) {
        
        //\Yii::$app->params['operatorPhone'] = $this->username;
        //\Yii::$app->request->post('var');
        
       // \Yii::$app->request->setBodyParams('operatorPhone',$this->username);
        
        //\Yii::$app->params['adminEmail'] = 'test';
        
        //$this->userPhone = 'test';
        
       /* $_SESSION['test'] = $this->username;
                
        $this->afterLogout($event);
        
        $cPhone = new CallsPhones;
        $cPhone->phone_num = $this->username;
        //$cPhone->date_activity = 'NOW()';
        $cPhone->save();*/
        
    }
    
    public function afterLogout($event) {
        
        /*$cPhone = CallsPhones::deleteAll('phone_num = '.$this->username);*/
        
    }
}
