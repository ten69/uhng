<?php 
    use backend\models\Cauhinh;
    use common\components\Tuyen;
    // $cache = Aabc::$app->dulieu;
?>

<?php

    $home_slide = Tuyen::_dulieu('cauhinh',Cauhinh::home_slide, 'array');

    $home_slogan_2 = Tuyen::_dulieu('cauhinh',Cauhinh::home_slogan_2, 'array');

?>
<h1 class="hidden">Ngọc Anh PC </h1>
<section class="awe-section-1">
    <div class="container">
    <div class="col-md-3 no-padding"></div>
    <div class="col-md-9 no-padding">
        <div class="home-slider owl-carousel not-dqowl">
            <?php foreach ($home_slide as $k => $v) { ?>
                <div class="item">           
                    <img src="<?= $v['l']?>" alt="New Collection" class="img-responsive center-block"/>           
                </div>
            <?php } ?> 
           
        </div>
    </div>
    </div>
</section>

<section class="awe-section-2">
    <div class="section_policy">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="owl-policy-mobile owl-carousel not-dqowl">
                        <?php foreach ($home_slogan_2 as $k => $v) { ?>
                            <div class="item">
                                <div class="section_policy_content">
                                    <img src="/svg/policy_image_1748c.svg" alt="<?= $v['k']?>"/>
                                    <div class="section-policy-padding">
                                        <h3><?= $v['k']?></h3>
                                        <div class="section_policy_title"><?= $v['c']?></div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>




