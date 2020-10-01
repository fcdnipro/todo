<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * This is the model class for table "{{%projects}}".
 *
 * @property int $id
 * @property string|null $name
 */
class Projects extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%projects}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 255],
            [['name',], 'required',],
            ['name', 'match', 'pattern' => '/^[a-zA-Z\d ]+$/'],
            [['user_id'], 'integer'],
            [['user_id',], 'required',],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'user_id' => 'User ID'
        ];

    }

}