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
    <meta charset="<?= Aabc::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <?php //Html::encode($this->title) ?>
    <?php //$this->head() ?>
    <meta charset="UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <title>Ngọc Anh PC</title>
        <!-- ================= Page description ================== -->
        <meta name="description" content="">
        <!-- ================= Meta ================== -->
        <meta name="keywords" content="Ngọc Anh PC"/>
        <link rel="canonical" href="index.html"/>
        <meta name='revisit-after' content='1 days'/>
        <meta name="robots" content="noodp,noindex,nofollow"/>
        <!-- ================= Favicon ================== -->
        <link rel="icon" href="/__ngocanh/favicon.png" type="image/x-icon"/>
        <meta property="og:type" content="website">
        <meta property="og:title" content="Ngọc Anh PC">
        <!-- <meta property="og:image" content="png/imagee.png?"> -->
        <!-- <meta property="og:image:secure_url" content="png/c.png?"> -->
        <meta property="og:description" content="">
        <meta property="og:url" content="index.html">
        <meta property="og:site_name" content="Ngọc Anh PC">
        
       
        <style type="text/css">
            header .main-navigation nav .mainmenu .nav-cate ul li.dropdown:hover {
                background-color:#f1f1f1
            }
        </style>

        <!-- <link rel="stylesheet" href="css/boostrap.css">
        <link rel="stylesheet" href="css/font-awesome.min.css">
        <link href='css/base.scss748c.css' rel='stylesheet' type='text/css'/>
        <link href='css/ant-paris.scss748c.css' rel='stylesheet' type='text/css'/>
        <link href='css/bpr-products-module748c.css' rel='stylesheet' type='text/css'/>

     -->
 
         <link rel="stylesheet" href="/css/style.css">
  
</head>
<body class="bg-index">    
<?php $this->beginBody() ?>



        <div id="box-wrapper">
           
            <?php echo $this->render('header'); ?>
           
            <?php //echo $this->render('slide'); ?>
            
            
            <?php //echo $content ?>      
            
            <div class="container">
                <div class="col-md-3" style="height: 500px"></div>
                <div class="col-md-9" style="height: 500px">
                    <h2>Xin Lỗi. Trang này không tồn tại.!</h2> 
                    <p>Quay lại <a href="/">Trang chủ</a></p>   
                </div>
            </div>
            

            <?php //echo $this->render('view-sanpham'); ?>
           
            <?php //echo $this->render('home-chuyenmuc'); ?>

           
            <?php echo $this->render('footer'); ?>
            
        </div>
      
        <script src='/js-home/jquery-2.2.3.min748c.js' type='text/javascript'></script>
        <script src='/js-home/owl.carousel.min748c.js?' type='text/javascript'></script>      
        <script src='/js-home/main748c.js?' type='text/javascript'></script>


        <script>
            $.validate({});
        </script>
        
       

        <?php echo $this->render('menu-mobile'); ?>


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




<script type="text/javascript">
    $('a').attr('href','/');
    $('h3.product-name a').text('Tên Sản phẩm').attr('title','Tên sản phẩm')
    $('.product-image-flip a').attr('title','Tên sản phẩm')
    $('.product-image-flip a img').attr('alt','Tên sản phẩm');

    $('h3.blog-item-name a').text('Bài viết giới thiệu, hướng dẫn').attr('title','Bài viết giới thiệu, hướng dẫn')
    $('.post-time span').html('Đăng bởi <b>NgocAnhPC</b> - 28-2-2018')
    $('.blog-item-summary').text('Nội dung bài viết.....')
</script>

<?php //$this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
