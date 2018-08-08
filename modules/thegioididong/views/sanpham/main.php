<?php
use aabc\helpers\Html;
use aabc\bootstrap\Nav;
use aabc\bootstrap\NavBar;
use aabc\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use frontend\assets\CustomAsset;
use common\widgets\Alert;
use common\cont\D;
use common\components\Tuyen;

// AppAsset::register($this);

use backend\models\Cauhinh;
$cache = Aabc::$app->dulieu;

$bundle = CustomAsset::register($this);
// $this->registerCssFile($bundle->baseUrl . 'css/tuyen.css', ['depends' => [frontend\assets\CustomAsset::className()]]);
// $bundle = CustomAsset::register($this);
$assetsPrefix = $this->assetBundles[TempAsset]->baseUrl ;
// echo '<pre>';
// print_r($assetsPrefix);
// echo '</pre>';



?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Aabc::$app->language ?>">
<head>    
    <?php echo $this->render('head',['assetsPrefix' => $assetsPrefix]); ?>        
</head>

    <body>          
       
   
          <?php echo $content ?> 
        

         <?php echo $this->render('/_include/header',['assetsPrefix' => $assetsPrefix]); ?>         
        
         <?php echo $this->render('/_include/footer',['assetsPrefix' => $assetsPrefix]); ?>     
    

        <p id="back-top">
            <a href="#top" title="Về Đầu Trang">
                <span></span>
            </a>
        </p>


    
        <script defer="defer" async="async" src="<?= $assetsPrefix?>/js/detail.min.v201807181030.js"></script>
        
       
        <div id="dlding">Đang xử lý, vui lòng đợi trong giây lát...</div>



<?php $this->endBody() ?>
</body>
</html>

<?php $this->endPage() ?>


