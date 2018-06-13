<?php 
    use backend\models\Cauhinh;
    use common\components\Tuyen;
    // $cache = Aabc::$app->dulieu; 


    
    // $footer_list = json_decode($cache->get('cauhinh'.Cauhinh::footer_list),true);    
    // $tencongty = json_decode($cache->get('cauhinh'.Cauhinh::tencongty),true);
    // $diachi = json_decode($cache->get('cauhinh'.Cauhinh::diachi),true);
    // $dienthoai = json_decode($cache->get('cauhinh'.Cauhinh::dienthoai),true);
    // $hotline = json_decode($cache->get('cauhinh'.Cauhinh::hotline),true);
    // $email = json_decode($cache->get('cauhinh'.Cauhinh::email),true);

    $footer_list = Tuyen::_dulieu('cauhinh',Cauhinh::footer_list,'array');
    $tencongty   = Tuyen::_dulieu('cauhinh',Cauhinh::tencongty,'string');

    // echo '<pre>';
    // print_r(Cauhinh::tencongty);
    // echo '</pre>';
    // die;

    $diachi = Tuyen::_dulieu('cauhinh',Cauhinh::diachi,'string');
    $dienthoai = Tuyen::_dulieu('cauhinh',Cauhinh::dienthoai,'string');
    // $hotline = Tuyen::_dulieu('cauhinh',Cauhinh::hotline,'array');
    $email = Tuyen::_dulieu('cauhinh',Cauhinh::email,'string');

    // echo '<pre>';
    // print_r($footer_list);
    // echo '</pre>';
?>

<footer class="footer ">
    <div class="site-footer">
        <div class="container">
            <div class="footer-inner padding-top-10 padding-bottom-10">
                <div class="row">

                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <div class="footer-widget">                           
                            <ul class="widget-menu contact-info-page">
                                <li><h4><b><?= $tencongty?></b></h4></li>
                                <li><i class="fa fa-map-marker color-x" aria-hidden="true"></i><?= $diachi?></li>
                                <li><i class="fa fa-phone color-x" aria-hidden="true"></i>
                                    <?php 
                                        $items = preg_split('/[\n]+/', $dienthoai); 
                                        if(is_array($items)) foreach ($items as $k => $v) { 
                                        if($k > 0){echo '-';}
                                    ?>
                                        <a href="tel:<?= $v?>"><?= $v?></a>
                                    <?php        
                                        }
                                    ?>

                                    
                                </li>
                                <li><i class="fa fa-envelope-o" aria-hidden="true"></i><a href="mailto:<?= $email?>"><?= $email?></a></li>
                            </ul>
                        </div>
                    </div>

                    <?php  foreach ($footer_list as $k => $v) { ?>
                    <div class="col-xs-6 col-sm-3 col-md-2 col-lg-2">
                        <div class="footer-widget">
                            <h4>
                                <span><?= $v['a']?></span>
                            </h4>
                            <?php if(is_array($v['c'])){ ?>
                                <ul class="list-menu">
                                    <?php foreach ($v['c'] as $k_c => $v_c) { ?>
                                        <li><a href="<?= $v_c['l']?>"><?= $v_c['k']?></a></li> 
                                    <?php } ?>
                                                                      
                                </ul>
                            <?php } ?>
                        </div>
                    </div>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="copyright clearfix">
        <div class="container">
            <div class="inner clearfix">
                <div class="row">
                    <div class="col-md-12 col-copyright">
                        <span>
                            
                        </span>
                    </div>
                </div>
            </div>
            <div class="back-to-top">
                <i class="fa fa-angle-up" aria-hidden="true"></i>
            </div>
        </div>
    </div>
</footer>