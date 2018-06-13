<?php
use aabc\helpers\Html;
use aabc\bootstrap\Nav;
use aabc\bootstrap\NavBar;
use aabc\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use frontend\assets\CustomAsset;
use aabc\web\View;

$bundle = CustomAsset::register(Aabc::$app->view);
// $this->registerCssFile($bundle->baseUrl . 'css/tuyen.css', ['depends' => [frontend\assets\CustomAsset::className()]]);
// $bundle = CustomAsset::register($this);
$assetsPrefix = $this->assetBundles[TempAsset]->baseUrl ;
echo '<pre>';
print_r($assetsPrefix);
echo '</pre>';

use common\widgets\Alert;
use common\cont\D;

// AppAsset::register($this);

use backend\models\Cauhinh;
$cache = Aabc::$app->dulieu;



// echo '<pre>';
// print_r($slogan_top);
// echo '</pre>';
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Aabc::$app->language ?>">
<head>  
    <?php echo $this->render('/_include/head'); ?>
    <?php //require(D::_include('head')); ?>          
    <style type="text/css">
        header .main-navigation nav .mainmenu .nav-cate ul li.dropdown:hover {
            background-color:#f1f1f1
        }
    </style>
     <link rel="stylesheet" href="/css/style.css">
</head>
<body class="bg-index">    
<?php $this->beginBody() ?>
        <div id="box-wrapper">
           
            <?php echo $this->render('/_include/header'); ?>
            <?php //require(D::_include('header')); ?>

            <?php echo $this->render('/_include/slide'); ?>
            <?php //require(D::_include('slide')); ?>
            
            
            <?php echo $content ?>      
            
            <?php echo $this->render('/_include/home-list'); ?>
            <?php //require(D::_include('home-list')); ?>

            <?php //require(D::_include() .'view-sanpham'); ?>
            <?php echo $this->render('/_include/home-chuyenmuc'); ?>
            <?php //require(D::_include('home-chuyenmuc')); ?>

            <?php echo $this->render('/_include/footer'); ?>
            <?php //require(D::_include('footer')); ?>
            
        </div>
      
        <script src='/js-home/jquery-2.2.3.min748c.js' type='text/javascript'></script>
        <script src='/js-home/owl.carousel.min748c.js' type='text/javascript'></script>      
        <script src='/js-home/main748c.js' type='text/javascript'></script>

        <!-- <script src='/js/api.jquerya87f.js?4' type='text/javascript'></script> -->
        <!-- <script src='/js/owl.carousel.min748c.js?1518100584703' type='text/javascript'></script> -->

        <!-- <link href='/css/base.scss748c.css?1518100584703' rel='stylesheet' type='text/css'/> -->
        
        <link href='/css/ant-paris.scss748c.css?1518100584703' rel='stylesheet' type='text/css'/>
        
       
        <?php echo $this->render('/_include/menu-mobile'); ?>
        <?php //require(D::_include('menu-mobile')); ?>


        <!-- <script src='/js/cs.script748c.js?' type='text/javascript'></script> -->
       
    
   

<?php
    if ($this->beginCache('footer')) {
?>

<?php
        $this->endCache();
    }
?>

<script type="text/javascript">
    $('#menu2017').mouseover(function () {
        $('#drop-back').removeClass('hide');
    })
    $('#menu2017').mouseout(function () {
        $('#drop-back').addClass('hide');
    })
</script>
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

<?php $this->head() ?>
</body>
</html>

<?php 
    // echo '<pre>';
    // print_r($this);
    // echo '</pre>';

    // $start_memory = memory_get_peak_usage();
    // echo (memory_get_peak_usage() - $start_memory)/1024 . 'kb';
    // echo (memory_get_peak_usage()/1024). 'kb';   
?>

<?php $this->endPage() ?>


