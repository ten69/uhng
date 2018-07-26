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
            <ul class="filter">
                <li class="fmanu">
                    <h1>Điện thoại:</h1>
                    <a href="https://www.thegioididong.com/dtdd-apple-iphone" data-id="80">iPhone (Apple)</a>
                    <a href="https://www.thegioididong.com/dtdd-samsung" data-id="2">Samsung</a>
                    <a href="https://www.thegioididong.com/dtdd-oppo" data-id="1971">OPPO</a>
                    <span class="criteria">Hãng khác
                    </span>
                    <div class="manufacture">
                        <button type="button" class="closefilter">
                            <i class="icontgdd-closefilter"></i>
                        </button>
                        <label class="all check">
                            <i class="icontgdd-checkbox"></i>
                            Tất cả hãng
                        </label>
                        <aside>
                            <label data-id="80">
                                <a href="https://www.thegioididong.com/dtdd-apple-iphone">
                                    <i class="icontgdd-checkbox"></i>
                                    iPhone (Apple)
                        
                                </a>
                            </label>
                            <label data-id="2">
                                <a href="https://www.thegioididong.com/dtdd-samsung">
                                    <i class="icontgdd-checkbox"></i>
                                    Samsung
                        
                                </a>
                            </label>
                            <label data-id="1971">
                                <a href="https://www.thegioididong.com/dtdd-oppo">
                                    <i class="icontgdd-checkbox"></i>
                                    OPPO
                        
                                </a>
                            </label>
                            <label data-id="3">
                                <a href="https://www.thegioididong.com/dtdd-sony">
                                    <i class="icontgdd-checkbox"></i>
                                    Sony
                        
                                </a>
                            </label>
                            <label data-id="1">
                                <a href="https://www.thegioididong.com/dtdd-nokia">
                                    <i class="icontgdd-checkbox"></i>
                                    Nokia
                        
                                </a>
                            </label>
                            <label data-id="2236">
                                <a href="https://www.thegioididong.com/dtdd-vivo">
                                    <i class="icontgdd-checkbox"></i>
                                    Vivo
                        
                                </a>
                            </label>
                            <label data-id="104">
                                <a href="https://www.thegioididong.com/dtdd-huawei">
                                    <i class="icontgdd-checkbox"></i>
                                    Huawei
                        
                                </a>
                            </label>
                            <label data-id="2235">
                                <a href="https://www.thegioididong.com/dtdd-xiaomi">
                                    <i class="icontgdd-checkbox"></i>
                                    Xiaomi
                        
                                </a>
                            </label>
                        </aside>
                        <aside>
                            <label data-id="4">
                                <a href="https://www.thegioididong.com/dtdd-motorola">
                                    <i class="icontgdd-checkbox"></i>
                                    Motorola
                        
                                </a>
                            </label>
                            <label data-id="111">
                                <a href="https://www.thegioididong.com/dtdd-asus-zenfone">
                                    <i class="icontgdd-checkbox"></i>
                                    Asus (Zenfone)
                        
                                </a>
                            </label>
                            <label data-id="14">
                                <a href="https://www.thegioididong.com/dtdd-htc">
                                    <i class="icontgdd-checkbox"></i>
                                    HTC
                        
                                </a>
                            </label>
                            <label data-id="110">
                                <a href="https://www.thegioididong.com/dtdd-mobiistar">
                                    <i class="icontgdd-checkbox"></i>
                                    Mobiistar
                        
                                </a>
                            </label>
                            <label data-id="19">
                                <a href="https://www.thegioididong.com/dtdd-mobell">
                                    <i class="icontgdd-checkbox"></i>
                                    Mobell
                        
                                </a>
                            </label>
                            <label data-id="27">
                                <a href="https://www.thegioididong.com/dtdd-philips">
                                    <i class="icontgdd-checkbox"></i>
                                    Philips
                        
                                </a>
                            </label>
                            <label data-id="5332">
                                <a href="https://www.thegioididong.com/dtdd-itel">
                                    <i class="icontgdd-checkbox"></i>
                                    Itel
                        
                                </a>
                            </label>
                            <label data-id="2259">
                                <a href="https://www.thegioididong.com/dtdd-bkav">
                                    <i class="icontgdd-checkbox"></i>
                                    BKAV
                        
                                </a>
                            </label>
                        </aside>
                        <p class="doit"></p>
                        <p class="cslder">
                            <span class="cswrap">
                                <span class="csdot"></span>
                                <span class="csdot"></span>
                                <span class="csdot"></span>
                            </span>
                        </p>
                    </div>
                </li>
                <li class="frange">
                    <a href="https://www.thegioididong.com/dtdd?p=duoi-2-trieu" data-id="7">Dưới 2 triệu
                    </a>
                            <a href="https://www.thegioididong.com/dtdd?p=tu-2-4-trieu" data-id="9">Từ 2 - 4 triệu
                    </a>
                            <a href="https://www.thegioididong.com/dtdd?p=tu-4-7-trieu" data-id="289">Từ 4 - 7 triệu
                    </a>
                            <a href="https://www.thegioididong.com/dtdd?p=tu-7-13-trieu" data-id="562">Từ 7 - 13 triệu
                    </a>
                            <a href="https://www.thegioididong.com/dtdd?p=tren-13-trieu" data-id="253">Tr &#234;n 13 triệu
                    </a>
                            <span class="criteria" style="display:none!important">Giá khác
                    </span>
                    <div class="listprice" style="display:none!important">
                        <button type="button" class="closefilter">
                            <i class="icontgdd-closefilter"></i>
                        </button>
                        <label class="all check" data-id="0">
                            <i class="icontgdd-checklist"></i>
                            Tất cả mức giá
                        </label>
                        <label data-id="7">
                            <i class="icontgdd-checklist"></i>
                            <a href="https://www.thegioididong.com/dtdd?p=duoi-2-trieu">Dưới 2 triệu
                    </a>
                        </label>
                        <label data-id="9">
                            <i class="icontgdd-checklist"></i>
                            <a href="https://www.thegioididong.com/dtdd?p=tu-2-4-trieu">Từ 2 - 4 triệu
                    </a>
                        </label>
                        <label data-id="289">
                            <i class="icontgdd-checklist"></i>
                            <a href="https://www.thegioididong.com/dtdd?p=tu-4-7-trieu">Từ 4 - 7 triệu
                    </a>
                        </label>
                        <label data-id="562">
                            <i class="icontgdd-checklist"></i>
                            <a href="https://www.thegioididong.com/dtdd?p=tu-7-13-trieu">Từ 7 - 13 triệu
                    </a>
                        </label>
                        <label data-id="253">
                            <i class="icontgdd-checklist"></i>
                            <a href="https://www.thegioididong.com/dtdd?p=tren-13-trieu">Tr &#234;n 13 triệu
                    </a>
                        </label>
                        <p class="doit"></p>
                        <p class="cslder">
                            <span class="cswrap">
                                <span class="csdot"></span>
                                <span class="csdot"></span>
                                <span class="csdot"></span>
                            </span>
                        </p>
                    </div>
                </li>
                <li class="barpage ">
                    <label data-id="moi-ra-mat">
                        <a href="https://www.thegioididong.com/dtdd?s=moi-ra-mat">
                            <i class="icontgdd-checkbox"></i>
                            Mới
                        </a>
                    </label>
                    <label data-id="tra-gop-0-phan-tram">
                        <a href="https://www.thegioididong.com/dtdd?s=tra-gop-0-phan-tram">
                            <i class="icontgdd-checkbox"></i>
                            Trả góp 0%
                        </a>
                    </label>
                    <label data-id="gia-re-online">
                        <a href="https://www.thegioididong.com/dtdd?s=gia-re-online">
                            <i class="icontgdd-checkbox"></i>
                            Giá rẻ Online
                        </a>
                    </label>
                </li>
                <!--#region Tính năng-->
                <li>
                    <span class="criteria">Tính năng 
                    </span>
                    <div class="feature">
                        <button type="button" class="closefilter">
                            <i class="icontgdd-closefilter"></i>
                        </button>
                        <aside class="rowfeature">
                            <aside class="property">
                                <strong>Loại điện thoại</strong>
                                <label data-id="62879">
                                    <a href="https://www.thegioididong.com/dtdd?g=dien-thoai-pho-thong">
                                        <i class="icontgdd-checkbox"></i>
                                        Điện thoại phổ th &#244;ng
                                
                                    </a>
                                </label>
                                <label data-id="39237">
                                    <a href="https://www.thegioididong.com/dtdd?g=android">
                                        <i class="icontgdd-checkbox"></i>
                                        Android
                                
                                    </a>
                                </label>
                                <label data-id="39238">
                                    <a href="https://www.thegioididong.com/dtdd?g=iphone-ios">
                                        <i class="icontgdd-checkbox"></i>
                                        iPhone (iOS)
                                
                                    </a>
                                </label>
                            </aside>
                            <aside class="property">
                                <strong>Chất liệu vỏ</strong>
                                <label data-id="57279">
                                    <a href="https://www.thegioididong.com/dtdd?g=kim-loai-nguyen-khoi">
                                        <i class="icontgdd-checkbox"></i>
                                        Kim loại nguy &#234;n khối
                                
                                    </a>
                                </label>
                                <label data-id="57280">
                                    <a href="https://www.thegioididong.com/dtdd?g=nhua-va-kim-loai">
                                        <i class="icontgdd-checkbox"></i>
                                        Nhựa v &#224;kim loại
                                
                                    </a>
                                </label>
                                <label data-id="57311">
                                    <a href="https://www.thegioididong.com/dtdd?g=kim-loai-va-kinh-cuong-luc">
                                        <i class="icontgdd-checkbox"></i>
                                        Kim loại v &#224;k &#237;nh cường lực
                                
                                    </a>
                                </label>
                                <label data-id="57278">
                                    <a href="https://www.thegioididong.com/dtdd?g=nhua">
                                        <i class="icontgdd-checkbox"></i>
                                        Nhựa
                                
                                    </a>
                                </label>
                            </aside>
                        </aside>
                        <aside class="rowfeature">
                            <aside class="property">
                                <strong>Camera Sau</strong>
                                <label data-id="54167">
                                    <a href="https://www.thegioididong.com/dtdd?g=duoi-3-mp">
                                        <i class="icontgdd-checkbox"></i>
                                        Dưới 3 MP
                            
                                    </a>
                                </label>
                                <label data-id="54168">
                                    <a href="https://www.thegioididong.com/dtdd?g=tu-3-den-5-mp">
                                        <i class="icontgdd-checkbox"></i>
                                        Từ 3 đến 5 MP
                            
                                    </a>
                                </label>
                                <label data-id="54169">
                                    <a href="https://www.thegioididong.com/dtdd?g=tu-5-den-8-mp">
                                        <i class="icontgdd-checkbox"></i>
                                        Từ 5 đến 8 MP
                            
                                    </a>
                                </label>
                                <label data-id="54170">
                                    <a href="https://www.thegioididong.com/dtdd?g=tu-8-den-12-mp">
                                        <i class="icontgdd-checkbox"></i>
                                        Từ 8 đến 12 MP
                            
                                    </a>
                                </label>
                                <label data-id="54171">
                                    <a href="https://www.thegioididong.com/dtdd?g=tren-12-mp">
                                        <i class="icontgdd-checkbox"></i>
                                        Tr &#234;n 12 MP
                            
                                    </a>
                                </label>
                            </aside>
                            <aside class="yesno">
                                <strong>Tính năng đặc biệt</strong>
                                <label data-id="9009">
                                    <a href="https://www.thegioididong.com/dtdd?f=bao-mat-van-tay">
                                        <i class="icontgdd-checkbox"></i>
                                        Bảo mật v &#226;n tay
                            
                                    </a>
                                </label>
                                <label data-id="13929">
                                    <a href="https://www.thegioididong.com/dtdd?f=man-hinh-tran-vien">
                                        <i class="icontgdd-checkbox"></i>
                                        M &#224;n h &#236;nh tr &#224;n viền
                            
                                    </a>
                                </label>
                                <label data-id="7760">
                                    <a href="https://www.thegioididong.com/dtdd?f=chong-nuoc-bui">
                                        <i class="icontgdd-checkbox"></i>
                                        Chống nước, bụi
                            
                                    </a>
                                </label>
                                <label data-id="6463">
                                    <a href="https://www.thegioididong.com/dtdd?f=ho-tro-4g">
                                        <i class="icontgdd-checkbox"></i>
                                        h &#244;̃ trợ 4G
                            
                                    </a>
                                </label>
                                <label data-id="7819">
                                    <a href="https://www.thegioididong.com/dtdd?f=2-sim">
                                        <i class="icontgdd-checkbox"></i>
                                        2 SIM
                            
                                    </a>
                                </label>
                                <label data-id="10881">
                                    <a href="https://www.thegioididong.com/dtdd?f=3d-touch">
                                        <i class="icontgdd-checkbox"></i>
                                        3D Touch
                            
                                    </a>
                                </label>
                                <label data-id="10882">
                                    <a href="https://www.thegioididong.com/dtdd?f=sac-pin-nhanh">
                                        <i class="icontgdd-checkbox"></i>
                                        Sạc pin nhanh
                            
                                    </a>
                                </label>
                                <label data-id="10883">
                                    <a href="https://www.thegioididong.com/dtdd?f=sac-pin-cho-thiet-bi-khac">
                                        <i class="icontgdd-checkbox"></i>
                                        Sạc pin cho thiết bị kh &#225;c
                            
                                    </a>
                                </label>
                                <label data-id="7759">
                                    <a href="https://www.thegioididong.com/dtdd?f=pin-khung-3000-mah">
                                        <i class="icontgdd-checkbox"></i>
                                        Pin khủng (&gt;3000 mAh)
                            
                                    </a>
                                </label>
                            </aside>
                        </aside>
                        <aside class="rowfeature hide">
                            <aside class="property">
                                <strong>Camera Trước</strong>
                                <label data-id="54172">
                                    <a href="https://www.thegioididong.com/dtdd?g=camera-truoc-duoi-3-mp">
                                        <i class="icontgdd-checkbox"></i>
                                        Dưới 3 MP
                                
                                    </a>
                                </label>
                                <label data-id="54173">
                                    <a href="https://www.thegioididong.com/dtdd?g=camera-truoc-tu-3-den-5-mp">
                                        <i class="icontgdd-checkbox"></i>
                                        Từ 3 đến 5 MP
                                
                                    </a>
                                </label>
                                <label data-id="54174">
                                    <a href="https://www.thegioididong.com/dtdd?g=camera-truoc-tu-5-den-8-mp">
                                        <i class="icontgdd-checkbox"></i>
                                        Từ 5 đến 8 MP
                                
                                    </a>
                                </label>
                                <label data-id="54175">
                                    <a href="https://www.thegioididong.com/dtdd?g=camera-truoc-tren-8-mp">
                                        <i class="icontgdd-checkbox"></i>
                                        Tr &#234;n 8 MP
                                
                                    </a>
                                </label>
                            </aside>
                            <aside class="property">
                                <strong>M &#224;n h &#236;nh</strong>
                                <label data-id="40432">
                                    <a href="https://www.thegioididong.com/dtdd?g=duoi-3">
                                        <i class="icontgdd-checkbox"></i>
                                        Dưới 3 &quot;
                                    </a>
                                </label>
                                <label data-id="40433">
                                    <a href="https://www.thegioididong.com/dtdd?g=khoang-4">
                                        <i class="icontgdd-checkbox"></i>
                                        Khoảng 4 &quot;
                                    </a>
                                </label>
                                <label data-id="40434">
                                    <a href="https://www.thegioididong.com/dtdd?g=khoang-5">
                                        <i class="icontgdd-checkbox"></i>
                                        Khoảng 5 &quot;
                                    </a>
                                </label>
                                <label data-id="40435">
                                    <a href="https://www.thegioididong.com/dtdd?g=khoang-6">
                                        <i class="icontgdd-checkbox"></i>
                                        Khoảng 6 &quot;
                                    </a>
                                </label>
                            </aside>
                        </aside>
                        <button type="button" class="morefeature" onclick="$(this).remove();$('.rowfeature.hide').slideDown().removeClass('hide')">Xem thêm tính năng khác</button>
                        <p class="doit"></p>
                        <p class="cslder">
                            <span class="cswrap">
                                <span class="csdot"></span>
                                <span class="csdot"></span>
                                <span class="csdot"></span>
                            </span>
                        </p>
                    </div>
                </li>
                <!--#endregion-->
            </ul>

            <?php echo $this->render('2',['assetsPrefix' => $assetsPrefix]); ?>   
            

            <!-- Product -->
            <ul class="homeproduct">
                <li class="feature">
                    <a href="https://www.thegioididong.com/dtdd/iphone-x-256gb">
                        <img width="600" height="275" src="<?= $assetsPrefix?>/jpg/iphone-x-256gb-14.jpg">
                        <h3>iPhone X 256GB Gray</h3>
                        <div class="price">
                            <strong>34.790.000₫</strong>
                        </div>
                        <div class="ratingresult">
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <span>33 đánh giá</span>
                        </div>
                        <label class="discount">GIẢM 3.000.000₫</label>
                    </a>
                </li>
                <li>
                    <a href="https://www.thegioididong.com/dtdd/iphone-x-256gb-silver">
                        <img width="180" height="180" src="<?= $assetsPrefix?>/jpg/iphone-x-256gb-silver-400x400.jpg">
                        <h3>iPhone X 256GB Silver</h3>
                        <div class="price">
                            <strong>34.790.000₫</strong>
                        </div>
                        <div class="promo noimage">
                            <p>
                                Giảm 3 triệu thanh to &#225;n online bằng thẻ Mastercard và <b>2 K.mãi</b>
                                khác
                            </p>
                        </div>
                        <label class="discount">GIẢM 2.000.000₫</label>
                    </a>
                </li>
                <li>
                    <a href="https://www.thegioididong.com/dtdd/iphone-x-64gb">
                        <img width="180" height="180" src="<?= $assetsPrefix?>/jpg/iphone-x-64gb-21-400x400.jpg">
                        <h3>iPhone X 64GB Gray</h3>
                        <div class="price">
                            <strong>29.990.000₫</strong>
                        </div>
                        <div class="ratingresult">
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <span>70 đánh giá</span>
                        </div>
                        <div class="promo noimage">
                            <p>
                                Giảm 1 triệu thanh to &#225;n online bằng Mastercard và <b>2 K.mãi</b>
                                khác
                            </p>
                        </div>
                        <label class="discount">GIẢM 3.000.000₫</label>
                    </a>
                </li>
                <li>
                    <a href="https://www.thegioididong.com/dtdd/iphone-x-64gb-silver">
                        <img width="180" height="180" src="<?= $assetsPrefix?>/jpg/iphone-x-64gb-silver-400x400.jpg">
                        <h3>iPhone X 64GB Silver</h3>
                        <div class="price">
                            <strong>29.990.000₫</strong>
                        </div>
                        <div class="ratingresult">
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <span>14 đánh giá</span>
                        </div>
                        <div class="promo noimage">
                            <p>
                                Giảm 1 triệu thanh to &#225;n online bằng Mastercard và <b>2 K.mãi</b>
                                khác
                            </p>
                        </div>
                        <label class="discount">GIẢM 2.000.000₫</label>
                    </a>
                </li>
                <li>
                    <a href="https://www.thegioididong.com/dtdd/iphone-8-plus-do-256gb">
                        <img width="180" height="180" src="<?= $assetsPrefix?>/jpg/iphone-8-plus-do-256gb-400x400.jpg">
                        <h3>iPhone 8 Plus Red 256GB (Đỏ)</h3>
                        <div class="price">
                            <strong>28.790.000₫</strong>
                        </div>
                        <div class="promo noimage">
                            <p>
                                Giảm 500.000đ thanh to &#225;n online bằng Mastercard và <b>2 K.mãi</b>
                                khác
                            </p>
                        </div>
                        <label class="discount">GIẢM 1.000.000₫</label>
                    </a>
                </li>
                <li>
                    <a href="https://www.thegioididong.com/dtdd/iphone-8-256gb">
                        <img width="180" height="180" src="<?= $assetsPrefix?>/jpg/iphone-8-256gb-hh-400x400.jpg">
                        <h3>iPhone 8 256GB</h3>
                        <div class="price">
                            <strong>25.790.000₫</strong>
                        </div>
                        <div class="promo noimage">
                            <p>
                                Giảm 500.000đ thanh to &#225;n online bằng Mastercard và <b>2 K.mãi</b>
                                khác
                            </p>
                        </div>
                        <label class="discount">GIẢM 1.000.000₫</label>
                    </a>
                </li>
                <li>
                    <a href="https://www.thegioididong.com/dtdd/samsung-galaxy-s9-plus-128gb">
                        <img width="180" height="180" src="<?= $assetsPrefix?>/jpg/samsung-galaxy-s9-plus-128gb-600x600-600x600-400x400.jpg">
                        <h3>Samsung Galaxy S9+ 128GB</h3>
                        <div class="price">
                            <strong>24.490.000₫</strong>
                        </div>
                        <div class="ratingresult">
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <span>9 đánh giá</span>
                        </div>
                        <div class="promo noimage">
                            <p>
                                Giảm 1 triệu thanh to &#225;n online bằng Mastercard và <b>4 K.mãi</b>
                                khác
                            </p>
                        </div>
                        <label class="discount">GIẢM 2.000.000₫</label>
                    </a>
                </li>
                <li>
                    <a href="https://www.thegioididong.com/dtdd/iphone-8-plus">
                        <img width="180" height="180" src="<?= $assetsPrefix?>/jpg/iphone-8-plus-hh-400x400.jpg">
                        <h3>iPhone 8 Plus 64GB</h3>
                        <div class="price">
                            <strong>23.990.000₫</strong>
                        </div>
                        <div class="ratingresult">
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <span>90 đánh giá</span>
                        </div>
                        <div class="promo noimage">
                            <p>
                                Giảm 500.000đ thanh to &#225;n online bằng Mastercard và <b>2 K.mãi</b>
                                khác
                            </p>
                        </div>
                        <label class="discount">GIẢM 1.000.000₫</label>
                    </a>
                </li>
                <li>
                    <a href="https://www.thegioididong.com/dtdd/iphone-8-plus-do">
                        <img width="180" height="180" src="<?= $assetsPrefix?>/jpg/iphone-8-plus-red-400x400.jpg">
                        <h3>iPhone 8 Plus Red 64GB (Đỏ)</h3>
                        <div class="price">
                            <strong>23.990.000₫</strong>
                        </div>
                        <div class="ratingresult">
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <span>8 đánh giá</span>
                        </div>
                        <div class="promo noimage">
                            <p>
                                Giảm 500.000đ thanh to &#225;n online bằng Mastercard và <b>2 K.mãi</b>
                                khác
                            </p>
                        </div>
                        <label class="discount">GIẢM 1.000.000₫</label>
                    </a>
                </li>
                <li>
                    <a href="https://www.thegioididong.com/dtdd/samsung-galaxy-s9-plus">
                        <img width="180" height="180" src="<?= $assetsPrefix?>/jpg/samsung-galaxy-s9-plus-64gb-xanh-san-ho-2-1-400x400.jpg">
                        <h3>Samsung Galaxy S9+ 64GB</h3>
                        <div class="price">
                            <strong>23.490.000₫</strong>
                        </div>
                        <div class="ratingresult">
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-hstar"></i>
                            <span>26 đánh giá</span>
                        </div>
                        <div class="promo noimage">
                            <p>
                                Giảm 1 triệu thanh to &#225;n online bằng Mastercard và <b>4 K.mãi</b>
                                khác
                            </p>
                        </div>
                        <label class="discount">GIẢM 2.000.000₫</label>
                    </a>
                </li>
                <li class="feature">
                    <a href="https://www.thegioididong.com/dtdd/samsung-galaxy-s9-plus-64gb-tim">
                        <img width="600" height="275" src="<?= $assetsPrefix?>/jpg/samsung-galaxy-s9-plus-64gb-tim-4.jpg">
                        <h3>Samsung Galaxy S9+ 64GB T &#237;m</h3>
                        <div class="price">
                            <strong>23.490.000₫</strong>
                        </div>
                        <div class="ratingresult">
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-hstar"></i>
                            <span>3 đánh giá</span>
                        </div>
                        <label class="discount">GIẢM 2.000.000₫</label>
                    </a>
                </li>
                <li class="feature">
                    <a href="https://www.thegioididong.com/dtdd/samsung-galaxy-note8">
                        <img width="600" height="275" src="<?= $assetsPrefix?>/jpg/samsung-galaxy-note8-22.jpg">
                        <h3>Samsung Galaxy Note 8</h3>
                        <div class="price">
                            <strong>22.490.000₫</strong>
                        </div>
                        <div class="ratingresult">
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <span>100 đánh giá</span>
                        </div>
                        <label class="discount">GIẢM 2.000.000₫</label>
                    </a>
                </li>
                <li>
                    <a href="https://www.thegioididong.com/dtdd/iphone-7-plus-128gb">
                        <img width="180" height="180" data-original="https://cdn4.tgdd.vn/Products/Images/42/87839/iphone-7-plus-128gb-hh-400x400.jpg" class="lazy">
                        <h3>iPhone 7 Plus 128GB</h3>
                        <div class="price">
                            <strong>22.990.000₫</strong>
                        </div>
                        <div class="ratingresult">
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <span>109 đánh giá</span>
                        </div>
                        <div class="promo noimage">
                            <p>
                                Giảm 500.000đ thanh to &#225;n online bằng Mastercard và <b>2 K.mãi</b>
                                khác
                            </p>
                        </div>
                        <label class="discount">GIẢM 1.000.000₫</label>
                    </a>
                </li>
                <li>
                    <a href="https://www.thegioididong.com/dtdd/iphone-8-64gb">
                        <img width="180" height="180" data-original="https://cdn3.tgdd.vn/Products/Images/42/114113/iphone-8-64gb-hh-400x400.jpg" class="lazy">
                        <h3>iPhone 8 64GB</h3>
                        <div class="price">
                            <strong>20.990.000₫</strong>
                        </div>
                        <div class="ratingresult">
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <span>8 đánh giá</span>
                        </div>
                        <div class="promo noimage">
                            <p>
                                Giảm 500.000đ thanh to &#225;n online bằng Mastercard và <b>2 K.mãi</b>
                                khác
                            </p>
                        </div>
                        <label class="discount">GIẢM 1.000.000₫</label>
                    </a>
                </li>
                <li>
                    <a href="https://www.thegioididong.com/dtdd/iphone-8-do">
                        <img width="180" height="180" data-original="https://cdn.tgdd.vn/Products/Images/42/158730/iphone-8-red-do-2-400x400.jpg" class="lazy">
                        <h3>iPhone 8 Red 64GB (Đỏ)</h3>
                        <div class="price">
                            <strong>20.990.000₫</strong>
                        </div>
                        <div class="promo noimage">
                            <p>
                                Giảm 500.000đ thanh to &#225;n online bằng Mastercard và <b>2 K.mãi</b>
                                khác
                            </p>
                        </div>
                        <label class="discount">GIẢM 1.000.000₫</label>
                    </a>
                </li>
                <li class="feature">
                    <a href="https://www.thegioididong.com/dtdd/oppo-find-x">
                        <img width="600" height="275" data-original="https://cdn4.tgdd.vn/Products/Images/42/165189/Feature/ft-oppo-find-x-6.jpg" class="lazy">
                        <h3>OPPO Find X</h3>
                        <div class="price">
                            <strong>20.990.000₫</strong>
                        </div>
                        <label class="preorder">Đặt trước đến 17/08</label>
                    </a>
                </li>
                <li>
                    <a href="https://www.thegioididong.com/dtdd/iphone-7-plus">
                        <img width="180" height="180" data-original="https://cdn4.tgdd.vn/Products/Images/42/78124/iphone-7-plus-32gb-hh-400x400.jpg" class="lazy">
                        <h3>iPhone 7 Plus 32GB</h3>
                        <div class="price">
                            <strong>19.990.000₫</strong>
                        </div>
                        <div class="ratingresult">
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <span>147 đánh giá</span>
                        </div>
                        <div class="promo noimage">
                            <p>
                                Giảm 500.000đ thanh to &#225;n online bằng Mastercard và <b>1 K.mãi</b>
                                khác
                            </p>
                        </div>
                        <label class="installment">Trả góp 0%</label>
                    </a>
                </li>
                <li>
                    <a href="https://www.thegioididong.com/dtdd/huawei-p20-pro">
                        <img width="180" height="180" data-original="https://cdn.tgdd.vn/Products/Images/42/154685/huawei-p20-pro-2-600x600-600x600-400x400.jpg" class="lazy">
                        <h3>Huawei P20 Pro</h3>
                        <div class="price">
                            <strong>19.990.000₫</strong>
                        </div>
                        <div class="ratingresult">
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <span>10 đánh giá</span>
                        </div>
                        <div class="promo">
                            <img width="30" height="30" data-original="https://cdn4.tgdd.vn/Products/Images/2102/170449/tai-nghe-bluetooth-am61-100x100.jpg" class="lazy">
                            <p>Tặng Tai nghe Bluetooth AM61</p>
                        </div>
                        <label class="installment">Trả góp 0%</label>
                    </a>
                </li>
                <li>
                    <a href="https://www.thegioididong.com/dtdd/sony-xperia-xz2">
                        <img width="180" height="180" data-original="https://cdn4.tgdd.vn/Products/Images/42/146014/sony-xperia-xz2-1-400x400.jpg" class="lazy">
                        <h3>Sony Xperia XZ2</h3>
                        <div class="price">
                            <strong>19.990.000₫</strong>
                        </div>
                        <div class="ratingresult">
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-gstar"></i>
                            <span>61 đánh giá</span>
                        </div>
                        <div class="promo">
                            <img width="30" height="30" data-original="https://cdn2.tgdd.vn/Products/Images/2102/142312/phieu-mua-hang-200000d-1-100x100.jpg" class="lazy">
                            <p>Tặng Phiếu mua h &#224;ng 200.000đ khi mua online</p>
                        </div>
                        <label class="installment">Trả góp 0%</label>
                    </a>
                </li>
                <li>
                    <a href="https://www.thegioididong.com/dtdd/samsung-galaxy-s9-tim">
                        <img width="180" height="180" data-original="https://cdn2.tgdd.vn/Products/Images/42/177047/samsung-galaxy-s9-tim-1-400x400.jpg" class="lazy">
                        <h3>Samsung Galaxy S9 T &#237;m</h3>
                        <div class="price">
                            <strong>19.990.000₫</strong>
                        </div>
                        <div class="ratingresult">
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-hstar"></i>
                            <i class="icontgdd-gstar"></i>
                            <span>7 đánh giá</span>
                        </div>
                        <div class="promo noimage">
                            <p>
                                Giảm 500.000đ thanh to &#225;n online bằng Mastercard và <b>4 K.mãi</b>
                                khác
                            </p>
                        </div>
                        <label class="discount">GIẢM 2.000.000₫</label>
                    </a>
                </li>
                <li>
                    <a href="https://www.thegioididong.com/dtdd/samsung-galaxy-s9">
                        <img width="180" height="180" data-original="https://cdn3.tgdd.vn/Products/Images/42/113263/samsung-galaxy-s9-black-400x400.jpg" class="lazy">
                        <h3>Samsung Galaxy S9</h3>
                        <div class="price">
                            <strong>19.990.000₫</strong>
                        </div>
                        <div class="ratingresult">
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-gstar"></i>
                            <span>21 đánh giá</span>
                        </div>
                        <div class="promo noimage">
                            <p>
                                Giảm 500.000đ thanh to &#225;n online bằng Mastercard và <b>4 K.mãi</b>
                                khác
                            </p>
                        </div>
                        <label class="discount">GIẢM 2.000.000₫</label>
                    </a>
                </li>
                <li class="feature">
                    <a href="https://www.thegioididong.com/dtdd/samsung-galaxy-s8-plus">
                        <img width="600" height="275" data-original="https://cdn1.tgdd.vn/Products/Images/42/91131/Feature/samsung-galaxy-s8-plus-4-1.jpg" class="lazy">
                        <h3>Samsung Galaxy S8 Plus</h3>
                        <div class="price">
                            <strong>17.990.000₫</strong>
                        </div>
                        <div class="ratingresult">
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-hstar"></i>
                            <span>83 đánh giá</span>
                        </div>
                        <label class="discount">GIẢM 1.000.000₫</label>
                    </a>
                </li>
                <li>
                    <a href="https://www.thegioididong.com/dtdd/iphone-7">
                        <img width="180" height="180" data-original="https://cdn.tgdd.vn/Products/Images/42/74110/iphone-7-hh-400x400.jpg" class="lazy">
                        <h3>iPhone 7 32GB</h3>
                        <div class="price">
                            <strong>15.990.000₫</strong>
                        </div>
                        <div class="ratingresult">
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-gstar"></i>
                            <span>106 đánh giá</span>
                        </div>
                        <div class="promo noimage">
                            <p>
                                Giảm 500.000đ thanh to &#225;n online bằng Mastercard và <b>1 K.mãi</b>
                                khác
                            </p>
                        </div>
                        <label class="installment">Trả góp 0%</label>
                    </a>
                </li>
                <li>
                    <a href="https://www.thegioididong.com/dtdd/samsung-galaxy-a8-star">
                        <img width="180" height="180" data-original="https://cdn2.tgdd.vn/Products/Images/42/166247/samsung-galaxy-a8-star-black-400x400.jpg" class="lazy">
                        <h3>Samsung Galaxy A8 Star</h3>
                        <div class="price">
                            <strong>13.990.000₫</strong>
                        </div>
                        <div class="ratingresult">
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-gstar"></i>
                            <span>9 đánh giá</span>
                        </div>
                        <div class="promo">
                            <img width="30" height="30" data-original="https://cdn3.tgdd.vn/Products/Images/2102/113543/phieu-mua-hang-250000d-1-100x100.jpg" class="lazy">
                            <p>
                                Tặng Phiếu mua h &#224;ng 200.000đ và <b>4 K.mãi</b>
                                khác
                            </p>
                        </div>
                        <label class="installment">Trả góp 0%</label>
                    </a>
                </li>
                <li>
                    <a href="https://www.thegioididong.com/dtdd/samsung-galaxy-a8-plus-2018">
                        <img width="180" height="180" data-original="https://cdn.tgdd.vn/Products/Images/42/142465/samsung-galaxy-a8-plus-2018-gold-400x400.jpg" class="lazy">
                        <h3>Samsung Galaxy A8+ (2018)</h3>
                        <div class="price">
                            <strong>13.490.000₫</strong>
                        </div>
                        <div class="ratingresult">
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-ystar"></i>
                            <i class="icontgdd-gstar"></i>
                            <span>88 đánh giá</span>
                        </div>
                        <div class="promo noimage">
                            <p>
                                Giảm 500.000đ thanh to &#225;n online bằng Mastercard và <b>4 K.mãi</b>
                                khác
                            </p>
                        </div>
                        <label class="discount">GIẢM 1.000.000₫</label>
                    </a>
                </li>
            </ul>
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


