<?php
#1
namespace frontend\modules\kehoach\controllers;

use Aabc;
use aabc\web\Controller;
use aabc\helpers\Html;
use aabc\helpers\Url; /*Them*/
use aabc\filters\VerbFilter;



class DefaultController extends Controller
{
    public function getControllerLabel()
    {
        return 'Kế hoạch sản xuất';
    }
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        date_default_timezone_set('asia/ho_chi_minh');
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'get-order' => ['GET'],
                ],
            ],
        ];
    }

    /**
     * Lists all Plan models.
     * @return mixed
     */
    public function actionIndex()
    {
        return 'ok';
    }

   
}
