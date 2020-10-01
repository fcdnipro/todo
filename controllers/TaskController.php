<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\Projects;
use app\models\Tasks;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Json;

class TaskController extends Controller
{


        public function actionCreateTask($id)
    {
        $taskName = isset($_POST["task_name"]) ? $_POST["task_name"] : '';
        $error = false;
        $errorMsg = [];
        $task = null;


        $taskModel = new Tasks();
        $maxPriority = $taskModel->find()->where(['project_id' => $id])->max('priority');
        $taskModel->name = $taskName;
        $taskModel->project_id = $id;
        $taskModel->status = 0;
        $taskModel->priority = $maxPriority + 1;
        if ($taskModel->validate()) {
            $taskModel->save();
            $task = [
                'id' => $taskModel->id,
                'name' => $taskModel->name,
                'status' => $taskModel->status,
                'project_id' => $taskModel->project_id,
                'priority' => $taskModel->priority,
                'deadline' => $taskModel->deadline,
            ];
            if ($taskModel->getErrors()) {
                $error = true;
                $errorMsg[] = 'Can`t input project name from DB!';
            }
        } else {
            $error = true;
            $errorMsg[] = 'Enter data not valid';
        }

        $result = [
            'task' => $task,
            'errorMsg' => $errorMsg,
            'error' => $error
        ];

        echo json_encode($result); die();

    }

        public function actionEditTask($id, $action){

            $taskModel = new Tasks();
            $task = $taskModel->find()->where(['id' => $id])->asArray()->one();
            $user_id = Yii::$app->user->identity->id;
            $project_id = $task['project_id'];
            $project = Projects::find()->where(['user_id' => $user_id,'id' => $project_id])->one();
            if ($project == false || empty($project)) {
                $error = true;
                $errorMsg[] = 'Have not access for current task!';

                $result = [
                    'errorMsg' => $errorMsg,
                    'error' => $error
                ];

                echo json_encode($result); die();
            }
            if($action == 'get')
            {

                $error = false;
                $errorMsg = [];
                $status = false;



                if ($task == false || empty($task))
                {
                    $error = true;
                    $errorMsg[] = 'Incorrect id!';
                }

                $result = [
                    'task' => $task,
                    'errorMsg' => $errorMsg,
                    'error' => $error
                ];

                echo json_encode($result); die();
            }

            if ($action == 'set')
            {
                $error = false;
                $errorMsg = [];
                $taskName = isset($_POST["name"]) ? $_POST["name"] : '';
                $status = !isset($_POST["status"]) ? 0 : 1;
                $deadline = isset($_POST["deadline"]) ? $_POST["deadline"] : '';

                $task = Tasks::findone($id);


                if ($task == false || empty($task))
                {
                    $error = true;
                    $errorMsg[] = 'Id not exist!';
                } else {
                    $task->name = $taskName;
                    $task->status = $status;
                    $task->deadline = $deadline;
                    if ($task->validate()) {
                        $task->save();
                        if ($task->getErrors()) {
                            $error = true;
                            $errorMsg[] = 'Can`t input project name from DB!';
                        }
                    } else {
                        $error = true;
                        $errorMsg[] = 'Enter data not valid';
                    }
                }

                $result = [
                    'task' => [
                        'name' => $taskName,
                        'status' => $status,
                        'project_id' => $task->project_id,

                    ],
                    'errorMsg' => $errorMsg,
                    'error' => $error,
                ];

                echo json_encode($result); die();
            }

    }

    public function actionRemoveTask($id) {
        $error = false;
        $errorMsg = [];
        $taskModel = new Tasks();
        $task = $taskModel->find()->where(['id' => $id])->asArray()->one();
        $user_id = Yii::$app->user->identity->id;
        $project_id = $task['project_id'];
        $project = Projects::find()->where(['user_id' => $user_id,'id' => $project_id])->one();
        if ($project == false && empty($project)) {
            $error = true;
            $errorMsg[] = 'Have not access for current task!';

            $result = [
                'errorMsg' => $errorMsg,
                'error' => $error
            ];

            echo json_encode($result); die();
        }
        $task = Tasks::findone($id);
        $project_id = $task->project_id;
            $task->delete();
        if ($task->getErrors()) {
            $error = true;
            $errorMsg[] = 'Incorrect id!';
        }

        $result = [
            'projectId' => $project_id,
            'errorMsg' => $errorMsg,
            'error' => $error
        ];

        echo json_encode($result); die();
        }

    public function  actionTaskUp($id,$project_id){
            $error = false;
            $errorMsg = [];
            $date = date('Y-m-d H:i:s');
            $taskModel = new Tasks();
            $task = $taskModel->find()->where(['id' => $id])->one();
            $priority = $task->priority;
            $prevTask = Tasks::getTaskUp($date, $priority, $project_id);
            $prevTask = Tasks::find()->where(['id' => $prevTask[0]['id']])->one();

            $prevPriority = $prevTask->priority;
            $task->priority = $prevPriority;
            $prevTask->priority = $priority;
            $task->save();
            $prevTask->save();
            if ($task->getErrors()) {
                $error = true;
                $errorMsg[] = 'Can`t replace main task!';
                if ($prevTask->getErrors())
                {
                    $prevTask->priority = $prevPriority;
                    $errorMsg[] = 'Can`t replace prev task!';

                }
                $task->priority = $priority;
                $task->save();
            }
            if ($prevTask->getErrors()) {
                $error = true;
                $errorMsg[] = 'Can`t replace main task!';
                if ($task->getErrors())
                {
                    $task->priority = $prevPriority;
                    $task->save();
                    $errorMsg[] = 'Can`t replace prev task!';
                }
                $prevTask->priority = $priority;
                $prevTask->save();
            }

            $result = [
                'errorMsg' => $errorMsg,
                'error' => $error
            ];

            echo json_encode($result); die();
        }

    public function  actionTaskDown($id,$project_id){
            $error = false;
            $errorMsg = [];
            $date = date('Y-m-d H:i:s');
            $taskModel = new Tasks();
            $task = $taskModel->find()->where(['id' => $id])->one();
            $priority = $task->priority;
            $prevTask = Tasks::getTaskDown($date, $priority, $project_id);
            $prevTask = Tasks::find()->where(['id' => $prevTask[0]['id']])->one();

            $prevPriority = $prevTask->priority;
            $task->priority = $prevPriority;
            $prevTask->priority = $priority;
            $task->save();
            $prevTask->save();
            if ($task->getErrors()) {
                $error = true;
                $errorMsg[] = 'Can`t replace main task!';
                if ($prevTask->getErrors())
                {
                    $prevTask->priority = $prevPriority;
                    $prevTask->save();
                    $errorMsg[] = 'Can`t replace prev task!';

                }
                $task->priority = $priority;
                $task->save();
            }
            if ($prevTask->getErrors()) {
                $error = true;
                $errorMsg[] = 'Can`t replace main task!';
                if ($task->getErrors())
                {
                    $task->priority = $prevPriority;
                    $task->save();
                    $errorMsg[] = 'Can`t replace prev task!';
                }
                $prevTask->priority = $priority;
                $prevTask->save();
            }

            $result = [
                'errorMsg' => $errorMsg,
                'error' => $error
            ];

            echo json_encode($result); die();

        }



    public function actionRefreshTask($id) {

        $taskModel = new Tasks();
        $date = date('Y-m-d H:i:s');
        $task = Tasks::getTasksList($date,$id);


        echo $this->renderPartial('task_form',
            [
                'task' => $task,
            ]);
        die();

    }


}
?>
