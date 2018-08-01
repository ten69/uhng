<?php
use aabc\helpers\ArrayHelper;
use common\components\Tuyen;
$model = Tuyen::_dulieu('module', '34');

?>


<div class="laptopmanu">
    	<?php foreach ($model as $k => $v) {    		
    		$img = Tuyen::_dulieu('image',$v['background'],'390x80');
    	?>
    		<a href='#456'>
    		<?php if(!empty($img)){ ?>	        
	            <img src='<?= $img?>' alt='<?= $v['label']?>' />
	        <?php }else{
	        	echo $v['label'];
	        } ?>
	        </a>	

	    <?php 			
	    } ?>
</div>
