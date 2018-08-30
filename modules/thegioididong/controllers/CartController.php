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
            ($_POST['ts'])?$thongso = $_POST['ts']:$thongso = '';
            self::addcart(empty($_POST['product'])?0:$_POST['product'], $thongso);            
            return $this->redirect(['/cart.html']);
        }
    }

    public function actionAddAj()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {    
            ($_POST['ts'])?$thongso = $_POST['ts']:$thongso = '';
            $return = [];
            $return['status'] = self::addcart(empty($_POST['product'])?0:$_POST['product'], $thongso); 

            $session = Aabc::$app->session;
            $cart = $session['cart'];            
            $return['count'] = sizeof($cart);
            Aabc::$app->response->format = \aabc\web\Response::FORMAT_JSON; 
            return $return;
        }else{   
            echo 'get';
        }
    }

    protected function addcart($idsp = 0, $thongso, $soluong = 1)
    {
        $idsp = (int)$idsp;
        if(!empty($idsp)){
            $sp = Tuyen::_dulieu('sanpham',$idsp);
            if($sp){                
                $session = Aabc::$app->session;
                $cart = $session['cart'];                
                $cart[] = [
                    'sanpham' => $sp->sp_id,
                    'soluong' => $soluong,
                    'gia' => $sp->sp_gia_label,
                    'thongso' => $thongso,
                    'khuyenmai' => $sp->sp_khuyenmai,
                ];
                $session['cart'] = $cart;
                return true;
            }           
        }
        return false;
    }




     public function actionUpdate()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $session = Aabc::$app->session;                    
            $session['cart'] = null;

            if(isset($_POST['cart'])){
                $cart_u = $_POST['cart'];                
                foreach ($cart_u as $k => $v) {
                    self::addcart($v['sanpham'], $v['thongso'], $v['soluong']);
                } 
            }

            $return = [];
            $return['h'] = $this->renderPartial('index', [
                'cart' => $session['cart'],
            ]);
            Aabc::$app->response->format = \aabc\web\Response::FORMAT_JSON; 
            return $return;
        }else{   
            echo 'get';
        }
    }

    //Add cart direct
    //Add cart ajax

    //View cart get

    //Update cart ajax


}
