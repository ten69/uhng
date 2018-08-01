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
    <?php echo $this->render('/_include/head-laptop',['assetsPrefix' => $assetsPrefix]); ?>        
</head>

    <body>          
       
        <section class="laptopcate ">

            <div class="laptoptop">
               
                <?php echo $this->render('2',['assetsPrefix' => $assetsPrefix]); ?>

                <?php echo $this->render('3',['assetsPrefix' => $assetsPrefix]); ?>

                <div class="clr"></div>
            </div>


            <div class="clr"></div>

            <?php echo $this->render('4',['assetsPrefix' => $assetsPrefix]); ?>
            
            <!-- <div class="laptopprice">
                <h1>Laptop nổi bật nhất</h1>
                <div>
                    Mức giá:
                    <a href="https://www.thegioididong.com/laptop?p=tren-25-trieu">Tr &#234;n 25 triệu
                    </a>
                    <a href="https://www.thegioididong.com/laptop?p=15-25-trieu">15 - 25 triệu
                    </a>
                    <a href="https://www.thegioididong.com/laptop?p=10-15-trieu">10 - 15 triệu
                    </a>
                    <a href="https://www.thegioididong.com/laptop?p=duoi-10-trieu">Dưới 10 triệu
                    </a>
                </div>
            </div> -->

        
             <?php echo $content ?> 

            
            

            <div class="laptopacc">
                <h3>PHẦN MỀM - PHỤ KIỆN LAPTOP</h3>
                <a href="https://www.thegioididong.com/phan-mem?g=diet-virus">
                    <span>
                        <img class="lazy" data-original="<?= $assetsPrefix?>/jpg/diet-virus.jpg" />
                    </span>
                    <h4>Phần mềm diệt virus</h4>
                </a>
                <a href="https://www.thegioididong.com/phan-mem?g=microsoft-office">
                    <span>
                        <img class="lazy" data-original="<?= $assetsPrefix?>/jpg/office-365.jpg" />
                    </span>
                    <h4>Microsoft Office</h4>
                </a>
                <a href="https://www.thegioididong.com/phan-mem?g=windows-10">
                    <span>
                        <img class="lazy" data-original="<?= $assetsPrefix?>/jpg/windows.jpg" />
                    </span>
                    <h4>Windows 10 bản quyền</h4>
                </a>
                <a href="https://www.thegioididong.com/chuot-may-tinh">
                    <span>
                        <img class="lazy" data-original="<?= $assetsPrefix?>/jpg/chuot-ban-phim.jpg" />
                    </span>
                    <h4>Chuột, bàn phím</h4>
                </a>
                <a href="https://www.thegioididong.com/usb">
                    <span>
                        <img class="lazy" data-original="<?= $assetsPrefix?>/jpg/usb.jpg" />
                    </span>
                    <h4>USB</h4>
                </a>
                <a href="https://www.thegioididong.com/tai-nghe">
                    <span>
                        <img class="lazy" data-original="<?= $assetsPrefix?>/jpg/tai-nghe.jpg" />
                    </span>
                    <h4>Tai nghe</h4>
                </a>
                <a href="https://www.thegioididong.com/loa-laptop">
                    <span>
                        <img class="lazy" data-original="<?= $assetsPrefix?>/jpg/loa.jpg" />
                    </span>
                    <h4>Loa</h4>
                </a>
                <a href="https://www.thegioididong.com/phu-kien?key=dan+laptop">
                    <span>
                        <img class="lazy" data-original="<?= $assetsPrefix?>/jpg/mieng-dan-laptop.jpg" />
                    </span>
                    <h4>Miếng dán laptop</h4>
                </a>
            </div>

            <div class="laptopnews">
                <h3>Góc tư vấn chọn mua laptop</h3>
                <div class="left">
                    <iframe width="560" height="315" data-src="https://www.youtube.com/embed/videoseries?list=PLrlFKqadgE9FuBcsI7hkg9KBpyvQ5FSdo" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                </div>
                <div class="right">
                    <ul class="newslist" id="mainlist">
                        <li>
                            <a href="https://www.thegioididong.com/tin-tuc/asus-ra-mat-laptop-gaming-rog-strix-scar-ii-va-strix-hero-ii-1105543">
                                <div class="tempvideo">
                                    <img width="100" height="70" data-original="https://cdn.tgdd.vn/Files/2018/07/31/1105543/asus-ra-mat-laptop-gaming-5_800x524-300x200.jpg" class="lazy" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABAQMAAAAl21bKAAAAA1BMVEX6+vqsEtnpAAAACklEQVQI12NgAAAAAgAB4iG8MwAAAABJRU5ErkJggg==" />
                                </div>
                                <h3>
                                    Asus ra mắt laptop gaming ROG Strix Scar II v &#224;Strix Hero II, gi &#225;từ 44.99 triệu

                                    <span class="lesscom">
                                        <i class="iconnews-comcya"></i>
                                        1
                                    </span>
                                </h3>
                                <figure>Asus đã chính thức ra mắt ROG Strix Scar II và ROG Strix Hero II – bộ đôi laptop chuyên dành cho game thủ tại Việt Nam. Sở hữu phần cứng cực mạnh...
                                </figure>
                                <div class="timepost">
                                    <span>23 giờ trước</span>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="https://www.thegioididong.com/tin-tuc/2-laptop-co-khuyen-mai-khung-gop-mat-back-to-school-1105132">
                                <div class="tempvideo">
                                    <img width="100" height="70" data-original="https://cdn.tgdd.vn/Files/2018/07/29/1105132/hp-pavilion-x360-ba080tu-2_800x450-300x200.jpg" class="lazy" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABAQMAAAAl21bKAAAAA1BMVEX6+vqsEtnpAAAACklEQVQI12NgAAAAAgAB4iG8MwAAAABJRU5ErkJggg==" />
                                </div>
                                <h3>Th &#234;m 2 laptop c &#243;khuyến m &#227;i “khủng” g &#243;p mặt trong chương tr &#236;nh “Back to School”

                                </h3>
                                <figure>Đây đang là thời điểm rất tốt để các bạn học sinh – sinh viên mua laptop phục vụ việc học, khi nhiều mẫu máy đang được giảm giá và đi kèm khuyến...
                                </figure>
                                <div class="timepost">
                                    <span>2 ng &#224;y trước</span>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="https://www.thegioididong.com/tin-tuc/chon-mua-laptop-hp-nao-phu-hop-voi-ban-than-trong-nam-2018-1104841">
                                <div class="tempvideo">
                                    <img width="100" height="70" data-original="https://cdn.tgdd.vn/Files/2018/07/28/1104841/12_800x450-300x200.png" class="lazy" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABAQMAAAAl21bKAAAAA1BMVEX6+vqsEtnpAAAACklEQVQI12NgAAAAAgAB4iG8MwAAAABJRU5ErkJggg==" />
                                </div>
                                <h3>HP Envy 2018 c &#243;g &#236;HOT? Chọn laptop HP n &#224;o tốt v &#224;ph &#249;hợp nhu cầu?

                                </h3>
                                <figure>Năm 2018 hứa hẹn sẽ là một cuộc đua giành cho các hãng laptop tung ra các sản phẩm cao cấp của mình. Tuy có khá nhiều mẫu laptop để người dùng lựa...
                                </figure>
                                <div class="timepost">
                                    <span>3 ng &#224;y trước</span>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="clr"></div>
        </section>
        

        <?php echo $this->render('2',['assetsPrefix' => $assetsPrefix]); ?>   
                    
        <?php echo $this->render('2',['assetsPrefix' => $assetsPrefix]); ?>   
               


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


