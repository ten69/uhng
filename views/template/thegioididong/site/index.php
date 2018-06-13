<?php
use aabc\widgets\Menu;
use aabc\helpers\ArrayHelper;
use common\components\Tuyen;

use backend\models\Cauhinh;
$cache = Aabc::$app->dulieu;

$thetieude = json_decode($cache->get('cauhinh'.Cauhinh::thetieude),true);
$themota = json_decode($cache->get('cauhinh'.Cauhinh::themota),true);

$this->title = $thetieude ;
$this->params['description'] = $themota;
?>

<style type="text/css">
    .shopping-cart{
        width: 50px;
        height: 50px;
        background: #0FF;
        padding: 10px;
        border-radius: 35px;
    }
    .shopping-cart path{
        fill: #F00;
    }
</style>

<?php
    
    for ($i = 0; $i < 20; $i++) {
        echo Tuyen::_icon('shopping-cart');
    }
    


    // echo '<div class="shopping-cart">'.file_get_contents("svg/shopping-cart748c.svg").'</div>';
    

    if ($this->beginCache('maincontent')) {
?> 
     <?php 
        // if(isset($_GET['type'])){
        //     $sp_id = '1';
        //     if(isset($_GET['id'])) $sp_id = $_GET['id'];

        //     $settings = Aabc::$app->settings;
        
        //     $_Sanpham = 'backend\models\Sanpham';
        //     $model = $_Sanpham::find()->asArray()->all();
        //     echo '<pre>';
        //     // print_r($model);
        //     for ($i=0; $i < 20; $i++) {   
        //         foreach ($model as $v) {
        //             $m = $_Sanpham::find()->andWhere(['sp_id' => $v['sp_id']])->asArray()->one();

        //             $_Sanphamdanhmuc = 'backend\models\Sanphamdanhmuc';
        //             $spdm = $_Sanphamdanhmuc::find()->andWhere(['spdm_id_sp' => $v['sp_id']])->asArray()->all();
        //             if($spdm){
        //                 $list_dm = ArrayHelper::getColumn($spdm, 'spdm_id_danhmuc');   
        //                 // print_r($list_dm);
        //                 // die;
        //                 $arr = [
        //                     'list_dm' => $list_dm,
        //                 ];
        //                 // print_r($arr);
        //                 $m += $arr;
        //             }

        //             print_r(($m));
        //         }
        //     }
            

        //     echo '</pre>';
        // }else{

        //     echo 'TUYEN';

        //     $t = Aabc::$app->settings;
        //     $tuyen = $t->get('tuyen');
            
        //     // $sp_id = '1';
        //     // if(isset($_GET['id'])) $sp_id = $_GET['id'];
        //     // echo 's_130p_'.$sp_id;
        //     // $sp = $t->get('s_130p_'.$sp_id);
        //     // $sp = json_decode($sp);
        //     // echo '<pre>';
        //     // print_r($sp);
        //     // echo '</pre>';

        //     $sp_all = $t->get('sp_all');
        //     $sp_all = json_decode($sp_all);
        //     echo '<pre>';
        //     for ($i=0; $i < 20; $i++) {                 
        //         foreach ($sp_all as $v) {
        //             $sp = $t->get('sp_'.$v);
        //             $sp = json_decode($sp);
        //            // print_r($sp);
        //         }            
        //     }
        //     unset($sp_all);
        //     echo '</pre>';


        //     $tuyen = json_decode($tuyen,true);
        //     // echo '<pre>';
        //     // print_r(($t->get('thongtin2')));
        //     $jsonstring = $t->get('menu');
        //     $op = [
        //         'options' => [
        //                 'class' => 'navbar-nav nav',
        //                 'id'=>'navbar-id',
        //                 // 'data-tag'=>'yii2-menu',
        //             ],
        //     ];
        //     $submenu = [
        //         'submenuTemplate' => "\n<ul class='dropdown-menu' role='menu'>\n{items}\n</ul>\n",
        //     ];

        //     $jsonstring = json_decode($jsonstring,true);
        //     // echo '<pre>';
        //     // print_r($jsonstring);
        //     // echo '</pre>';
            
        //     // echo Menu::widget($jsonstring);
        //     echo Menu::widget($tuyen);
        // }
        // echo Menu::widget(json_decode($jsonstring,true)); 

         // Aabc::$app->settings->clearCache();
    ?> 
    cache main
<?php
    $this->endCache();
    }
?>