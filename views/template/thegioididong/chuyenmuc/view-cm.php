<?php

use aabc\helpers\Html;
use backend\models\Sanpham;
use backend\models\Sanphamngonngu;
use backend\models\Cauhinh;
use common\components\Tuyen;


$this->title = $model['dm_ten'];
$this->params['description'] = $model['dm_ten'];
?>

<div class="container">
    <section class="bread-crumb">               
        <div class="col-xs-12 padding-0 padding-top-10">
            <ul class="breadcrumb" itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
                <style type="text/css">
                    ul.breadcrumb li {
                        width: auto;
                        float: left;
                        padding: 0 5px 0 0;
                    }
                </style>
                <li class="home">
                    <a itemprop="url" href="#/index.html" title="Trang chủ">
                        <span itemprop="title">Trang chủ</span>
                    </a>
                    <span>
                        <i class="fa fa-angle-right"></i>
                    </span>
                </li>
                
                <li>
                    <strong>
                        <span itemprop="title"><?= $model['dm_ten']?></span>
                    </strong>
                <li>
            </ul>
        </div>  
    </section>

<?php if(!empty($model['dm_background'])){ ?>
   <!--  <section style="margin: 5px 0 10px 0">
        <img src="<?php  //$model['dm_background']?>" />
    </section> -->
<?php } ?>

  <section class="box-cm main_container">

    <div class="pottion">
                
        <div class="category-products products clearfix">

            <section class="products-view products-view-grid clearfix">
                <?php                
                    
                    foreach ($data as $k => $v) { 
                       
                        $sp = Tuyen::_dulieu('sanpham',$v,'array');  
                        $image = explode('-', $sp['sp_images']); 
                        $s_html = '';
                        if(!empty($image['0'])) $s_html = Tuyen::_dulieu('image',$image['0'],'300x300');

                ?>
                    <div class="col-xs-6 col-sm-4 col-md-3 no-padding">
                        <div class="product-box">
                            <div class="product-thumbnail">
                                <div class="product-image-flip">
                                    <a href="/<?= $sp['sp_linkseo']?>" title="<?= $sp['sp_tensp']?>">
                                        <img src="<?= $s_html?>" alt="" class="img-responsive center-block"/>
                                    </a>
                                </div>                                               
                            </div>


                            <div class="product-info">
                                <h3 class="product-name">
                                    <a href="/<?= $sp['sp_linkseo']?>" title="<?= $sp['sp_tensp']?>"><b><?= $sp['sp_tensp']?></b></a>
                                </h3>
                                 <div class="post-time">
                                    <span>
                                        <?= date('d-m-Y', strtotime($sp['sp_ngaytao'])) ?>
                                    </span>
                                </div>
                                <p class="blog-item-summary margin-bottom-5"><?= $sp['sp_motaseo']?></p>
                            </div>


                        </div>
                    </div>   
                <?php } ?>
                <?php // } ?>   
            </section>
        </div>
    </div>

</section>

<style type="text/css">
    .product-image-flip {
        padding: 10px;
    }
</style>

</div>