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
    <?php echo $this->render('head',['assetsPrefix' => $assetsPrefix]); ?>    
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
        <?php //echo $this->render('1',['assetsPrefix' => $assetsPrefix]); ?> 
        

        <?php echo $content ?>   
        
        <?php //echo $this->render('2',['assetsPrefix' => $assetsPrefix]); ?> 

        <?php //echo $this->render('3',['assetsPrefix' => $assetsPrefix]); ?> 
        
        
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

<!-- <script defer="defer" async="async" src="<?= $assetsPrefix?>/js/home.min.v201806050250.js"></script> -->
<script defer="defer" async="async" src="<?= $assetsPrefix?>/js/cart.min.v201805300950.js"></script>
        

<?php $this->endBody() ?>
</body>
</html>

<?php $this->endPage() ?>


