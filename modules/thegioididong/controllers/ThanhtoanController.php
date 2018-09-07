<?php
#1
namespace frontend\modules\thegioididong\controllers;

use Aabc;
use aabc\web\Controller;
use aabc\helpers\Html;
use aabc\helpers\Url; /*Them*/
use aabc\filters\VerbFilter;
use aabc\filters\AccessControl;

use common\components\Tuyen;
use backend\models\Cauhinh;
use backend\models\SanphamDanhmuc;

class ThanhtoanController extends Controller
{
    public function getControllerLabel()
    {
        return 'Thanh toán';
    }
   
    public function behaviors()
    {
        date_default_timezone_set('asia/ho_chi_minh');   
       
        $a = Tuyen::_dulieu('cauhinh',Cauhinh::cart_dangnhap);        
        if($a == 2){
            $login = ['index'];
            $not = ['login'];
        }else{
            $login = [];
            $not = ['login','index'];
        }
        
        return [
             'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'signup','login'],
                'rules' => [
                    [
                        'actions' => $not,
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => $login,
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],


            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                ],
            ],
        ];
    }

   
   
     public function beforeAction($action)
    {
        $this->layout = 'thanhtoan/main';
        if ($action->id == 'error') $this->layout = 'main-error';

        $this->enableCsrfValidation = false;
               
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
