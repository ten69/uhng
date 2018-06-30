<?php
use aabc\helpers\Html;
use aabc\bootstrap\Nav;
use aabc\bootstrap\NavBar;
use aabc\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use frontend\assets\CustomAsset;
use common\widgets\Alert;
use common\cont\D;
use common\components\Tuyen;

// AppAsset::register($this);

use backend\models\Cauhinh;
$cache = Aabc::$app->dulieu;

$bundle = CustomAsset::register($this);
// $this->registerCssFile($bundle->baseUrl . 'css/tuyen.css', ['depends' => [frontend\assets\CustomAsset::className()]]);
// $bundle = CustomAsset::register($this);
$assetsPrefix = $this->assetBundles[TempAsset]->baseUrl ;
// echo '<pre>';
// print_r($assetsPrefix);
// echo '</pre>';



?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Aabc::$app->language ?>">
<head>
    <?php echo $this->render('/_include/head',['assetsPrefix' => $assetsPrefix]); ?>    
</head>

<?php 
    echo $_SERVER['HTTP_USER_AGENT'];
?>
<body class="bg-index">   
    <style type="text/css">
        #aabc-debug-toolbar{
            display: none !important;
        }
    </style>
<?php $this->beginBody() ?>
    <?php echo $this->render('/_include/header'); ?> 
    <section>            
        <?php echo $this->render('_home-slide',['assetsPrefix' => $assetsPrefix]); ?> 
        <aside class="homenews">
            <figure>
                <h2>
                    <a href="https://www.thegioididong.com/tin-tuc">Tin công nghệ</a>
                </h2>
                <b></b>
            </figure>
            <ul>
                <li>
                    <a >
                        <img width="100" height="70" src="<?= $assetsPrefix?>/jpg/android-oreo-go-edition-will-also-get-support-from-qualcomm_800x450-100x100.jpg">
                        <h3>Snapdragon 429 v &#224;439 đang ph &#225;t triển, sẽ tối ưu cho Android Go
                </h3>
                        <span>vừa đăng</span>
                    </a>
                </li>
            </ul>
            <div class="twobanner">
                <a>
                    <img src='<?= $assetsPrefix?>/png/07_06_2018_21_29_02_s8-plus-hotsale-398-110.png' alt='2018 - JU - S8+ Hotsale'/>
                </a>
                <a>
                    <img src='<?= $assetsPrefix?>/png/30_05_2018_13_41_31_bigape_398-110.png' alt='2018 - JU - Big Apple'/>
                </a>
            </div>
        </aside>


        <div class="clr"></div>

        <div class="promotebanner">
            <a>
                <img src='<?= $assetsPrefix?>/gif/08_06_2018_10_07_31_1200x75.gif' alt='Hoa nhip bong da'/>
            </a>
        </div>


        <?php echo $content ?>   
        
        <?php echo $this->render('_home_nb',['assetsPrefix' => $assetsPrefix]); ?> 

        <?php echo $this->render('_home_km',['assetsPrefix' => $assetsPrefix]); ?> 
        
        
    </section>

    <?php echo $this->render('/_include/footer',['assetsPrefix' => $assetsPrefix]); ?> 
    

    <p id="back-top">
        <a href="#top" title="Về Đầu Trang">
            <span></span>
        </a>
    </p>
    
    <!-- <script defer="defer" async="async" src="<?= $assetsPrefix?>/"></script> -->
    
    <!--#region CrazyEgg -->
    <!--#endregion -->
    <!--#region Hotjar -->
    <!--#endregion -->
    <div id="dlding">Đang xử lý, vui lòng đợi trong giây lát...</div>

<?php
    if ($this->beginCache('footer')) {
?>

<?php
        $this->endCache();
    }
?>

<style type="text/css">
    #drop-back{
        width: 100%;
        height: 100%;
        background: #00000082;
        position: fixed;
        top: 203px;
        left: 0;
        z-index: 9;
    }
</style>
<div id="drop-back" class="hide"></div>


<?php $this->endBody() ?>
</body>
</html>

<?php $this->endPage() ?>


