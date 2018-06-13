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
        
       
        <style type="text/css">            
            header .main-navigation nav .mainmenu .nav-cate {
                display: none;
                tuyen:tuyen;
            }
            header .main-navigation nav .mainmenu:hover .nav-cate {
                display: block;
            }
        </style>

      
 
         <link rel="stylesheet" href="/css/style.css">
  
</head>
<body class="bg-index">    
<?php $this->beginBody() ?>



        <div id="box-wrapper">
           
            <?php echo $this->render('/_include/header'); ?>
           
            <?php //echo $this->render('/_include/slide'); ?>
            
            
            <?php echo $content ?>      
            
            <?php //echo $this->render('/_include/dm-list'); ?>

            <?php //echo $this->render('/_include/view-sanpham'); ?>
           
            <?php echo $this->render('/_include/home-chuyenmuc'); ?>

           
            <?php echo $this->render('/_include/footer'); ?>
            
        </div>
      
        <script src='/js-home/jquery-2.2.3.min748c.js' type='text/javascript'></script>
        <script src='/js-home/owl.carousel.min748c.js?' type='text/javascript'></script>      
        <script src='/js-home/main748c.js?' type='text/javascript'></script>

        <link href='/css/ant-paris.scss748c.css?1518100584703' rel='stylesheet' type='text/css'/>

        <script>
            $.validate({});
        </script>
        
       

        <?php echo $this->render('/_include/menu-mobile'); ?>


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



<?php //$this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
