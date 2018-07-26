<?php
use aabc\helpers\ArrayHelper;
use common\components\Tuyen;
$model = Tuyen::_dulieu('module', '23');

?>

<div class="catetag">       
    <?php foreach ($model as $k => $v) { ?>    
        <a href="#<?= $k?>">
            <?= $v['label']?> 
        </a>  
    <?php } ?>               
       
</div>

