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
            ['name', 'required'],
            ['name', 'match', 'pattern' => '/^[a-zA-Z._*!,:;@$%#&()?+=-^\d ]+$/'],
            [['deadline'], 'default', 'value' => date('Y-m-d H:i:s', strtotime('+ 1 day'))],
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

    public static function getTasksList($date,$project_id){
        $sqlTask = self::getTasksListSQL();
        $task = Yii::$app->db->createCommand($sqlTask)->bindValues([
            'date' => $date,
            'pid' => $project_id,
        ])->queryAll();

        return $task;
    }

    public static function getTasksListSQL(){
        $sqlTask ='
            SELECT * 
            FROM (
                SELECT *, IF(t.deadline <= :date AND t.status <> 1, "1", "0") AS deadline_flag
                FROM tasks AS t
                WHERE t.project_id = :pid 
            ) AS t
            ORDER BY t.deadline_flag ASC, t.status ASC, t.priority DESC 
        ';

        return $sqlTask;
    }

    public static function getTaskUp($date, $priority, $project_id){
        $sqlTask = self::getTaskUpSQL();

        $task = Yii::$app->db->createCommand($sqlTask)->bindValues([
            'date' => $date,
            'prior' => $priority,
            'pid' => $project_id,
        ])->queryAll();

        return $task;
    }

    public static function getTaskUpSQL(){
        $sqlTask ='
            SELECT * FROM (
                SELECT *, IF(t.deadline <= :date AND t.status <> 1, "1", "0") AS deadline_flag
                FROM tasks AS t
                WHERE t.project_id = :pid AND t.priority > :prior AND t.status <> 1
                ORDER BY t.priority ASC 
            ) AS t
            WHERE t.deadline_flag <> 1
            ORDER BY t.priority ASC LIMIT 1
        ';

        return $sqlTask;

    }

    public static function getTaskDown($date, $priority, $project_id){
        $sqlTask = self::getTaskDownSQL();
        $task = Yii::$app->db->createCommand($sqlTask)->bindValues([
            'date' => $date,
            'prior' => $priority,
            'pid' => $project_id,
        ])->queryAll();

        return $task;
    }

    public static function getTaskDownSQL(){
        $sqlTask ='
            SELECT * FROM (
                SELECT *, IF(t.deadline <= :date AND t.status <> 1, "1", "0") AS deadline_flag
                FROM tasks AS t
                WHERE t.project_id = :pid AND t.priority < :prior AND t.status <> 1
                ORDER BY t.priority ASC 
            ) AS t
            WHERE t.deadline_flag <> 1
            ORDER BY t.priority DESC LIMIT 1
        ';

        return $sqlTask;
    }
}