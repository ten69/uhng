<?php
use aabc\helpers\ArrayHelper;
use common\components\Tuyen;
$model = Tuyen::_dulieu('module', '33');
$dem = 1;
?>


<div class="right">
    <div class="twobanner">
    	<?php foreach ($model as $k => $v) {
    		if($dem <3){
    			$img = Tuyen::_dulieu('image',$v['background'],'390x80');
    	?>
    	
    	<?php
    		$url = Tuyen::_show_link($v['url']);
		?>

    		<a href='<?= $url?>'>

    		<?php if(!empty($img)){ ?>	        
	            <img src='<?= $img?>' alt='<?= $v['label']?>' />
	        <?php }else{
	        	echo $v['label'];
	        } ?>
	        </a>	

	    <?php 
			}
	    	$dem++;
	    } ?>
    </div>
</div>