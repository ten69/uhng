<?php
#1
namespace frontend\modules\thegioididong\controllers;

use Aabc;
use aabc\web\Controller;
use aabc\helpers\Html;
use aabc\helpers\Url; /*Them*/
use aabc\filters\VerbFilter;



class SiteController extends Controller
{
    public function getControllerLabel()
    {
        return 'Site';
    }
   
    public function behaviors()
    {
        date_default_timezone_set('asia/ho_chi_minh');
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'get-order' => ['GET'],
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

    public function actionIndex()
    {
        // $this->layout = 'chuyenmuc/main';        
        // $this->layout = 'site/main';       
        return $this->render('index');
    }

   
}
