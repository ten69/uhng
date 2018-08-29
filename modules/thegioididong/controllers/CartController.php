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

class CartController extends Controller
{
    public function getControllerLabel()
    {
        return 'Cart';
    }
   
    public function behaviors()
    {
        date_default_timezone_set('asia/ho_chi_minh');
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['GET'],
                    'add' => ['POST'],
                    'add-aj' => ['POST'],
                    'update-cart' => ['POST'],
                ],
            ],
        ];
    }
   
     public function beforeAction($action)
    {
        $this->layout = 'cart/main';
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
        $session = Aabc::$app->session;
        $cart = $session['cart']; 
        // $session['cart'] = null;             
        return $this->render('index', [
            'cart' => $cart,
        ]);
    }


     public function actionAdd()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            self::addcart(empty($_POST['product'])?0:$_POST['product']);            
            $this->redirect(['/cart.html']);
        }
    }

    public function actionAddAj()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {            
            self::addcart(empty($_POST['product'])?0:$_POST['product']);
        }else{   
            echo 'get';
        }
    }

    protected function addcart($idsp = 0)
    {
        $idsp = (int)$idsp;
        if(!empty($idsp)){
            $sp = Tuyen::_dulieu('sanpham',$idsp);
            if($sp){                
                $session = Aabc::$app->session;
                $cart = $session['cart'];
                $cart[] = [
                    'sanpham' => $sp->sp_id,
                    'soluong' => 1,
                    'gia' => $sp->sp_gia_label,
                    'thongso' => $_POST['ts'],
                    'khuyenmai' => $sp->sp_khuyenmai,
                ];
                $session['cart'] = $cart;
            }           
        }
    }




     public function actionUpdate()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $sp = Tuyen::_dulieu('sanpham',$_POST['product']);
            $pb = $sp['sp_phienban'];
            echo '<pre>';
            print_r($sp['sp_phienban']);
            echo '</pre>';

            echo '<pre>';
            print_r($_POST['ts']);
            echo '</pre>';

            $a = [];
            foreach ($_POST['ts'] as $k_ts => $v_gt) {
                $a = [
                    'title' => $pb[$k_ts]['title'],
                    'option' => $pb[$k_ts]['option'][$v_gt]['name'],
                    '' => $pb[$k_ts]['option'][$v_gt]['name'],
                ];

                echo '<pre>';
                print_r($a);
                echo '</pre>';
            }
        }else{   
            echo 'get';
        }
    }

    //Add cart direct
    //Add cart ajax

    //View cart get

    //Update cart ajax


}
