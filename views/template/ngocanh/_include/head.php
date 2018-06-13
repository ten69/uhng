<?php 
use aabc\helpers\Html;
use backend\models\Cauhinh;
use common\cont\D;
use common\components\Tuyen;


// $thetieude = D::dulieu('cauhinh'.Cauhinh::thetieude,'string');
// $thehauto = D::dulieu('cauhinh'.Cauhinh::thehauto,'string');
// $themota = D::dulieu('cauhinh'.Cauhinh::themota,'string');
// $favicon = D::dulieu('cauhinh'.Cauhinh::favicon,'string');


$thetieude = Tuyen::_dulieu('cauhinh',Cauhinh::thetieude,'string');
$thehauto = Tuyen::_dulieu('cauhinh',Cauhinh::thehauto,'string');
$themota = Tuyen::_dulieu('cauhinh',Cauhinh::themota,'string');
$favicon = Tuyen::_dulieu('cauhinh',Cauhinh::favicon,'string');


?>

<meta charset="<?= Aabc::$app->charset ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php //Html::csrfMetaTags() ?>
<?php //Html::encode($this->title) ?>
<?php //$this->head() ?>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title><?php echo Html::encode($this->title).$thehauto ?></title>

<meta name="description" content="<?= $this->params['description'] ?>">        


<!-- <link rel="canonical" href="index.html"/> -->

<meta name='revisit-after' content='1 days'/>
<meta name="robots" content="noodp,noindex,nofollow"/>

<link rel="icon" href="<?= $favicon?>" type="image/x-icon"/>

<meta property="og:type" content="website">
<meta property="og:title" content="<?php echo Html::encode($this->title).$thehauto ?>">        
<meta property="og:description" content="<?= $this->params['description']?>">
<!-- <meta property="og:url" content="index.html"> -->
<meta property="og:site_name" content="Ngọc Anh PC">
    
<style type="text/css">
	.subcate.gd-menu img{
		max-height: 362px;
	}
</style>
