<?php 
use backend\models\Sanpham;
use backend\models\Danhmuc;
use backend\models\Cauhinh;
use common\components\Tuyen;

$home_image_dm = Tuyen::_dulieu('cauhinh',Cauhinh::home_image_dm,'string');
$home_danhmuc = Tuyen::_dulieu('cauhinh',Cauhinh::home_danhmuc,'array');

foreach ($home_danhmuc as $k => $v) {   
    $data = Tuyen::_dulieu('danhmuc',$v,'array');   
?>

<section class="awe-section-4">
    <?php if(!empty($home_image_dm[$k]['k'])){ ?>
        <div class="container" style="margin-bottom: 10px;"><img src="<?= $home_image_dm[$k]['k']?>" style=""></div>
    <?php } ?>
    <div class="section_group_product section_group_product_1">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="box-shock">
                        <div class="barbox clearfix">
                            <a href="<?= $data['dm_link']?>"><h2 class="titlecate"><?= $data['dm_ten']?></h2></a>
                            <div class="menu-button-edit">
                                <i class="fa fa-navicon" aria-hidden="true"></i>
                            </div>
                            <ul>                                                                         
                                <li><a href="<?= $data['dm_link']?>" class="viewmoretext">Xem tất cả</a></li>
                            </ul>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="section_tab_product-owl owl-carousel owl-theme not-dqowl">
                                    <?php
                                        if(is_array($data['dm_listsp'])) foreach ($data['dm_listsp'] as $k_sp => $v_sp) { 
                                            if($k_sp < 10){
                                                $sp = Tuyen::_dulieu('sanpham',$v_sp,'array');  
                                                $image = explode('-', $sp['sp_images']); 
                                                $s_html = '';
                                                if(!empty($image['0'])) $s_html = Tuyen::_dulieu('image',$image['0'],'300x300');

                                                if(!empty($sp)){
                                                ?>                                            
                                                <div class="item">
                                                    <div class="product-box">
                                                        <div class="product-thumbnail">
                                                            <!-- <div class="sale-flash">HOT</div> -->
                                                            <div class="product-image-flip">
                                                                <a href="<?= $sp['sp_linkseo'] ?>" title="<?= $sp['sp_tensp'] ?>">        
                                                                        <img src="<?= $s_html?>" alt="" class="img-responsive center-block"/>
                                                                </a>
                                                            </div>                                                
                                                        </div>
                                                        <div class="product-info a-center">
                                                            <h3 class="product-name">
                                                                <a href="<?= $sp['sp_linkseo'] ?>" title="<?= $sp['sp_tensp'] ?>"><?= $sp['sp_tensp'] ?></a>
                                                            </h3>
                                                            <div class="price-box clearfix">
                                                                <div class="special-price">
                                                                   
                                                                    <span class="price product-price"><?= ($sp['sp_gia']) ?></span>
                                                                   
                                                                </div>

                                                                <?php if(!empty($sp['sp_giakhuyenmai'])){?>
                                                                    <div class="old-price">
                                                                        <span class="price product-price-old"><?= ($sp['sp_giakhuyenmai']) ?>₫           
                                                                        </span>
                                                                    </div>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>                                               
                                            <?php } ?>      
                                            <?php } ?>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        // var c_n_s = 0
        function rap(){
            // $('.awe-section-4 .owl-stage .owl-next::before').click()
            // var maxleng = $('.awe-section-4 .owl-stage').width()
            // var full_active =  $('.awe-section-4 .owl-stage-outer').width()
            // var step =  $('.awe-section-4 .owl-stage .active').width()
            // c_n_s = c_n_s + parseInt(step)
            // if(c_n_s > (maxleng - parseInt(full_active))){
            //     c_n_s = 0
            // }
            // console.log(c_n_s)
            // $('.owl-stage').css('transform', 'translate3d(-'+c_n_s+'px, 0px, 0px)')
            
        }

        function stat(){
            // setInterval(rap, 2000);            
        }
 
        // stat();
    </script>

</section>

<?php } ?>
