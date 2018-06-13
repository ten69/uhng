<?php
namespace frontend\controllers;

use Aabc;
use aabc\base\InvalidParamException;
use aabc\web\BadRequestHttpException;
use aabc\web\Controller;
use aabc\filters\VerbFilter;
use aabc\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use common\cont\D;
use aabc\web\NotFoundHttpException;
use backend\models\Sanpham;

class BaivietController extends Controller
{
    
    public function behaviors()
    {
        return [

          //   [
	        	// 'class' => 'aabc\filters\PageCache',
	         //    'only' => ['index','contact'],
	         //    'duration' => 0,
	         //    'variations' => [
	         //        \Aabc::$app->language,
	         //    ],
          //   ],

            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }


    public function beforeAction($action)
    {
        $this->layout = 'baiviet/main';
        // print_r($action->id);
        // die;
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

    public function actionView($slug = '', $id = '')
    {         
        $type = 2;
            $id = addslashes($id);
            $slug = addslashes($slug);
            
            $Sanphamdanhmuc = Aabc::$app->_model->Sanphamdanhmuc;
            $Sanphamchinhsach = Aabc::$app->_model->Sanphamchinhsach;

            $model = $this->findModel($id);
            
            if($model->sp_status == 2 OR $model->sp_recycle == 1){
                header('Location: /', true,302);
                exit();
            }

            // if($model['sp_type'] == 1) $slug = $slug . '-'.D::url_sp.$id.'.html';
            // if($model['sp_type'] == 2) $slug = $slug . '-'.D::url_bv.$id.'.html';
           
            // echo '<pre>'; 
            // echo $type;       
            // print_r($model->sp_linkseo."\n");
            // print_r($slug);
            // echo '</pre>';
            // die;

            if($model){
                if($slug != $model->sp_linkseo || $type != $model->sp_type){
                    if($model->sp_type == 1) header('Location: /'.$model->sp_linkseo.'-'.D::url_sp.$id.'.html', true,302);
                    if($model->sp_type == 2) header('Location: /'.$model->sp_linkseo.'-'.D::url_bv.$id.'.html', true,302);
                    exit();
                }
            }
           

            $datajson = 0;
            
           
             // $id
            // if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if(1){
                $Sanphamngonngu = Aabc::$app->_model->Sanphamngonngu;

                // $data = Sanpham::getSanphamNgonngus($model)->all();
                $data = Sanpham::getSanphamNgonngus($model)->andWhere(['spnn_idngonngu' => 1])->one();
                // print_r($data);
                // die;

                          //Xử lý link ảnh trước khi hiển thị
                // foreach ($data as $key => $value) {
                //     $data[$key]['spnn_noidung'] = $this->decodelinkanh($data[$key]['spnn_noidung']);
                // }

                //Tìm danh sách các danh mục của sản phẩm.
                $modeldanhmuc = $Sanphamdanhmuc::find()
                                    ->andWhere(['spdm_id_sp' => $id])
                                    ->all();       
                $datadanhmuc = array_column($modeldanhmuc, 'spdm_id_danhmuc');
                $model[Sanpham::sp_id_danhmuc] = $datadanhmuc;


                //Tìm các chính sách (All, hoặc theo chứa nhóm, hoặc chứa sản phẩm này)
                $_Chinhsach  = Aabc::$app->_model->Chinhsach;                
                $chinhsach = $_Chinhsach::find()
                                   ->andWhere([Aabc::$app->_chinhsach->cs_status => '1'])
                                   ->andWhere([Aabc::$app->_chinhsach->cs_recycle => '2'])
                                   ->andWhere([Aabc::$app->_chinhsach->cs_apdungcho => '1'])
                                   ->all();
                $idcstatca = array_column($chinhsach, Aabc::$app->_chinhsach->cs_id); 

                $modeldanhmuc = $Sanphamdanhmuc::find()
                                    ->joinWith('spdmIdDanhmuc',false,'INNER JOIN')    
                                    ->andWhere([Aabc::$app->_danhmuc->dm_type => '1'])
                                    ->andWhere([Aabc::$app->_danhmuc->dm_recycle => '2'])
                                    ->andWhere([Aabc::$app->_danhmuc->dm_status => '1'])
                                    ->andWhere(['spdm_id_sp' => $id])
                                    ->all(); 
                foreach ($modeldanhmuc as $keydm => $valuedm) {
                    $_Danhmucchinhsach  = Aabc::$app->_model->Danhmucchinhsach;                
                    $danhmucchinhsach = $_Danhmucchinhsach::find()
                               ->joinWith('dmcsIdChinhsach','false','INNER JOIN')
                               ->andWhere([Aabc::$app->_chinhsach->cs_status => '1'])
                               ->andWhere([Aabc::$app->_chinhsach->cs_recycle => '2'])
                               ->andWhere([Aabc::$app->_danhmucchinhsach->dmcs_id_danhmuc => $valuedm['spdm_id_danhmuc']])
                               ->all();  
                    foreach ($danhmucchinhsach as $keycs => $valuecs) {
                        array_push($idcstatca,$valuecs[Aabc::$app->_danhmucchinhsach->dmcs_id_chinhsach]);
                    } 
                }   
                $sanphamchinhsach = $Sanphamchinhsach::find()
                                        ->joinWith('spcsIdChinhsach','false','INNER JOIN')
                                        ->andWhere([Aabc::$app->_chinhsach->cs_status => '1'])
                                        ->andWhere([Aabc::$app->_chinhsach->cs_recycle => '2'])
                                        ->andWhere(['spcs_id_sp'  => $id])
                                        ->all();
                foreach ($sanphamchinhsach as $keyspcs => $valuespcs) {
                    array_push($idcstatca,$valuespcs['spcs_id_chinhsach']);
                }
                $model[Sanpham::sp_id_chinhsach] = $idcstatca;

                // $this->layout = Aabc::$app->controller->id. '/main';
                // $this->layout = 'sanpham/main';
               
                $kq = $this->render('view-bv', [
                    'model' => $model,
                    'data' => $data,
                    // 'ngonngu' => $ngonngu->getAllNgonngu(),
                ]);
                
                
                // $kq = Aabc::$app->d->decodeview($kq);
                return $kq;
            }
    }

    // public function actionTuyen($value='')
    // {        
    //     // echo 'tuyen';die;
    //     print_r(Array ('aabc\filters\PageCache'));
    // }
    
    // public function actionIndex()
    // {
    //     return $this->render('index');
    // }  

    protected function findModel($id)
    {
        // $Sanpham = Aabc::$app->_model->Sanpham;
        if (($model = (Sanpham::M)::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
