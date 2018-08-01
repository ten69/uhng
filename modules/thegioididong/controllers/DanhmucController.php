<?php
#1
namespace frontend\modules\thegioididong\controllers;

use Aabc;
use aabc\web\Controller;
use aabc\helpers\Html;
use aabc\helpers\Url; /*Them*/
use aabc\filters\VerbFilter;

use common\components\Tuyen;
use common\cont\D;


class DanhmucController extends Controller
{
    public function getControllerLabel()
    {
        return 'Danh mục';
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
        // $this->layout = 'danhmuc/main';
        $this->layout = 'laptop/main';
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
        $model = Tuyen::_dulieu('danhmuc', $id);
        
        // echo '<pre>';
        // print_r($model);
        // echo '</pre>';
        // die;

        if(!$model){
            header('Location: /', true,302);
            exit();
        }
        if($model['dm_status'] == 2 OR $model['dm_recycle'] == 1){
            header('Location: /', true,302);
            exit();
        }

        if($model['dm_type'] == 1) $slug = $slug . '-'.D::url_dm.$id.'.html';
      

        if($model){
            if($slug != $model['dm_link']){
                header('Location: /'.$model['dm_link'], true,302);
                exit();
            }
        }
               
        $kq = $this->render('/laptop/index', [
            'model' => $model,                
        ]);            
       
        return $kq;
    }

   
}
