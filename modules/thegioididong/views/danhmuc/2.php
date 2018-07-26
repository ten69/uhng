<?php
use aabc\helpers\ArrayHelper;
use common\components\Tuyen;
$slide = Tuyen::_dulieu('module', '22');
?>

<div id="owl-cate">    
    <?php $dem = 0 ?>
    <?php foreach ($slide as $k => $img) { ?>
        <?php $dem += 1; ?>        
            <a>             
                <?php if($dem < 4){ ?>
                    <img src='<?= Tuyen::_dulieu('image',$img['background'],'595x100') ?>' alt=''/>
                <?php }else{ ?>
                     <img class='lazyOwl owl-lazy' data-src='<?= Tuyen::_dulieu('image',$img['background'],'595x100') ?>' alt=''/>
                <?php } ?>
            </a>        
    <?php } ?>  



</div>

