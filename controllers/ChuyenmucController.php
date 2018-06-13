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

class ChuyenmucController extends Controller
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
        $this->layout = 'chuyenmuc/main';
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
        $Danhmuc = Aabc::$app->_model->Danhmuc;

        $cache = Aabc::$app->dulieu; 
        $model = $cache->get('danhmuc'.$id); 

        // $model
        if($model['dm_status'] == 2 OR $model['dm_recycle'] == 1){
            header('Location: /', true,302);
            exit();
        }

        if($model['dm_type'] == 1) $slug = $slug . '-'.D::url_dm.$id.'.html';
        if($model['dm_type'] == 2) $slug = $slug . '-'.D::url_cm.$id.'.html';
        // echo '<pre>';        
        // print_r($model);
        // print_r($slug);
        // echo '</pre>';
        // die;

        if($model){
            if($slug != $model['dm_link'] || $type != $model['dm_type']){
                header('Location: /'.$model['dm_link'], true,302);
                exit();
            }
        }
        // $data = $Danhmuc::getSpdmIdSanphams($model)->column();
        $data = $model['dm_listsp'];
        // echo '<pre>';
        // print_r($data);die;        

        // $this->layout = 'main-dm'; 
       
        $kq = $this->render('view-cm', [
            'model' => $model,
            'data' => $data,                
        ]); 
        
        return $kq;



         
           
            // echo '<pre>';
            // print_r($Danhmuc::find()->andWhere(['dm_id' => $id])->one());
            // die;


            $Sanphamdanhmuc = Aabc::$app->_model->Sanphamdanhmuc;
            $Sanphamchinhsach = Aabc::$app->_model->Sanphamchinhsach;

            $model = $this->findModel($id);
            
            if($model){

            }else{

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

                

                // $this->layout = 'main-dm'; 

                $kq = $this->render('view', [
                    'model' => $model,
                    'data' => $data,
                    // 'ngonngu' => $ngonngu->getAllNgonngu(),
                ]);
                // $kq = Aabc::$app->d->decodeview($kq);
                return $kq;
            }
               
        
    }


    public function actionTuyen($value='')
    {        
        // echo 'tuyen';die;
        print_r(Array ('aabc\filters\PageCache'));
    }
    
    public function actionIndex()
    {

        return $this->render('index');
    }

    
    public function actionLogin()
    {
        if (!Aabc::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Aabc::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    
    public function actionLogout()
    {
        Aabc::$app->user->logout();

        return $this->goHome();
    }

    
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Aabc::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Aabc::$app->params['adminEmail'])) {
                Aabc::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Aabc::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    
    public function actionAbout()
    {
        return $this->render('about');
    }

    
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Aabc::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Aabc::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Aabc::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Aabc::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Aabc::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Aabc::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Aabc::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

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
