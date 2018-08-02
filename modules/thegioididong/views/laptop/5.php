<?php
use aabc\helpers\ArrayHelper;
use common\components\Tuyen;
$model = Tuyen::_dulieu('module', '35');


?>

<div class="laptopacc">
    <h3>PHẦN MỀM - PHỤ KIỆN LAPTOP</h3>

    <?php foreach ($model as $k => $v) { ?>    
        <a href="#234">            
            <span>                
                <img class="lazy" data-original="<?= Tuyen::_dulieu('image',$v['background'],'80x80'); ?>" />
            </span>
            <h4><?= $v['label']?></h4>
        </a>
    <?php } ?>  

</div>

