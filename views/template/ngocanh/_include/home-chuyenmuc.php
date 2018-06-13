<?php 
    use backend\models\Cauhinh;
    use common\components\Tuyen;
?>
<?php
    $searchtop = Tuyen::_dulieu('cauhinh',Cauhinh::searchtop,'array');
    $home_chuyenmuc = Tuyen::_dulieu('cauhinh',Cauhinh::home_chuyenmuc,'string');    

if(1){    
    $data = Tuyen::_dulieu('danhmuc',$home_chuyenmuc,'array');
?>

<section class="awe-section-8">
<section class="section_group_product section-news">
    <div class="container">
        <div class="blogs-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box-shock">
                        <div class="barbox clearfix">
                            <a href="<?= $data['dm_link']?>"><h2 class="titlecate">Kinh nghiệm hay</h2></a>
                            <a href="<?= $data['dm_link']?>" class="viewmoretext">Xem tất cả</a>
                        </div>
                        <div class="list-blogs-link">
                            <div class="section_blogs_owl owl-carousel owl-theme not-dqowl">                               
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
                                                <article class="blog-item">
                                                    <div class="blog-item-thumbnail">
                                                        <a href="<?= $sp['sp_linkseo'] ?>">                     
                                                            <img src="<?= $s_html?>" alt="" class="img-responsive center-block"/>
                                                    </a>
                                                </div>
                                                <div class="blog-item-contens">
                                                    <h3 class="blog-item-name margin-top-10">
                                                        <a href="<?= $sp['sp_linkseo'] ?>" title="<?= $sp['sp_tensp'] ?>"><?= $sp['sp_tensp'] ?></a>
                                                    </h3>
                                                    <div class="post-time">
                                                        <span>
                                                            <?= date('d-m-Y', strtotime($sp['sp_ngaytao'])) ?>
                                                       
                                                        </span>
                                                    </div>

                                                    <p class="blog-item-summary margin-bottom-5"><?= $sp['sp_motaseo']?></p>
                                                </div>
                                                </article>
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
</section>
</section>


<?php } ?>


<div class="aabcweb-product-reviews-module"></div>

<div class="search-more">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <label>Tìm kiếm nhiều: </label>
                <?php if(is_array($searchtop)) foreach ($searchtop as $k => $v) { ?>
                    <a href="<?= $v['l']?>">• <?= $v['k']?></a>    
                <?php } ?>                
            </div>
        </div>
    </div>
</div>