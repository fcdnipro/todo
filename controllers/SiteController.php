<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Projects;
use app\models\Tasks;
use app\models\LoginForm;
use app\models\User;
use app\models\Users;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login','create-user'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout','index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['logout','index'],
                        'allow' => false,
                        'roles' => ['?'],
                    ],
                ],
            ],

        ];
    }



    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionCreateUser(){
        $userName = isset($_POST["user_name"]) ? $_POST["user_name"] : '';
        $pass =isset($_POST["password"]) ? $_POST["password"] : '';
        $error = false;
        $errorMsg = [];
        $user = null;

        $userModel = new Users();

        $userModel->username = $userName;
        $userModel->password =$pass;
        $userModel->accessToken = '';
        $userModel->authKey = '';
        if ($userModel->validate()) {
            $userModel->save();
            $user = [
                'id' => $userModel->id,
                'name' => $userModel->username,
                'password' =>$userModel->password,
            ];
            if ($userModel->getErrors()) {
                $error = true;
                $errorMsg[] = 'Can`t input project name from DB!';
            }
        } else {
            $error = true;
            $errorMsg[] = 'Enter data not valid';
        }

        $result = [
            'user' => $user,
            'errorMsg' => $errorMsg,
            'error' => $error
        ];

        echo json_encode($result); die();
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $modelProjects = new Projects();
        $modelTasks = new Tasks();
        $user_id = Yii::$app->user->identity->id;

        $projects = $modelProjects->find()->where(['user_id' => $user_id])->asArray()->all();
        $date = date('Y-m-d H:i:s');
        foreach ($projects as $key => $val) {
            $projects[$key]['tasks'] = Tasks::getTasksList($date,$projects[$key]['id']);
            if ($projects[$key]['tasks'] == null)
            {
                $projects[$key]['tasks'] = [];
            }
        }

        $task=[
            'id' => $modelTasks->id,
            'name' => $modelTasks->name,
            'status' => $modelTasks->status,
            'project_id' => $modelTasks->project_id,
            'priority' => $modelTasks->priority,
            'deadline' => $modelTasks->deadline,
            ];


        return $this->render('index',
            [
                'task' => $task,
                'modelProjects' => $modelProjects,
                'projects' => $projects,
            ]

        );
    }


}
