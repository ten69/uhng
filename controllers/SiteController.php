<?php
namespace frontend\controllers;

use Aabc;
use aabc\base\InvalidParamException;
use aabc\web\BadRequestHttpException;
use aabc\web\Controller;
use aabc\filters\VerbFilter;
use aabc\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;


class SiteController extends Controller
{
    
    public function behaviors()
    {

        // echo '<pre>';

        // $a = [
        //     'class' => 'aabc\filters\PageCache',
        //     'only' => ['index','contact'],
        //     'duration' => 0,
        //     'variations' => [
        //         \Aabc::$app->language,
        //     ],
        // ];

        // print_r($a);
        // die;

        return [

          //   [
	        	// 'class' => 'aabc\filters\PageCache',
	         //    'only' => ['index','contact'],
	         //    'duration' => 0,
	         //    'variations' => [
	         //        \Aabc::$app->language,
	         //    ],
          //   ],

            
            // 'access' => [
            //     'class' => AccessControl::className(),
            //     'only' => ['logout', 'signup'],
            //     'rules' => [
            //         [
            //             'actions' => ['signup'],
            //             'allow' => true,
            //             'roles' => ['?'],
            //         ],
            //         [
            //             'actions' => ['logout'],
            //             'allow' => true,
            //             'roles' => ['@'],
            //         ],
            //     ],
            // ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        $this->layout = 'site/main';
        if ($action->id == 'error') $this->layout = 'main-error';

        return parent::beforeAction($action);
    }

    
    public function actions()
    {
        return [
            'error' => [
                'class' => 'aabc\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'aabc\captcha\CaptchaAction',
                'fixedVerifyCode' => AABC_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
 

    public function actionTuyen($value='')
    {        
        // print_r(Array ('aabc\filters\PageCache'));
    }
    
    public function actionIndex()
    {
        // $this->layout = 'chuyenmuc/main';        
        // $this->layout = 'site/main';       
        return $this->render('index');
    }

    
}
