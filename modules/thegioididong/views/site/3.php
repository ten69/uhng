
<?php
use aabc\helpers\ArrayHelper;
use common\components\Tuyen;
$khuyenmai = Tuyen::_dulieu('module', '13');
?>
<div class="otherpromotebanner">
    <h2>Khuyến mãi tại các website thành viên</h2>
    <div id="cross-owl" class="owl-carousel">

        <?php $dem = 0; ?>      
        <?php foreach ($khuyenmai as $k => $img) { ?>
            <?php $dem += 1; ?>
            <div class="item">
                <a  onclick="jQuery.ajax({ url: '//www.thegioididong.com/bannertracking?bid=21710&r='+ (new Date).getTime(),   async: true, cache: false });">
                    
                    <?php if($dem < 3){ ?>
                        <img class='lazy' data-original='<?= Tuyen::_dulieu('image',$img['background'],'380x145') ?>' width='380' height='145'/>
                    <?php }else{ ?>
                        <img class='lazyOwl' data-src='<?= Tuyen::_dulieu('image',$img['background'],'380x145') ?>' width='380' height='145'/>
                    <?php } ?>
                </a>
            </div>
        <?php } ?>  

    </div>
</div>