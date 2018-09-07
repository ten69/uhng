<?php
#1
namespace frontend\modules\thegioididong\controllers;

use Aabc;
use aabc\web\Controller;
use aabc\helpers\Html;
use aabc\helpers\Url; /*Them*/
use aabc\filters\VerbFilter;
use aabc\filters\AccessControl;
use frontend\models\SignupForm;

use common\components\Tuyen;
use backend\models\Cauhinh;
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
       
        // $a = Tuyen::_dulieu('cauhinh',Cauhinh::cart_dangnhap);        
        // if($a == 2){
        //     $login = ['index'];
        //     $not = ['signup','login'];
        // }else{
        //     $login = [];
        //     $not = ['signup','login','index'];
        // }
        
        return [
            //  'access' => [
            //     'class' => AccessControl::className(),
            //     'only' => ['index', 'signup','login'],
            //     'rules' => [
            //         [
            //             'actions' => $not,
            //             'allow' => true,
            //             'roles' => ['?'],
            //         ],
            //         [
            //             'actions' => $login,
            //             'allow' => true,
            //             'roles' => ['@'],
            //         ],
            //     ],
            // ],


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
        // $this->layout = 'chuyenmuc/main';        
        // $this->layout = 'site/main'; 
        return $this->render('index');
    }

    public function actionDangNhap()
    {
        $this->layout = 'thanhtoan/main';    
        return $this->render('dang-nhap');
    }


    public function actionDangKy()
    {
        $this->layout = 'thanhtoan/main';   
        $model = new SignupForm(); 
        return $this->render('dang-ky',[
            'model' => $model,
        ]);
    }



    public function actionApi($p = '')
    {        
        $data = [];
        $Property = '';
        if(isset($_POST['Property'])){
            $dt = $_POST['Property'];
            $Property = $_POST['Property'];
            $data = explode(',', $dt);
        }


        $Category = '';
        if(isset($_POST['Category']))
            $Category = $_POST['Category'];

        if(isset($_POST['PageSize']))
            $page_size = $_POST['PageSize'];

        if(isset($_POST['PageIndex']))
            $page_index = $_POST['PageIndex'];

        $sort = '';
        if(isset($_POST['OrderBy']))
            $sort = $_POST['OrderBy'];

        

        // $orderby = ['sp_gia_sort' => SORT_ASC];
        // if($sort == 1) $orderby = ['sp_gia_sort' => SORT_ASC];
        // elseif($sort == 2) $orderby = ['sp_gia_sort' => SORT_DESC];


        $orderby = '`sp_gia_sort`';
        if($sort == 1) $orderby = '`sp_gia_sort`';
        elseif($sort == 2) $orderby = '`sp_gia_sort` DESC';



        $list_ts = [];
        if(is_array($data)) foreach ($data as $ts) {
            $thongso = Tuyen::_dulieu('danhmuc',$ts);
            if($thongso)
                $list_ts[$thongso['dm_idcha']][] = $ts;
        }

        $where = ''; //Tạo câu where với mỗi nhóm thông số. Cùng nhóm sẽ là in, khác nhóm sẽ là and
        $dem = 0;
        foreach ($list_ts as $value) {
            $value = implode($value,',');            
            if($dem > 0) $where .= ' AND ';
            $where .= ' (`spdm_id_sp` IN (SELECT `spdm_id_sp` FROM `db_sanpham_danhmuc` WHERE `spdm_id_danhmuc` IN ('.$value.'))) ';            
            $dem+= 1;
        }
        if(empty($where)) $where = '0';//Nếu không có thông số nào thì where 0



        $connection = Aabc::$app->getDb();
       

        if($p == '1000100'){//Kết quả số đếm search

            if(empty($data[0])) $where = '1 = 0';

            // $a = SanphamDanhmuc::getDb()->cache(function ($db) use ($data, $page_size, $page_index,$connection,$where) {
                // return SanphamDanhmuc::find()
                //                     ->select(['spdm_id_sp'])
                //                     ->where(['spdm_id_danhmuc' => $data])
                //                     ->groupBy('spdm_id_sp')
                //                     ->offset($page_size *  $page_index)                                    
                //                     ->column(); 
                $command = $connection->createCommand("
                    SELECT `spdm_id_sp`
                    FROM `db_sanpham_danhmuc`                    
                    WHERE 
                    ".$where."
                    GROUP BY `spdm_id_sp`                           
                    ", []);
            //     return $command->queryColumn();           
            // });
            
            $a = $command->queryColumn();

            if($a){
                $html = '<p class="doit"><button type="submit" class="viewresult" onclick="ShowResult()">Xem '.count($a).' kết quả</button></p>';
            }else{
                $html = '<p class="doit"><button type="button" class="noresult">Không tìm thấy</button></p>';
            }
            return $html;
        }


        elseif($p == '101100') { //List sản phâm, MORE
            Aabc::$app->response->format = \aabc\web\Response::FORMAT_JSON; 
            $return = '';
            if(empty($Property)){ //Danh mục
                $danhmuc = Tuyen::_dulieu('danhmuc',$Category);
                $dem = 1; 
                $min = $page_size * $page_index + 1;
                $max = $page_size * ($page_index + 1);                
                $size_max = sizeof($danhmuc['dm_listsp']);

                if(is_array($danhmuc['dm_listsp'])) foreach ($danhmuc['dm_listsp'] as $k => $idsp) {    
                    if($dem >= $min && $dem <= $max){
                        $return .= $this->renderPartial('/laptop/_item',[
                            'idsp' => $idsp,
                        ]); 
                    }
                    $dem += 1;
                }
                $more = $size_max - $max;
                return [
                    'html' => $return,
                    'more' => ($more > 0?'Xem thêm '.$more.' laptop':''),
                ];

            }
            else{ //Thông số  
                // $a = SanphamDanhmuc::getDb()->cache(function ($db) use ($data, $page_size, $page_index,$orderby,$connection,$where) {
                    $command = $connection->createCommand("
                        SELECT `spdm_id_sp`
                        FROM `db_sanpham_danhmuc`
                        LEFT JOIN `db_sanpham` ON `db_sanpham_danhmuc`.`spdm_id_sp` = `db_sanpham`.`sp_id`
                        WHERE 
                        ".$where."
                        GROUP BY `spdm_id_sp`
                        ORDER BY ".$orderby."
                        LIMIT ".$page_size."
                        OFFSET ".$page_size *  $page_index."            
                        ", []);
                //     return $command->queryColumn();           
                // });
                $a = $command->queryColumn();
                
                if($a) foreach ($a as $k => $idsp) {
                    $return .= $this->renderPartial('/laptop/_item',[
                        'idsp' => $idsp,
                    ]);                
                }
                $more = SanphamDanhmuc::getDb()->cache(function ($db) use ($data, $page_size, $page_index,$connection,$where) {
                    $command = $connection->createCommand("
                        SELECT COUNT(*) FROM (
                            SELECT `spdm_id_sp`
                            FROM `db_sanpham_danhmuc`
                            WHERE 
                            ".$where."
                            GROUP BY `spdm_id_sp`
                        ) tuyen
                        ", []);
                    return $command->queryScalar();
                });
                $more = $more - ($page_size * ($page_index+1));
                return [
                    'html' => $return,
                    'more' => ($more > 0?'Xem thêm '.$more.' laptop':''),
                ]; 
            }           
        } 

        elseif($p == '110001'){ //fiter mới
            $return = '<div class="choosedfilter">';
            $dem = 0;
            $count = count($data);
            $dmsp_neuco = '';
            if($data) foreach ($data as $tt) {
                $dm = Tuyen::_dulieu('danhmuc',$tt);
                $dem += 1;
                if($count == 1){                    
                    $dmsp = Tuyen::_dulieu('danhmuc',$dm['dm_dmsp']);                        
                    $return .= '<a href="/'.$dmsp['dm_link'].'">'.$dm['dm_ten'].'<i class="icontgdd-clearfil"></i></a>';                    
                }else{
                    $return .= '<a href="javascript:;" onclick="RemoveFilter(this, 3, '.$tt.')">'.$dm['dm_ten'].'<i class="icontgdd-clearfil"></i></a>';
                    $dmsp_neuco = $dm['dm_dmsp'];
                }
            }
            if($dem > 1){
                if(!empty($dmsp_neuco)){ //Tức là cái dm này là Thông số, thì phải tìm cái DMSP của nó
                    $dmsp = Tuyen::_dulieu('danhmuc',$dmsp_neuco);                        
                    $return .= '<a class="reset" href="/'.$dmsp['dm_link'].'">Xóa tất cả<i class="icontgdd-clearfil"></i></a>';
                }else{   
                    $return .= '<a class="reset" href="?t">Xóa tất cả<i class="icontgdd-clearfil"></i></a>';
                }
            }

            $return .= '</div>';
            return $return;
        }


        elseif($p == '10011'){ //Load thông số sản phẩm
            $product_id = $_POST['productID'];
            $sanpham = Tuyen::_dulieu('sanpham', $product_id);
            $return = [];
            $return['imgKit'] = $sanpham->sp_images_cover_ts('590x500');
            
            $html = '';
            foreach ($sanpham->sp_thongso_full as $id_nts => $arr_ts) {
                $nts = Tuyen::_dulieu('danhmuc', $id_nts);
                if($nts){
                    $html.= '<li><label>'.$nts['dm_ten'].'</label></li>';
                    foreach ($arr_ts as $id_ts => $arr_gt) {
                        $ts = Tuyen::_dulieu('danhmuc', $id_ts);
                            if($ts){
                                $html.= '<li><span>'.$ts['dm_ten'].'</span>';
                                    foreach($arr_gt as $id_gt) {
                                        $gt = Tuyen::_dulieu('danhmuc', $id_gt);
                                            if($gt){
                                                $html.= '<div>'.$gt['dm_ten'].'</div>';
                                    }       }
                                $html.= '</li>';
                            }
                    }
                }
            }
            $return['spec'] = $html;
          
            Aabc::$app->response->format = \aabc\web\Response::FORMAT_JSON;
            return $return;
        }

        elseif($p == '21001'){ //get album

            // $productID = '';
            // if(isset($_POST['productID'])) $productID = $_POST['productID'];

            // $albumID = '';
            // if(isset($_POST['imageType'])) $albumID = $_POST['imageType'];

            // $sanpham = Tuyen::_dulieu('sanpham', $id, $type)

            return '
            <div class="fotorama" data-auto="false" data-allowfullscreen="true" data-nav="thumbs" data-fit="scaledown" data-thumbwidth="100" data-arrows="true" data-click="false" data-swipe="true">

                <div class="caption_ps" data-thumb="//cdn.tgdd.vn/Products/Images/44/106875/apple-macbook-air-mqd32sa-a-i5-5350u-bac-1-180x125.jpg" data-img="//cdn.tgdd.vn/Products/Images/44/106875/apple-macbook-air-mqd32sa-a-i5-5350u-bac-1-org.jpg" data-picid="784572">
                    
                </div>
                <div class="caption_ps" data-thumb="//cdn.tgdd.vn/Products/Images/44/106875/apple-macbook-air-mqd32sa-a-i5-5350u-bac-2-180x125.jpg" data-img="//cdn.tgdd.vn/Products/Images/44/106875/apple-macbook-air-mqd32sa-a-i5-5350u-bac-2-org.jpg" data-picid="784573">
                    
                </div>
                <div class="caption_ps" data-thumb="//cdn.tgdd.vn/Products/Images/44/106875/apple-macbook-air-mqd32sa-a-i5-5350u-bac-3-180x125.jpg" data-img="//cdn.tgdd.vn/Products/Images/44/106875/apple-macbook-air-mqd32sa-a-i5-5350u-bac-3-org.jpg" data-picid="784574">
                    
                </div>
                <div class="caption_ps" data-thumb="//cdn.tgdd.vn/Products/Images/44/106875/apple-macbook-air-mqd32sa-a-i5-5350u-bac-4-180x125.jpg" data-img="//cdn.tgdd.vn/Products/Images/44/106875/apple-macbook-air-mqd32sa-a-i5-5350u-bac-4-org.jpg" data-picid="784575">
                    
                </div>
                <div class="caption_ps" data-thumb="//cdn.tgdd.vn/Products/Images/44/106875/apple-macbook-air-mqd32sa-a-i5-5350u-bac-5-180x125.jpg" data-img="//cdn.tgdd.vn/Products/Images/44/106875/apple-macbook-air-mqd32sa-a-i5-5350u-bac-5-org.jpg" data-picid="784576">
                    
                </div>
                <div class="caption_ps" data-thumb="//cdn.tgdd.vn/Products/Images/44/106875/apple-macbook-air-mqd32sa-a-i5-5350u-bac-6-180x125.jpg" data-img="//cdn.tgdd.vn/Products/Images/44/106875/apple-macbook-air-mqd32sa-a-i5-5350u-bac-6-org.jpg" data-picid="784577">
                    
                </div>
                <div class="caption_ps" data-thumb="//cdn.tgdd.vn/Products/Images/44/106875/apple-macbook-air-mqd32sa-a-i5-5350u-bac-7-180x125.jpg" data-img="//cdn.tgdd.vn/Products/Images/44/106875/apple-macbook-air-mqd32sa-a-i5-5350u-bac-7-org.jpg" data-picid="784578">
                    
                </div>
                <div class="caption_ps" data-thumb="//cdn.tgdd.vn/Products/Images/44/106875/apple-macbook-air-mqd32sa-a-i5-5350u-bac-8-180x125.jpg" data-img="//cdn.tgdd.vn/Products/Images/44/106875/apple-macbook-air-mqd32sa-a-i5-5350u-bac-8-org.jpg" data-picid="784579">
                    
                </div>
                <div class="caption_ps" data-thumb="//cdn.tgdd.vn/Products/Images/44/106875/apple-macbook-air-mqd32sa-a-i5-5350u-bac-9-180x125.jpg" data-img="//cdn.tgdd.vn/Products/Images/44/106875/apple-macbook-air-mqd32sa-a-i5-5350u-bac-9-org.jpg" data-picid="784580">
                    
                </div>
                <div class="caption_ps" data-thumb="//cdn.tgdd.vn/Products/Images/44/106875/apple-macbook-air-mqd32sa-a-i5-5350u-bac-91-180x125.jpg" data-img="//cdn.tgdd.vn/Products/Images/44/106875/apple-macbook-air-mqd32sa-a-i5-5350u-bac-91-org.jpg" data-picid="784581">
                    
                </div>
                <div class="caption_ps" data-thumb="//cdn.tgdd.vn/Products/Images/44/106875/apple-macbook-air-mqd32sa-a-i5-5350u-bac-92-180x125.jpg" data-img="//cdn.tgdd.vn/Products/Images/44/106875/apple-macbook-air-mqd32sa-a-i5-5350u-bac-92-org.jpg" data-picid="784582">
                    
                </div>
                
            </div>
        

    ';




        }
    }
}
