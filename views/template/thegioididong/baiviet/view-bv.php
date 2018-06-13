<?php

use aabc\helpers\Html;
use backend\models\Sanpham;
use backend\models\Sanphamngonngu;
use backend\models\Cauhinh;
use common\components\Tuyen;
    

$sp_hotro = Tuyen::_dulieu('cauhinh',Cauhinh::sp_hotro,'string');
$hotline = Tuyen::_dulieu('cauhinh',Cauhinh::hotline,'string');


$sp = Tuyen::_dulieu('sanpham',$model[Sanpham::sp_id],'array');

$this->title = $data[Sanphamngonngu::spnn_tieudeseo] ;

$this->params['description'] = $data[Sanphamngonngu::spnn_motaseo];
$this->params['breadcrumbs'][] = $this->title;
?>

<section class="bread-crumb">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <ul class="breadcrumb" itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
                    <li class="home">
                        <a itemprop="url" href="/" title="Trang chủ">
                            <span itemprop="title">Trang chủ</span>
                        </a>
                        <span>
                            <i class="fa fa-angle-right"></i>
                        </span>
                    </li>                    
                    <li>
                        <strong>
                            <span itemprop="title"><?= $data[Sanphamngonngu::spnn_ten]?></span>
                        </strong>
                    <li>
                </ul>
            </div>
        </div>
    </div>
</section>


 <section class="product" itemscope itemtype="http://schema.org/Product">
    <meta itemprop="url" content="">
    <meta itemprop="image" content="">
    <meta itemprop="description" content="">
    <meta itemprop="name" content="<?= $data[Sanphamngonngu::spnn_ten]?>">
    <?php //echo $content ?>
    <div class="container">
        <div class="row ">
            <div class="details-product clearfix">
                <div class="col-lg-8 col-md-8 col-xs-12">
                    <div class="row">
                    	<div class="col-md-12">
                    		<h1 class="title-head"><?= $data[Sanphamngonngu::spnn_ten];?></h1>
                    	</div>
                        
                         <div class="col-md-12 text-right">          
                            <div class="social-media" data-permalink="<?= $sp['sp_linkseo']?>">
                                <label>Chia sẻ: </label>
                                <a target="_blank" href="http://www.facebook.com/sharer.php?u=<?= $sp['sp_linkseo']?>" class="share-facebook" title="Chia sẻ lên Facebook">
                                    <i class="fa fa-facebook-official"></i>
                                </a>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12 ">
                            <div class="well product-well">
                                <div class="row ababa">
                                    <div class="col-md-12">
                                       <?= $data[Sanphamngonngu::spnn_noidung]?>
                                    </div>
                                </div>                                
                            </div>                            
                        </div>

                        <style type="text/css">
                            .product-well .row.ababa{
                                height: auto !important;
                            }
                        </style>

                        
                        
                       

                    </div>
                </div>

                <div class="col-lg-4 col-md-4 col-xs-12">
                    <div class="product-sidebar-ant-fashion">
                        <div class="box-hotlines clearfix hidden-sm hidden-xs">
                            <div class="box-hotlines-left">
                                <h3><b>Bạn cần hỗ trợ?</b></h3>
                                
                                <div><?= $sp_hotro ?></div>

                                <h2>
                                    <a href="tel:<?= $hotline?>"><?= $hotline?></a>
                                </h2>                               
                            </div>
                            <div class="box-hotlines-right">
                                <a href="tel:<?= $hotline?>">
                                    <i class="fa fa-phone" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                       

                        <div class="product_preview">
                        <?= $data[Sanphamngonngu::spnn_noidungbosung]?>

                    	</div>


                    	<!-- <div class="product_preview">
                            <div class="recently-viewed-products">
                                <div class="title_section_center">
                                    <h2 class="title">Tin tức khác</h2>
                                </div>
                                
                                <div class="clearfix list-border clone-item" id="owl-demo-daxem">
                                    <div class="product-item style-list-review" id="recently-viewed-products"><div class="item recently-item-pro clearfix"><div class="wrp"><div class="box-image"><a class="image url-product" href="#/android-tivi-sony-4k-49-inch-kd-49x7500e" title="Android Tivi Sony 4K 49 inch KD-49X7500E"><img class="image-review" src="../jpg/tivi-sony-kd-49x7500e-1-orgb630-5.jpg" alt="Android Tivi Sony 4K 49 inch KD-49X7500E"></a></div><div class="info"><h3><a href="#/android-tivi-sony-4k-49-inch-kd-49x7500e" title="Android Tivi Sony 4K 49 inch KD-49X7500E" class="url-product"><span class="title-review">Android Tivi Sony 4K 49 inch KD-49X7500E</span></a></h3></div></div></div></div>
                                </div>

                            </div>
                        </div> -->


                    </div>
                </div>
            </div>
        </div>
    </div>

</section>


<!-- 
<div class="product-page-relative">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="related-product collections-container">
                    <div class="feature_category_title text-center">
                        <h2 class="title-head text-center">Sản phẩm cùng loại</h2>
                    </div>
                    <div class="products  owl-carousel owl-theme products-view-grid" data-md-items="6" data-sm-items="4" data-xs-items="2" data-margin="0">
                       

                        <div class="product-box">
                            <div class="product-thumbnail">
                                <div class="product-image-flip">
                                    <a href="#/t" title="Internet Tivi Samsung 49 inch UA49J5200">
                                        
                                            <img src="/__ngocanh/pc/1.png" alt="Internet Tivi Samsung 49 inch UA49J5200" class="img-responsive center-block"/>
                                       
                                    </a>
                                </div>
                                
                            </div>
                            <div class="product-info a-center">
                                <h3 class="product-name">
                                    <a href="#/t" title="Internet Tivi Samsung 49 inch UA49J5200">Internet Tivi Samsung 49 inch UA49J5200</a>
                                </h3>
                                <div class="price-box clearfix">
                                    <div class="special-price">
                                        <span class="price product-price">13.400.000₫</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->


            <script>
           
                $(document).ready(function() {
                    setTimeout(function() {
                        $('.zoomContainer').remove();
                        $('#zoom_01').removeData('elevateZoom');
                        $('#zoom_01').elevateZoom({
                            gallery: 'gallery_01',
                            zoomWindowWidth: 420,
                            zoomWindowHeight: 500,
                            zoomWindowOffetx: 10,
                            easing: true,
                            scrollZoom: true,
                            cursor: 'pointer',
                            galleryActiveClass: 'active',
                            imageCrossfade: true

                        });
                    }, 500);
                    jQuery('.thumb-link').on('click', function(event) {
                        var largeImage = jQuery(this).attr('data-image');
                        var smallImage = jQuery(this).find('img', 0).attr('src');
                        var ez = jQuery('#zoom_01').data('elevateZoom');
                        ez.swaptheimage(largeImage, largeImage);
                        jQuery('.large-image > .zoomWrapper > img').attr('data-zoom-image', jQuery(this).attr('data-lightbox-image'));
                        event.stopPropagation();
                        event.preventDefault();
                    });
                    jQuery("#gallery_01").owlCarousel({
                        loop: false,
                        margin: 10,
                        responsiveClass: true,
                        dots: false,
                        nav: true,
                        items: 4,
                        responsive: {
                            0: {
                                items: 4
                            },
                            600: {
                                items: 4
                            },
                            1000: {
                                items: 4
                            }
                        }
                    });
                });

            </script>