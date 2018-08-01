<?php
use aabc\helpers\ArrayHelper;
use common\components\Tuyen;
$model = Tuyen::_dulieu('module', '34');


?>

<div class="plc">
    <ul>    
    <?php foreach ($model as $k => $v) { ?>    
        <li>			
			<div style="width: 36px;float: left;margin: 0 10px 0 0;">
				<?php echo Tuyen::_icon($v['icon']); ?>
			</div>
            <span><?= $v['label']?></span>
        </li>
    <?php } ?>               
    </ul>  
</div>

