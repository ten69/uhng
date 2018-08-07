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
                    'api' => ['POST','GET'],
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

    public function actionApi($p = '')
    {        
        $dt = $_POST['Property'];
        $page_size = $_POST['PageSize'];
        $page_index = $_POST['PageIndex'];
        $sort = $_POST['OrderBy'];
        $data = explode(',', $dt);

        $orderby = ['sp_gia_sort' => SORT_ASC];
        if($sort == 1) $orderby = ['sp_gia_sort' => SORT_ASC];
        elseif($sort == 2) $orderby = ['sp_gia_sort' => SORT_DESC];


        
        if($p == '1000100'){//Kết quả số đếm search
            $a = SanphamDanhmuc::getDb()->cache(function ($db) use ($data, $page_size, $page_index) {
                return SanphamDanhmuc::find()
                                    ->select(['spdm_id_sp'])
                                    ->where(['spdm_id_danhmuc' => $data])
                                    ->groupBy('spdm_id_sp')
                                    ->offset($page_size *  $page_index)                                    
                                    ->column();            
            });
            if($a){
                $html = '<p class="doit"><button type="submit" class="viewresult" onclick="ShowResult()">Xem '.count($a).' kết quả</button></p>';
            }else{
                $html = '<p class="doit"><button type="button" class="noresult">Không tìm thấy</button></p>';
            }
            return $html;
        }


        elseif($p == '101100') { //List sản phâm
            Aabc::$app->response->format = \aabc\web\Response::FORMAT_JSON; 
            $return = '';
            $a = SanphamDanhmuc::getDb()->cache(function ($db) use ($data, $page_size, $page_index,$orderby) {
                return SanphamDanhmuc::find()
                                    ->select(['spdm_id_sp'])
                                    ->where(['spdm_id_danhmuc' => $data])
                                    ->groupBy('spdm_id_sp')
                                    ->offset($page_size *  $page_index)
                                    ->limit($page_size)
                                    ->joinWith('thongtinSanpham')
                                    ->orderby($orderby)
                                    ->column();            
            });
            
            if($a) foreach ($a as $k => $idsp) {
                $return .= $this->renderPartial('/laptop/_item',[
                    'idsp' => $idsp,
                ]);                
            }
            $more = SanphamDanhmuc::getDb()->cache(function ($db) use ($data, $page_size, $page_index) {
                return SanphamDanhmuc::find()
                                    ->select(['spdm_id_sp'])
                                    ->where(['spdm_id_danhmuc' => $data])
                                    ->groupBy('spdm_id_sp')
                                    ->offset($page_size *  ($page_index + 1))
                                    ->count();
            });
            return [
                'html' => $return,
                'more' => ($more > 0?'Xem thêm '.$more.' laptop':''),
            ];            
        } 

        elseif($p == '110001'){ //fiter mới
            $return = '<div class="choosedfilter">';
            $dem = 0;
            $count = count($data);
            if($data) foreach ($data as $tt) {
                $dm = Tuyen::_dulieu('danhmuc',$tt);
                $dem += 1;
                if($count == 1){
                    $return .= '<a href="?t">'.$dm['dm_ten'].'<i class="icontgdd-clearfil"></i></a>';
                }else{
                    $return .= '<a href="javascript:;" onclick="RemoveFilter(this, 3, '.$tt.')">'.$dm['dm_ten'].'<i class="icontgdd-clearfil"></i></a>';
                }
            }
            if($dem > 1){
                $return .= '<a class="reset" href="?t">Xóa tất cả<i class="icontgdd-clearfil"></i></a>';
            }

            $return .= '</div>';
            return $return;
        }

    }
}
