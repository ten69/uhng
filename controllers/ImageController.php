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


class ImageController extends Controller
{
    
    public function behaviors()
    {
        return [

        	// [
	        // 	'class' => 'aabc\filters\PageCache',
	        //     'only' => ['index'],
	        //     'duration' => 60,
	        //     'variations' => [
	        //         \Aabc::$app->language,
	        //     ],
         //    ],

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
        // echo ($a .'.'. $b);
        if(isset($_GET['i'])){
            
            $img = $_GET['i'];            
            $width = $_GET['w'];
            $height = $_GET['h'];

            // echo $img;
            // die;
            $ex = substr($img, strlen($img) - 3, strlen($img));
            $name = substr($img,0, strlen($img) - 4);
            // $fileOut = $name.'.'.$ex.'-'.$width.'x'.$height.'.'.$ex;
            $fileOut = 'uploads/' . $img;
            // echo $fileOut;die;
            // http://localhost/imgthumbnail/index.php?i=img/2.jpg&w=30&h=30
            // echo $fileOut;die;
            $path = $fileOut;
            if (file_exists($fileOut)) {
                $info = getimagesize($path);
                $size = array($info[0], $info[1]);
                header("Content-Type: image/jpeg");
                if ($info['mime'] == 'image/png') {
                    $src = imagecreatefrompng($path);       
                } 
                if ($info['mime'] == 'image/jpeg') {
                    $src = imagecreatefromjpeg($path);      
                }
                if ($info['mime'] == 'image/gif') {
                    $src = imagecreatefromgif($path);       
                }   

                $thumb = imagecreatetruecolor($width, $height);

                $src_aspect = $size[0] / $size[1];
                $thumb_aspect = $width / $height;

                if ($src_aspect < $thumb_aspect) {

                    $scale = $width / $size[0];
                    $new_size = array($width, $width / $src_aspect);
                    $src_post = array(0, ($size[1] * $scale - $height) / $scale / 2);

                } else if ($src_aspect > $thumb_aspect) {

                    $scale = $width / $size[1];
                    $new_size = array($height * $src_aspect, $height);
                    $src_post = array(($size[0] * $scale - $width) / $scale / 2, 0);

                } else {
                    $new_size = array($width, $height);
                    $src_post = array(0, 0);
                }

                $new_size[0] = max($new_size[0], 1);
                $new_size[1] = max($new_size[1], 1);

                imagecopyresampled($thumb, $src, 0, 0, $src_post[0], $src_post[1], $new_size[0], $new_size[1], $size[0], $size[1]);

                // if($save === false){
                    // header('Content-Type: image/jpeg');
                    return imagepng($thumb);
                // } else {
                //     return imagepng($thumb, $save);
                // }





                // $imageInfo = getimagesize($fileOut);
                // switch ($imageInfo[2]) {
                //     case IMAGETYPE_JPEG:
                //         header("Content-Type: image/jpeg");
                //         break;
                //     case IMAGETYPE_GIF:
                //         header("Content-Type: image/gif");
                //         break;
                //     case IMAGETYPE_PNG:
                //         header("Content-Type: image/png");
                //         break;
                //    default:
                //         break;
                // }
                // header('Content-Length: ' . filesize($fileOut));
                // readfile($fileOut);
                die;
            }
            header("Content-Type: image/png");
            die;  
        }else{
            die;
        };
        
        
              
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
}
