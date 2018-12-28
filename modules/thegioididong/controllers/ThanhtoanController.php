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
        
        $a = Tuyen::_dulieu('cauhinh',Cauhinh::cart_dangnhap); //Setting Khách có cần đăng nhập không?
        if($a == 2){//có phải đăng nhập
            $login = ['index'];
            $not = ['login'];
        }else{//Không cần đăng nhập
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
        $session = Aabc::$app->session;

        $model = new CartForm();
        if ($model->load(Aabc::$app->request->post()) && $model->validate()) {

            //Hiển thị lại những gì đã post lên
            $cart = $session['cart'];  

            $session['thongtinthanhtoan'] = $model;
            $session['thongtinthanhtoan'] = null;
            
            // if($cart){
            //     return $this->render('index', [
            //         'model' => $model,
            //         'cart' => $cart,
            //     ]);
            // }

            //Nhận thông tin thanh toán post lên và lưu vào db


            $a = Tuyen::_dulieu('cauhinh',Cauhinh::cart_dangnhap); //Setting Khách có cần đăng nhập không?
            if($a == 2){//có phải đăng nhập
                //Có id userfrontend đang đăng nhập
                //Tìm trong DS khách hàng where iduserfrontend ->trả về idkhachhang
                //Lưu đơn hàng với idkhachhang
            }else{//Không cần đăng nhập
                //Kiểm tra email, điện thoại có trùng ai không
                    //+Trùng: status = 1
                    //+Không trùng: status = 0
                //Lưu vào bảng khách hàng -> trả về idkhachhang
                //Lưu đơn hàng với idkhachhang
            }

            echo '<pre>';
            print_r($model->attributes);
            echo '</pre>';

            $session = Aabc::$app->session;
            $cart = $session['cart'];
            $cart_success = $session['cart_success'];
            echo '<pre>';
            print_r($cart);
            echo '</pre>';

            echo '<pre>';
            print_r($cart_success);
            echo '</pre>';
            //die;
            die;
            // return $this->redirect(['/']);
            // return $this->goBack();

            //Sẽ lưu vào trong session cart_info
            // $session = Aabc::$app->session;
            // $cart_info = $session['cart_info']; 
            
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
            if(isset($session['thongtinthanhtoan'])) $model = $session['thongtinthanhtoan']; 

            if($cart){
                return $this->render('index', [
                    'model' => $model,
                    // 'cart' => $cart,
                ]);
            }
            else{
                return $this->redirect(['/gio-hang.html']);
            }

           
        }

    }

   
}
