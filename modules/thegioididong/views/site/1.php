<?php
use aabc\helpers\ArrayHelper;
use common\components\Tuyen;
$slide = Tuyen::_dulieu('module', '11');

?>

<aside class="homebanner">
    <div id="sync1">
        <?php $dem = 0 ?>
        <?php foreach ($slide as $k => $img) { ?>
            <?php $dem += 1; ?>
            <div class="item">
                <a onclick="jQuery.ajax({ url: '//www.thegioididong.com/bannertracking?bid=22090&r='+ (new Date).getTime(),   async: true, cache: false });">
                 
                    <?php if($dem == 0){ ?>
                        <img src='<?= Tuyen::_dulieu('image',$img['background'],'800x300') ?>' alt=''/>
                    <?php }else{ ?>
                         <img class='lazyOwl owl-lazy' data-src='<?= Tuyen::_dulieu('image',$img['background'],'800x300') ?>' alt=''/>
                    <?php } ?>

                    <!-- <img src='<?php // $assetsPrefix?>/png/07_06_2018_10_00_32_hotsale-j6_800-300.png' alt='2018 - JU - Galaxy J6 - Hot sale'/> -->
                </a>
            </div>
        <?php } ?>    
    </div>
    <div id="sync2">
        <?php foreach ($slide as $k => $v) { ?>
            <div class="item">
                <h3>
                    <?= $v['label'] ?>
                </h3>
            </div>
        <?php } ?> 
    </div>
</aside>
