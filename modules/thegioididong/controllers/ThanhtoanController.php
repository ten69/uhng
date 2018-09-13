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

use frontend\models\CartForm;

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
        $user = Aabc::$app->user->identity;
       
       
        $model = new CartForm();
        if ($model->load(Aabc::$app->request->post()) && $model->validate()) {
            // return $this->redirect(['/']);
            // return $this->goBack();

            //Sẽ lưu vào trong session cart_info
            // $session = Aabc::$app->session;
            // $cart_info = $session['cart_info']; 
            $a = $b;

            return $this->goHome();
        } else {   
            if($user){
                $model->hoten = $user->hoten;
                $model->dienthoai = $user->dienthoai;
                $model->email = $user->email;
                $model->diachi = $user->diachi;
                $model->gioitinh = $user->gioitinh;
            }       
            $session = Aabc::$app->session;
            $cart = $session['cart'];         
            return $this->render('index', [
                'model' => $model,
                'cart' => $cart,
            ]);
        }

    }

   
}
