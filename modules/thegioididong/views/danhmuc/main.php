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
    <?php echo $this->render('/_include/head-danhmuc',['assetsPrefix' => $assetsPrefix]); ?>        
</head>

    <body>
        <section class=" ">
            
           
                
                <?php echo $content ?> 

       
            <a href="javascript:More()" class="viewmore">Xem thêm 146 điện thoại</a>
        </section>
        

        <?php echo $this->render('3',['assetsPrefix' => $assetsPrefix]); ?>   
        

            
        <?php echo $this->render('4',['assetsPrefix' => $assetsPrefix]); ?>   
        
        


        <div class="loadingcover">
            <p class="csslder">
                <span class="csswrap">
                    <span class="cssdot"></span>
                    <span class="cssdot"></span>
                    <span class="cssdot"></span>
                </span>
            </p>
        </div>
        

         <?php echo $this->render('/_include/header',['assetsPrefix' => $assetsPrefix]); ?> 
        
        
         <?php echo $this->render('/_include/footer',['assetsPrefix' => $assetsPrefix]); ?>     
    

        <p id="back-top">
            <a href="#top" title="Về Đầu Trang">
                <span></span>
            </a>
        </p>


        <script type="text/javascript">
            var query = {
                Category: 42,
                Manufacture: 0,
                PriceRange: 0,
                Feature: 0,
                Property: 0,
                OrderBy: 0,
                PageSize: 30,
                PageIndex: 0,
                Others: '',
                ClearCache: 0
            };
            var advanceQuery = {
                Category: 42,
                Manufacture: '',
                PriceRange: 0,
                Feature: '',
                Property: '',
                OrderBy: 0,
                PageSize: 28,
                PageIndex: 0,
                Count: 0,
                Others: '',
                ClearCache: 0
            };
            var GL_CATEGORYID = 42;
            var GL_MANUFACTUREID = 0;
        </script>
        <script defer="defer" async="async" src="<?= $assetsPrefix?>/js/category.min.v201805221030.js"></script>
        
       
        <div id="dlding">Đang xử lý, vui lòng đợi trong giây lát...</div>



<?php $this->endBody() ?>
</body>
</html>

<?php $this->endPage() ?>


