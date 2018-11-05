<?php
use aabc\helpers\ArrayHelper;
use common\components\Tuyen;
$module_23 = Tuyen::_dulieu('module', '23');

?>

<div class="catetag">       
    <?php foreach ($module_23 as $k => $v) { ?>    
        <a href="#<?= $k?>">
            <?= $v['label']?> 
        </a>  
    <?php } ?>               
       
</div>

