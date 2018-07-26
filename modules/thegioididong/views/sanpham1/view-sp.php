<?php

use aabc\helpers\Html;
use backend\models\Sanpham;
use backend\models\Sanphamngonngu;
use backend\models\Cauhinh;

use common\components\Tuyen;

$sp_khuyenmai = Tuyen::_dulieu('cauhinh',Cauhinh::sp_khuyenmai,'string');
$sp_chinhsach = Tuyen::_dulieu('cauhinh',Cauhinh::sp_chinhsach,'string');
$sp_hotro = Tuyen::_dulieu('cauhinh',Cauhinh::sp_hotro,'string');
$hotline = Tuyen::_dulieu('cauhinh',Cauhinh::hotline,'string');




// echo '<pre>';
// print_r($data);
// echo '</pre>';



// echo '<pre>';
// print_r($data);
// echo '</pre>';
// die;


$this->title = $data['spnn_tieudeseo'] ;

$this->params['description'] = $data['spnn_motaseo'];

$this->params['breadcrumbs'][] = $this->title;
?>




 <section class="product" itemscope itemtype="http://schema.org/Product">
    <meta itemprop="url" content="">
    <meta itemprop="image" content="">
    <meta itemprop="description" content="">
    <meta itemprop="name" content="<?= $data['spnn_ten']?>">
    <?php //echo $content ?>
    <div class="container">
        <div class="row ">
            <div class="details-product clearfix">
                <div class="col-lg-8 col-md-8 col-xs-12">
                    <div class="row">
                    	<div class="col-md-12">
                    		<h1 class="title-head"><?= $data['spnn_ten'];?></h1>
                    	</div>

                        <div class="col-md-6">
                            <div id="product">
                                <div class="large-image">
                                    <a href="javascript:void(0);" class="large_image_url checkurl" data-rel="prettyPhoto[product-gallery]">

                                    	<?php
	                                    if(isset($model['sp_images'])){
	                                        $listimg = explode("-",$model['sp_images']);
	                                        $s_html = '';
	                                        $max = 0;
	                                        foreach ($listimg as $key => $value) {
	                                            $_Image = Aabc::$app->_model->Image;
	                                            $img = $_Image::find()->andWhere([Aabc::$app->_image->image_id => $value])->one();	                        
	                                            // if($key > $max){
	                                            if($key == 0){
	                                            	// $max = $key;
		                                            $s_html = '<img id="zoom_01" class="img-responsive center-block" src="/thumb/300/300/'.$img[Aabc::$app->_image->image_tenfile]. '-' . $img[Aabc::$app->_image->image_id]. $img[Aabc::$app->_image->image_morong].'">';
		                                        }
	                                        }   
	                                        echo $s_html;                 
	                                    }
	                                ?>   

                                    </a>                                               
                                </div>
                                <div id="gallery_01" class="owl-carousel owl-theme thumbnail-product abc a2">
	                                <?php
	                                    if(isset($model['sp_images'])){
	                                        $listimg = explode("-",$model['sp_images']);
	                                        foreach ($listimg as $key => $value) {
	                                            $_Image = Aabc::$app->_model->Image;
	                                            $img = $_Image::find()->andWhere([Aabc::$app->_image->image_id => $value])->one();
	                                            echo '<div class="item">';
	                                            echo ' <a href="javascript:void(0);" data-image="/uploads/'.$img[Aabc::$app->_image->image_tenfile]. '-' . $img[Aabc::$app->_image->image_id]. $img[Aabc::$app->_image->image_morong].'" >';

	                                            echo '<img src="/thumb/75/75/'.$img[Aabc::$app->_image->image_tenfile]. '-' . $img[Aabc::$app->_image->image_id]. $img[Aabc::$app->_image->image_morong].'">';
	                                            echo '</a>';

	                                            // echo '<img src="/thumb/408/390/'.$img[Aabc::$app->_image->image_tenfile]. '-' . $img[Aabc::$app->_image->image_id]. $img[Aabc::$app->_image->image_morong].'">';

	                                            echo '</div>';
	                                        }                    
	                                    }
	                                ?>    
                                 
                                </div>


                            </div>
                            <div class="social-sharing">
                                <div class="social-media" data-permalink="android-tivi-sony-4k-49-inch-kd-49x7500e">
                                    <label>Chia sẻ: </label>
                                    <a href="#http://www.facebook.com/sharer.php?u=http://" class="share-facebook" title="Chia sẻ lên Facebook">
                                        <i class="fa fa-facebook-official"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 details-pro">                                        
                            <div class="stock-brand">
                                <ul>
                                    <li class="inventory_quantity" itemscope itemtype="http://schema.org/ItemAvailability">
                                        <span class="stock-brand-title">Tình trạng:</span>
                                        <span class="a-stock" itemprop="supersededBy">Còn hàng</span>
                                    </li>
                                    <!-- <li>
                                        <span class="stock-brand-title">Thương hiệu:</span>
                                        <span class="a-brand">Dell</span>
                                    </li> -->
                                </ul>
                            </div>
                            <div class="price-box" itemscope itemtype="http://schema.org/Offer">
                                <span class="special-price">
                                    <span class="price product-price" itemprop="price"><?= ($model['sp_gia'])?> <?php if(is_numeric($model['sp_gia'])){ echo 'đ'; }?></span>
                                    <meta itemprop="priceCurrency" content="VND">
                                </span>

                                <?php if(is_numeric($model['sp_gia'])){ ?>
                                    <!-- Giá Khuyến mại -->
                                    <span class="old-price">
                                        Giá thị trường:  <del class="price product-price-old" itemprop="priceSpecification"><?= ($model['sp_giakhuyenmai'])?>₫</del>
                                        <meta itemprop="priceCurrency" content="VND">
                                    </span>
                                    <!-- Giás gốc -->
                                    <span class="save-price">
                                        Tiết kiệm:  <span class="price product-price-save"><?= ($model['sp_giakhuyenmai'] - $model['sp_gia'])?>₫</span>
                                    </span>
                                    <!-- Tiết kiệm -->
                                <?php } ?>

                            </div>
                            <div class="form-product">
                               
                                    <div class="box-variant clearfix  hidden ">
                                        <input type="hidden" name="variantId" value="14154614"/>
                                    </div>
                                    <div class="form-group  clearfix">
                                        <a href="tel:<?= $hotline?>">
                                            <button type="submit" class="col-md-12 btn btn-lg btn-gray btn-cart add_to_cart btn_buy add_to_cart" title="Mua ngay">
                                                <span >Gọi ngay <?= $hotline?></span>
                                            </button>
                                        </a>
                                    </div>
                               
                                <div class="clearfix"></div>
                            </div>
                           
                            <div>
                                <?= $data['spnn_noidungbosung']?>

                                <div class="cskm"><?= $sp_khuyenmai?></div>
                                        <div class="cskm"><?= $sp_chinhsach?></div>
                            </div>


                            

                        </div>


                        <div class="col-xs-12 col-sm-12 col-md-12 margin-top-15 margin-bottom-10">
                            <div class="bs-example bs-example-tabs" role="tabpanel" data-example-id="togglable-tabs">
                                <ul id="myTab" class="nav nav-tabs nav-tabs-responsive" role="tablist">
                                    <li role="presentation" class="active">
                                        <a href="#home" id="home-tab" role="tab" data-toggle="tab" aria-controls="home" aria-expanded="true">
                                            <span class="text">Thông tin mô tả</span>
                                        </a>
                                    </li>
                                    <li role="presentation" class="next">
                                        <a href="#profile" role="tab" id="profile-tab" data-toggle="tab" aria-controls="profile">
                                            <span class="text">Cấu hình chi tiết</span>
                                        </a>
                                    </li>
                                </ul>
                                <div id="myTabContent" class="tab-content">
                                    <div role="tabpanel" class="tab-pane fade in active" id="home" aria-labelledby="home-tab">
                                        <div class="well product-well">
                                            <div class="row ababa">
                                                <div class="col-md-12">
                                                   <?= $data['spnn_noidung']?>
                                                </div>
                                            </div>
                                            <a class="btn btn-default btn--view-more">
                                                <span class="more-text">
                                                    Xem thêm <i class="fa fa-chevron-down"></i>
                                                </span>
                                                <span class="less-text">
                                                    Thu gọn <i class="fa fa-chevron-up"></i>
                                                </span>
                                            </a>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane fade" id="profile" aria-labelledby="profile-tab">
                                    	 <?= $data['spnn_noidungbosung_2']?>
                                         
                                    </div>
                                </div>
                            </div>
                        </div>
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
                            
                            <style type="text/css">
                                .cskm a{
                                    color: #337ab7;
                                }
                            </style>
                            <?= $data['spnn_noidungbosung_3']?>

                    	</div>




                    </div>
                </div>
            </div>
        </div>
    </div>

</section>



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