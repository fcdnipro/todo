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

class ProjectController extends Controller
{

    public function actionCreateProject()
    {
        $projectName = isset($_POST["project_name"]) ? $_POST["project_name"] : '';
        $error = false;
        $errorMsg = [];
        $project = null;
        $user_id = Yii::$app->user->identity->id;

        $projectsModel = new Projects();
        $projectsModel->user_id = $user_id;
        $projectsModel->name = $projectName;
        if ($projectsModel->validate()) {
            $projectsModel->save();
            $project = [
                'id' => $projectsModel->id,
                'name' => $projectsModel->name,
            ];
            if ($projectsModel->getErrors()) {
                $error = true;
                $errorMsg[] = 'Can`t input project name from DB!';
            }
        } else {
            $error = true;
            $errorMsg[] = 'Enter data not valid';
        }

        $result = [
            'project' => $project,
            'errorMsg' => $errorMsg,
            'error' => $error
        ];

        echo json_encode($result); die();

    }
    public function actionEditProject($id,$action){
        if($action == 'get')
        {
            $projectModel = new Projects();
            $error = false;
            $errorMsg = [];

            $project = $projectModel->find()->select(['id','name'])->where(['id' => $id])->asArray()->one();
            if ($project == false || empty($project))
            {
                $error = true;
                $errorMsg[] = 'Incorrect id!';
            }

            $result = [
                'project' => $project,
                'errorMsg' => $errorMsg,
                'error' => $error
            ];

            echo json_encode($result); die();
        }

        if ($action == 'set')
        {
            $error = false;
            $errorMsg = [];
            $projectName = isset($_POST["name"]) ? $_POST["name"] : '';

            $project = Projects::findone($id);
            if ($project == false || empty($project))
            {
                $error = true;
                $errorMsg[] = 'Id not exist!';
            } else {
                $project->name = $projectName;

                if ($project->validate()) {
                    $project->save();
                    if ($project->getErrors()) {
                        $error = true;
                        $errorMsg[] = 'Can`t input project name from DB!';
                    }
                } else {
                    $error = true;
                    $errorMsg[] = 'Enter data not valid';
                }
            }

            $result = [
                'project' => [
                    'name' => $projectName,
                ],
                'errorMsg' => $errorMsg,
                'error' => $error
            ];

            echo json_encode($result); die();
        }
    }

    public function actionRemoveProject($id) {
        $error = false;
        $errorMsg = [];
        $user_id = Yii::$app->user->identity->id;

        $project = Projects::find()->where(['user_id' => $user_id,'id' => $id])->one();
        if ($project != false && !empty($project)) {
            $tasks = Tasks::deleteAll(['project_id' => $id]);
            $project->delete();
        }
        else {
            $error = true;
            $errorMsg[] = 'Have not access for current project';

            $result = [
                'errorMsg' => $errorMsg,
                'error' => $error
            ];

            echo json_encode($result); die();
        }
        if ($project->getErrors()) {
            $error = true;
            $errorMsg[] = 'Incorrect id!';
        }

        $result = [
            'errorMsg' => $errorMsg,
            'error' => $error
        ];

        echo json_encode($result); die();
    }






}


?>
