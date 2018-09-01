<?php
#1
namespace frontend\modules\thegioididong\controllers;

use Aabc;
use aabc\web\Controller;
use aabc\helpers\Html;
use aabc\helpers\Url; /*Them*/
use aabc\filters\VerbFilter;


use common\components\Tuyen;
use backend\models\SanphamDanhmuc;

class BarcodeController extends Controller
{
    public function getControllerLabel()
    {
        return 'Barcode';
    }
   
    public function behaviors()
    {
        date_default_timezone_set('asia/ho_chi_minh');
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [                    
                    'api' => ['POST','GET'],
                    'delete' => ['POST'],
                    'get-order' => ['GET'],
                ],
            ],
        ];
    }
   
     public function beforeAction($action)
    {
        $this->layout = 'barcode/main';
        if ($action->id == 'error') $this->layout = 'main-error';

        $skip_action = [
            'api',
        ];

        if(in_array($action->id,$skip_action)){
            $this->enableCsrfValidation = false;
        }
        
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
       $model = [];
        for ($i = 0; $i < 50; $i++) {            
            $model[] = [
                'code' => '55523442347',
                'content' => 'Sản phẩm abc Sản phẩm abc Sản phẩm abc Sản phẩm abc Sản phẩm abc Sản phẩm abc',
            ];
            $model[] = [
                'code' => '124673452347',
                'content' => 'Sản phẩm bhd Sản phẩm bhd Sản phẩm bhd Sản phẩm bhd Sản phẩm bhd Sản phẩm bhd Sản phẩm bhd ',
            ];
        }
        return $this->render('index',[
            'model' => $model,
        ]);
    }




}
