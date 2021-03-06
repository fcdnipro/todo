<?php

namespace app\models;

use app\models\Users;


class User extends \yii\base\BaseObject implements \yii\web\IdentityInterface
{
    public $id;
    public $username;
    public $password;
    public $authKey;
    public $accessToken;




    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        $users = Users::getUserList();
        return isset($users[$id]) ? new static($users[$id]) : null;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $users = Users::getUserList();
        foreach ($users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        $users = Users::getUserList();
        foreach ($users as $user) {
            if (strcasecmp($user['username'], $username) === 0) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */


    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser());
        }
    }
    public function getUser()
    {
        if ($this->user === false) {
            $this->user = Users::findOne(['username'=>$this->username,
                'password'=>$this->pass]);
        }

        return $this->user;
    }

    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $password;
    }
}
