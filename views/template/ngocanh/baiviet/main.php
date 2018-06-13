<?php
use aabc\helpers\Html;
use aabc\bootstrap\Nav;
use aabc\bootstrap\NavBar;
use aabc\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;


AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Aabc::$app->language ?>">
<head>
    
    <?php echo $this->render('/_include/head'); ?>

    <?php //include 'head.php';?>
    <?php //require('/../common/config/head'); ?>

        <style type="text/css">
            header .main-navigation nav .mainmenu .nav-cate {
                display: none;
            }
            header .main-navigation nav .mainmenu:hover .nav-cate {
                display: block;
            }
        </style>


        <link rel="stylesheet" href="/css/style-sp.css">

           <link rel="stylesheet" href="css/boostrap.css">
       <!--  <link rel="stylesheet" href="css/font-awesome.min.css">
        <link href='css/base.scss748c.css' rel='stylesheet' type='text/css'/>
        <link href='css/ant-paris.scss748c.css' rel='stylesheet' type='text/css'/>
        <link href='css/bpr-products-module748c.css' rel='stylesheet' type='text/css'/> -->


        <!-- <link rel="stylesheet" href="/css/font-awesome.min.css">  -->
        <link href='/css/base.scss748c.css' rel='stylesheet' type='text/css'/>

        <style type="text/css">            
                .product-well.expanded .row.ababa {
                    height: auto;
                    -webkit-transition: height 0.2s;
                    -moz-transition: height 0.2s;
                    transition: height 0.2s
                }
                .product-well.expanded .less-text {
                    display: block
                }

                .product-well.expanded .more-text {
                    display: none
                }
        </style>

        
        <script src='/js-home/jquery-2.2.3.min748c.js' type='text/javascript'></script>
       
      
    </head>
    <body>
        <div id="box-wrapper">
           
            <?php echo $this->render('/_include/header'); ?>

             <?php echo $this->render('/_include/bread-crumb'); ?>
          

             <?php echo $content ?>  

           
            <?php //echo $this->render('view-sp',['content' => $content]); ?>


            <?php echo $this->render('/_include/home-chuyenmuc'); ?>

            


            <?php echo $this->render('/_include/footer'); ?>




        </div>

 <?php echo $this->render('/_include/menu-mobile'); ?>

        <script src='../js/api.jquerya87f.js?4' type='text/javascript'></script>

        <script src='/js-home/owl.carousel.min748c.js' type='text/javascript'></script>

        <script src='/js/bootstrap.min.js' type='text/javascript'></script>
      
        <link href='/css/ant-paris.scss748c.css?1518100584703' rel='stylesheet' type='text/css'/>

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

        <script src='/js-home/main748c.js' type='text/javascript'></script>
        <!-- <link href='../css/lightbox748c.css' rel='stylesheet' type='text/css'/> -->

        <script src='/js/jquery.elevatezoom308.min748c.js' type='text/javascript'></script>
        <!-- <script src='../js/jquery.prettyphoto.min005e748c.js' type='text/javascript'></script>
        <script src='../js/jquery.prettyphoto.init.min367a748c.js' type='text/javascript'></script> -->
   
<?php //$this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

