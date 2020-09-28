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

class SqlTestController extends Controller
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
                        'actions' => ['index'],
                        'allow' => true,
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


    public function actionIndex()
    {
        $sqlTest[0]['sql'] = self::sqlTest1();
        $sqlTest[0]['result'] = Yii::$app->db->createCommand($sqlTest[0]['sql'])->queryAll();
        $sqlTest[0]['message'] = 'Get all statuses, not repeating, alphabetically ordered.';

        $sqlTest[1]['sql'] = self::sqlTest2();
        $sqlTest[1]['result'] = Yii::$app->db->createCommand($sqlTest[1]['sql'])->queryAll();
        $sqlTest[1]['message'] = 'Get the count of all tasks in each project, order by tasks count descending.';

        $sqlTest[2]['sql'] = self::sqlTest3();
        $sqlTest[2]['result'] = Yii::$app->db->createCommand($sqlTest[2]['sql'])->queryAll();
        $sqlTest[2]['message'] = 'Get the count of all tasks in each project, order by project name.';

        $sqlTest[3]['sql'] = self::sqlTest4();
        $sqlTest[3]['result'] = Yii::$app->db->createCommand($sqlTest[3]['sql'])->queryAll();
        $sqlTest[3]['message'] = 'Get rhe tasks for all projects having the name beginning with "N" letter.';

        $sqlTest[4]['sql'] = self::sqlTest5();
        $sqlTest[4]['result'] = Yii::$app->db->createCommand($sqlTest[4]['sql'])->queryAll();
        $sqlTest[4]['message'] = 'Get the list of all projects containing the "a" letter in the middle of the name, and show the tasks count near each project.';

        $sqlTest[5]['sql'] = self::sqlTest6();
        $sqlTest[5]['result'] = Yii::$app->db->createCommand($sqlTest[5]['sql'])->queryAll();
        $sqlTest[5]['message'] = 'Get the list of tasks with duplicate names. Order alphabeticaly.';

        $sqlTest[6]['sql'] = self::sqlTest7();
        $sqlTest[6]['result'] = Yii::$app->db->createCommand($sqlTest[6]['sql'])->queryAll();
        $sqlTest[6]['message'] = 'Get list of tasks having several exact matches of both name and status, from project "Garage". Order by matches count.';

        $sqlTest[7]['sql'] = self::sqlTest8();
        $sqlTest[7]['result'] = Yii::$app->db->createCommand($sqlTest[7]['sql'])->queryAll();
        $sqlTest[7]['message'] = 'Get list of project names having more than 10 tasks in status "completed". Order by "project_id".';

        return $this->renderPartial('sql_test', [
            'sqlTest' => $sqlTest,
        ]);
    }

    private function sqlTest1(){
        $sqlTest1 = '
            SELECT DISTINCT status 
            FROM tasks AS t
            ORDER BY t.status;
        ';

        return $sqlTest1;
    }

    private function sqlTest2(){
        $sqlTest2 = '
            SELECT p.name, COUNT(t.project_id) AS count
            FROM projects AS p
            LEFT JOIN tasks AS t
                ON t.project_id = p.id    
            GROUP BY p.id
            ORDER BY count DESC;
        ';

        return $sqlTest2;
    }

    private function sqlTest3(){
        $sqlTest3 = '
            SELECT p.name, COUNT(t.project_id) AS count
	        FROM projects AS p
	        LEFT JOIN tasks AS t
		        ON t.project_id = p.id          
	        GROUP BY p.id
	        ORDER BY p.name;
        ';

        return $sqlTest3;
    }

    private function sqlTest4(){
        $sqlTest4 = '
            SELECT t.name
            FROM projects AS p
            LEFT JOIN tasks AS t 
                ON t.project_id = p.id
            WHERE p.name LIKE "N%";
        ';

        return $sqlTest4;
    }

    private function sqlTest5(){
        $sqlTest5 = '
            SELECT p.name, COUNT(t.project_id) AS count
            FROM projects AS p 
            LEFT JOIN tasks AS t 
                ON t.project_id = p.id 
            WHERE p.name LIKE "%a%" 
            GROUP BY p.id;
        ';

        return $sqlTest5;
    }

    private function sqlTest6(){
        $sqlTest6 = '
            SELECT t.name
            FROM tasks AS t
            GROUP BY t.name
            HAVING COUNT(t.name) > 1
            ORDER BY t.name ASC;
        ';

        return $sqlTest6;
    }

    private function sqlTest7(){
        $sqlTest7 = '
            SELECT t.name as task_name, t.status, p.name as project_name, amount              
            FROM (
                SELECT t.name, COUNT(t.id) AS amount, t.status, MAX(project_id) AS pid
                FROM tasks AS t                
                GROUP BY t.name, t.status
            ) AS t
            JOIN projects AS p 
                ON pid = p.id
            WHERE p.name = "Garage"
            ORDER BY amount DESC;
        ';

        return $sqlTest7;
    }

    private function sqlTest8(){
        $sqlTest8 = '
            SELECT p.name as project_name, p.id                
            FROM (
                SELECT t.project_id
                FROM tasks AS t	                
                WHERE t.status = 1
                GROUP BY t.project_id                
                HAVING COUNT(t.id) > 10
            ) AS t
            JOIN projects AS p 
                ON t.project_id = p.id
            ORDER BY t.project_id DESC;
        ';

        return $sqlTest8;
    }
}
