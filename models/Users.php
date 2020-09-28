<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $accessToken
 * @property string $authKey
 */
class Users extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            [['id'], 'integer'],
            [['username', 'password', 'accessToken', 'authKey'], 'string', 'max' => 50],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'accessToken' => 'Access Token',
            'authKey' => 'Auth Key',
        ];
    }
    public static function getUserList() {
       $users = self::find()->asArray()->all();
       $keyUsers = [];
       foreach ($users as $key => $val)
       {
           $keyUsers[$val['id']] = $val;
       }

       return $keyUsers;
    }
}


