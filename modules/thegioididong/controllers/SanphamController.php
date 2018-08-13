<?php
#1
namespace frontend\modules\thegioididong\controllers;

use Aabc;
use aabc\web\Controller;
use aabc\helpers\Html;
use aabc\helpers\Url; /*Them*/
use aabc\filters\VerbFilter;
use common\components\Tuyen;



class SanphamController extends Controller
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
        $this->layout = 'sanpham/main';
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

    public function actionIndex($slug = '', $id = '')
    {
        // $this->layout = 'chuyenmuc/main';
        // $this->layout = 'site/main';
        $model = Tuyen::_dulieu('sanpham', $id);       
        if(!$model){
            header('Location: /', true,302);
            exit();
        }
        else{
            if($model['sp_status'] == 2 OR $model['sp_recycle'] == 1){
                header('Location: /', true,302);
                exit();
            }

            $slug = Tuyen::_get_link($slug,$id,'sanpham');

            if($slug != $model['sp_linkseo']){
                header('Location: /'.$model['sp_link'], true,302);
                exit();
            }

            $kq = $this->render('index', [
                'model' => $model,                
            ]);   
            return $kq;
        }       
    }

   
}
