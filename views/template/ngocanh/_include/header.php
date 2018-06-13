<?php 
    use backend\models\Cauhinh;
    use common\cont\D;    
    use common\components\Tuyen;
?>

<?php
    // $banner = json_decode($cache->get('cauhinh'.Cauhinh::banner),true);
    $banner = Tuyen::_dulieu('cauhinh',Cauhinh::banner,'string');
    $image_banner = Tuyen::_dulieu('image', $banner,'array');

    $slogan_top = Tuyen::_dulieu('cauhinh',Cauhinh::slogan_top,'string');

    $menu_top = Tuyen::_dulieu('module','1','array');
    $home_slogan = Tuyen::_dulieu('module','3','array');
    $hotrokhachhang = Tuyen::_dulieu('module','4','array'); 
?>


 <header class="header">
<style type="text/css">
    .header-item{

    }
       .header-item h3 {
        
    }
    .header-item p {
        padding: 0;
        margin: 0 0 5px 0;
    }

    .header-pa-item i.fa {
        float: left;
        border-radius: 40px;
        border: 1px solid #da251c;
        padding: 5px;
        position: relative;
        width: 30px;
        height: 30px;
        margin: 11px 0 0 10px;
        color: #da251c;
    }
    .header-pa-item i.fa::before {
        position: absolute;
        top: 7px;
        left: 8px;
    }

    .header-pa-item .header-item{
        float: left;
        margin: 5px 5px 0 5px;
    }
    .top-header-2{
        margin: 0px auto 10px auto;
    }

    .top-header-1{
        border-bottom: 1px solid #ccc;
        margin: 0 0 10px 0;
        padding: 5px 0;
    }
    .title-msg{
        font-size: 13px;
    }
    .policy {
        margin: 30px 0 0 0;
    }
    .header-pa-item{
       
    }
    .header-pa-item a {

    }

</style>

<style type="text/css">
        
    .col-xs-3:hover .hOnline {
        display: block;
    }

    .hotrokhachhang{
            font-size: 14px;
        padding: 0;
        margin: 0;
        color: #FFF;
        background: #ef1c12;
        width: auto;
        text-align: center;
        border-radius: 5px;
    }

    .fa{display:inline-block;font:normal normal normal 14px/1 FontAwesome;font-size:inherit;text-rendering:auto;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;}
    .fa-phone:before{content:"\f095";}
    .fa-envelope:before{content:"\f0e0";}

    a{color:#333;text-decoration:none;}
    a:hover{color:#d00;}
    img{border:none;vertical-align:middle;}
    .clear{clear:both;}
    .txt_u{text-transform:uppercase;}
    img{border:none;}
    .clear{clear:both;}
    #top a{text-decoration:none;color:#474747;font-size:12px;white-space:nowrap;}
    #top b{font-size:12px;}
    .hOnline{
                position: absolute;
            z-index: 999;
            right: 0;
            display: none;
            background: #fff;
            color: #333;
            border-radius: 0 0 3px 3px;
            top: 23px;
            margin: 0 auto;
    }
    .hOnline-order{background-color:#f9fafc;color:#000;text-align:left;width:1200px;border:solid 1px #ccc;font-size:12px;padding:15px 0 0;}
    .online-left{float:left;width:33%;}
    .online-right{float:right;width:33%;}
    .avatar{width:45px;float:left;overflow:hidden;margin-top:10px;}
    .online-content .cssLeft a{display:block;}
    .online-title{border-bottom:1px solid #dee2e5;color:#db0006;font-size:14px;margin-right:25px;padding:5px 0 0;font-weight:700;}
    .online-content{line-height:20px;padding-bottom:10px;}
    .online-content .cssLeft a{float:left;margin-right:5px;}
    .online-content .cssLeft .name{display:block;clear:both;font-weight:700;color:#000;font-size:13px;}
    .online-content .cssLeft{float:left;padding-top:10px;width:96px;}
    .online-content .cssLeft a{color:#d00!important;font-weight:700;font-size:13px!important;}
    .online-content .cssRight{float:left;padding-top:10px;padding-left:10px;width:auto;line-height:22px;}
    .online-content .cssRight i{margin-right:5px;color:#2980b9;font-size:16px;}
    .online-content a{color:#2d94df;text-decoration:none;}
    .online-content a:hover{text-decoration:underline;}
    .online-footer{background-color:#db0006;color:#fff;font-size:13px;padding:2px 10px;text-transform:none;white-space:normal;line-height:18px;}
    .online-footer a{color:#fff;font-weight:700;text-decoration:underline;}
    .online-footer a:hover{text-decoration:none;}
    #support_top:hover .hOnline{display:block;}
    .online-footer a{font-size:13px!important;}
    .avatar img{width:45px;max-height:45px;}
    .online-content .cssLeft img{display:block;}
    .online-content2 a{display:inline-block;width:33%;margin-top:9px;float:left;color:#df0000!important;}
    .online-content2 a img{vertical-align:middle;margin-right:10px;margin-bottom:10px;float:left;}
    .online-content2 span{margin-left:4px;font-size:13px;font-weight:600;display:block;}
    .fa{vertical-align:middle;}
    /*! CSS Used fontfaces */
   /* 
    @font-face{font-family:'FontAwesome';src:url(https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/fonts/fontawesome-webfont.eot?v=4.7.0);src:url(https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/fonts/fontawesome-webfont.eot#iefix&v=4.7.0) format('embedded-opentype'),url(https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/fonts/fontawesome-webfont.woff2?v=4.7.0) format('woff2'),url(https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/fonts/fontawesome-webfont.woff?v=4.7.0) format('woff'),url(https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/fonts/fontawesome-webfont.ttf?v=4.7.0) format('truetype'),url(https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/fonts/fontawesome-webfont.svg?v=4.7.0#fontawesomeregular) format('svg');font-weight:normal;font-style:normal;}*/

</style>


<div class="top-header-1">
    <div class="container">
        <div class="row">
            <div class="col-xs-9">
                <div class="title-msg hidden-xs"><?= $slogan_top?></div>
            </div>
            <div class="col-xs-3">
                <p class="hotrokhachhang">Hỗ trợ khách hàng</p>



                <div id="h_menu_sub_on1" class="h_menu_sub hOnline">
                    <div class="hOnline-order container">
                        <div class="online-footer"> <b>Hỗ trợ khách hàng</b>  </div>
                        <div style="padding:5px 10px;">

                            <?php foreach ($hotrokhachhang as $k => $v) { ?>        
                            <div class="online-left">
                                <div class="online-title"><?= $v['label']?></div>

                                <div class="online-content"> 
                                    <?php if(is_array($v['child'])) foreach ($v['child'] as $k2 => $v2) { ?>                                   
                                    <div class="clearfix">
                                        <div class="cssLeft">
                                         <span class="name"><?= $v2['label']?></span>
                                     </div>                        
                                     <div class="avatar">
                                        <?php $avatar = Tuyen::_dulieu('image',$v2['background'],'75x75'); ?>
                                        <?php if(!empty($avatar)){ ?>
                                        <img src="<?= $avatar ?>" alt="avatar">
                                        <?php } ?>
                                    </div>
                                    <div class="cssRight">                            
                                        <div style="float: left; width: 20px; margin: 0 5px 0 0">
                                            <?= Tuyen::_icon('computer1') ?>
                                        </div>
                                        <?= Tuyen::_show_phone($v2['phone']);?>
                                        <div class="clearfix"></div>
                                        <div style="float: left; width: 20px; margin: 0 5px 0 0">
                                            <?= Tuyen::_icon('computer2') ?>
                                        </div>
                                        <?= Tuyen::_show_phone($v2['zalo']);?><br>
                                    </div>           
                                    </div>

                                    <?php } ?>
                                </div> 
                            </div>

                            <?php } ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


    <div class="container top-header-2">

        <?php 
            //'<img class src="http://nhattin.vn/images/advert/138.jpg">
         ?>

        <button style="background: #ccc;color: #F00;float: left;" type="button" class="navbar-toggle collapsed visible-sm visible-xs" id="trigger-mobile">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

        <div class="row">
            <div class="logo_pc col-md-5">
                <a href="/">                    
                    <?= '<img src="'.$image_banner.'" alt="">' ?>                    
                </a>
            </div>
            <div class="col-md-7">
                <section class="policy hidden-xs hidden-sm">
                    <div class="row">
                       
                        <?php foreach ($home_slogan as $k => $v) {?>
                            <div class="header-pa-item">
                                <a href="#/">
                                    
                                    <div style="float: left; width: 20px; margin: 0 5px 0 0">
                                        <?= Tuyen::_icon($v['icon']) ?>
                                    </div>

                                    <div class="header-item">
                                        <h3><?= $v['label']?></h3>
                                        <?php foreach ($v['child'] as $k2 => $v2) { ?>   
                                            <p><?= $v2['label']?></p>    
                                        <?php } ?>                                        
                                    </div>
                                </a>
                            </div>
                        <?php } ?>
                    </div>
                </section>
            </div>            
        </div>
    </div>
    
    


    <div class="main-navigation">
        <div class="container">

            <?php //echo $this->render('menu'); ?>
            <?php require('menu.php'); ?>

        </div>
    </div>
</header>
