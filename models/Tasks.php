<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * This is the model class for table "{{%tasks}}".
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $status
 * @property int $project_id
 * @property int $priority
 * @property string $deadline
 */
class Tasks extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tasks}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'project_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            //['name', 'required'],
            ['name', 'match', 'pattern' => '/^[a-zA-Z._*\d]+$/'],
            [['deadline'], 'default', 'value' => date('Y-m-d H:i:s')],
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
            'status' => 'Status',
            'project_id' => 'Project ID',
            'priority' => 'Priority',
            'deadline' => 'Deadline',
        ];
    }
}
