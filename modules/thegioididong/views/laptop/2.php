<?php
use aabc\helpers\ArrayHelper;
use common\components\Tuyen;
$model = Tuyen::_dulieu('module', '32');
$dem = 1;
?>

 <div id="owl-laptop">
	<?php foreach ($model as $k => $v) { ?>
	    <?php
    		$url = Tuyen::_show_link($v['url']);
		?>
    		<a href='<?= $url?>'>
    			
	    	<?php if($dem > 2){ ?>
				<img  class='lazyOwl owl-lazy' data-src='<?= Tuyen::_dulieu('image',$v['background'],'768x163')?>' alt='<?= $v['label']?>' />
	    	<?php }else{ ?>
	        	<img src='<?= Tuyen::_dulieu('image',$v['background'],'768x163')?>' alt='<?= $v['label']?>' />
	        <?php } ?>
	    </a> 
	    <?php $dem++; ?> 
    <?php } ?>      

</div>
