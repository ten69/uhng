<?php
use aabc\widgets\Menu;
use aabc\helpers\ArrayHelper;
use common\components\Tuyen;
use common\cont\D;
use backend\models\Cauhinh;

$donvitiente = Tuyen::_dulieu('cauhinh', Cauhinh::tientetinhgia);
$donvitiente = $donvitiente['child'][$donvitiente['default']];

// $this->title = $thetieude ;
// $this->params['description'] = $themota;

use frontend\assets\CustomAsset;
$bundle = CustomAsset::register($this);
$assetsPrefix = $this->assetBundles[TempAsset]->baseUrl ;
?>

<div class="choosedfilter">
    
</div>

<?php if(isset($dmsp)){ ?>

<script type="text/javascript">
    var query = {
        Category: 42,
        Manufacture: 0,
        PriceRange: 0,
        Feature: 0,
        Property: 0,
        OrderBy: 0,
        PageSize: 2,
        PageIndex: 0,
        Others: '',
        ClearCache: 0
    };
    var advanceQuery = {
        Category: 42,
        Manufacture: '',
        PriceRange: 0,
        Feature: '',
        Property: <?= $model['dm_id']?>,
        OrderBy: 0,
        PageSize: 3,
        PageIndex: 0,
        Count: 0,
        Others: '',
        ClearCache: 0
    };
    var GL_CATEGORYID = 42;
    var GL_MANUFACTUREID = 0;    
    window.onload = function() {
       ShowResult();
    };
</script>
<?php }else{ ?>
<script type="text/javascript">
    var query = {
        Category: 42,
        Manufacture: 0,
        PriceRange: 0,
        Feature: 0,
        Property: 0,
        OrderBy: 0,
        PageSize: 2,
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
        PageSize: 3,
        PageIndex: 0,
        Count: 0,
        Others: '',
        ClearCache: 0
    };
    var GL_CATEGORYID = 42;
    var GL_MANUFACTUREID = 0;
</script>

<?php } ?>

    <ul class="filter">
        <!--#region Tính năng 2-->
       <!--  <li class="fmanu">
            <span class="criteria">Hãng
            </span>
            <div class="manufacture">
                <button type="button" class="closefilter"><i class="icontgdd-closefilter"></i></button>
                    <label class="all"><i class="icontgdd-checkbox"></i>Tất cả hãng</label>
                                    <aside>
                            <label data-id="203">
                                <a href="/laptop-apple-macbook" class="prevent">
                                    <i class="icontgdd-checkbox"></i>
                                    Macbook - iMac
                                </a>
                            </label>
                            <label data-id="118">
                                <a href="/laptop-dell" class="prevent">
                                    <i class="icontgdd-checkbox"></i>
                                    Dell
                                </a>
                            </label>
                            <label data-id="128">
                                <a href="/laptop-asus" class="prevent">
                                    <i class="icontgdd-checkbox"></i>
                                    Asus
                                </a>
                            </label>
                            <label class="check" data-id="122">
                                <a href="/laptop-hp-compaq" class="prevent">
                                    <i class="icontgdd-checkbox"></i>
                                    HP-Compaq
                                </a>
                            </label>
                    </aside>
                    <aside>
                            <label data-id="119">
                                <a href="/laptop-acer" class="prevent">
                                    <i class="icontgdd-checkbox"></i>
                                    Acer
                                </a>
                            </label>
                            <label data-id="120">
                                <a href="/laptop-lenovo" class="prevent">
                                    <i class="icontgdd-checkbox"></i>
                                    Lenovo
                                </a>
                            </label>
                            <label data-id="1470">
                                <a href="/laptop-lg" class="prevent">
                                    <i class="icontgdd-checkbox"></i>
                                    LG
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
            <span class="criteria">Giá
            </span>
            <div class="listprice">
                <button type="button" class="closefilter"><i class="icontgdd-closefilter"></i></button>
                <label class="all check" data-id="0"><i class="icontgdd-checklist"></i>Tất cả mức giá</label>
                    <label data-id="291">
                        <i class="icontgdd-checklist"></i>
                        <a href="/laptop-hp-compaq?p=tren-25-trieu" class="prevent">
                            Trên 25 triệu
                        </a>
                    </label>
                    <label data-id="13">
                        <i class="icontgdd-checklist"></i>
                        <a href="/laptop-hp-compaq?p=15-25-trieu" class="prevent">
                            15 - 25 triệu
                        </a>
                    </label>
                    <label data-id="12">
                        <i class="icontgdd-checklist"></i>
                        <a href="/laptop-hp-compaq?p=10-15-trieu" class="prevent">
                            10 - 15 triệu
                        </a>
                    </label>
                    <label data-id="11">
                        <i class="icontgdd-checklist"></i>
                        <a href="/laptop-hp-compaq?p=duoi-10-trieu" class="prevent">
                            Dưới 10 triệu
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
        <li>
            <span class="criteria">Ổ cứng
            </span>
            <div class="feature">
                <button type="button" class="closefilter"><i class="icontgdd-closefilter"></i></button>
                <aside class="rowfeature">
                    <aside class="property">
                        <strong>Ổ cứng</strong>
                            <label class="check" data-id="63612">
                                <a href="/laptop-hp-compaq?g=duoi-500-gb" class="prevent">
                                    <i class="icontgdd-checkbox"></i>
                                    Dưới 500 GB
                                </a>
                            </label>
                            <label data-id="63613">
                                <a href="/laptop-hp-compaq?g=tu-500-gb-den-1-tb" class="prevent">
                                    <i class="icontgdd-checkbox"></i>
                                    Từ 500 GB đến 1 TB
                                </a>
                            </label>
                            <label data-id="63614">
                                <a href="/laptop-hp-compaq?g=tren-1-tb" class="prevent">
                                    <i class="icontgdd-checkbox"></i>
                                    Trên 1 TB
                                </a>
                            </label>
                    </aside>
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
        </li> -->
            <?php
                if(!isset($dmsp)) $dmsp = $model['list_thongso'];
            ?>

            <?php if(is_array($dmsp)) foreach ($dmsp as $k => $v) {
                    $thongso = Tuyen::_dulieu('danhmuc', $v); ?>
            <li>
                <span class="criteria"><?= $thongso['dm_ten']?>
                </span>
                <div class="feature">
                    <button type="button" class="closefilter"><i class="icontgdd-closefilter"></i></button>
                    <aside class="rowfeature">
                        <aside class="property">
                            <strong><?= $thongso['dm_ten']?></strong>
                            <?php if(is_array($thongso['list_thongso_con'])) foreach ($thongso['list_thongso_con'] as $k_gt => $id_gt) { ?>
                                 <label data-id="<?= $id_gt?>">
                                    <a href="#12">
                                        <i class="icontgdd-checkbox"></i>                                    
                                        <?php 
                                            $giatri = Tuyen::_dulieu('danhmuc', $id_gt);

                                            echo $giatri['dm_ten'];
                                        ?>
                                    </a>
                                </label>

                            <?php } ?>
                        </aside>
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
            <?php } ?>

            <li>
                <span class="criteria">Sắp xếp
                </span>
                <div class="sortprice">
                    <button type="button" class="closefilter"><i class="icontgdd-closefilter"></i></button>
                    <label class="check" data-id="1"><i class="icontgdd-checklist"></i>Giá thấp đến cao</label>
                    <label data-id="2"><i class="icontgdd-checklist"></i>Giá cao đến thấp</label>                    
                    <p class="doit"></p>
                </div>
            </li>
        <!--#endregion-->
    <!-- <li class="barpage prevent">
        <label data-id="moi-ra-mat"><a href="?s=moi-ra-mat"><i class="icontgdd-checkbox"></i>Mới</a></label>
        <label data-id="tra-gop-0-phan-tram"><a href="?s=tra-gop-0-phan-tram"><i class="icontgdd-checkbox"></i>Trả góp 0%</a></label>
    </li> -->
</ul>

<ul class="homeproduct">

<?php 
   // echo '<pre>';
   // print_r($model['list_thongso']);
   // echo '</pre>';

   // echo '<pre>';
   // print_r($listsp);
   // echo '</pre>';
    if(is_array($model['dm_listsp'])) foreach ($model['dm_listsp'] as $k => $idsp) {        
        echo $this->render('_item',[
            'idsp' => $idsp,
        ]);
    }
?>

</ul>


<!-- Product -->
<!-- <ul class="homeproduct"> -->
    <!-- <li class="feature">
        <a href="https://www.thegioididong.com/laptop/hp-pavilion-x360-ba080tu-3mr79pa">
            <img width="600" height="275" src="<?= $assetsPrefix?>/jpg/hp-pavilion-x360-ba080tu-3mr79pa-mf-1.jpg">
            <div class="props">
                <span class="dotted">RAM 4GB</span>
                <span class="dotted">Ổ cứng 1TB</span>
            </div>
            <h3>HP Pavilion x360 ba080TU i3 7100U (3MR79PA)</h3>
            <div class="price">
                <strong>13.490.000₫</strong>
            </div>
            <div class="ratingresult">
                <i class="icontgdd-ystar"></i>
                <i class="icontgdd-ystar"></i>
                <i class="icontgdd-ystar"></i>
                <i class="icontgdd-hstar"></i>
                <i class="icontgdd-gstar"></i>
                <span>3 đánh giá</span>
            </div>
            <label class="installment">Trả góp 0%</label>
        </a>
    </li> -->
   <!--  <li>
        <div class="stickerlap">
            <img width="30" height="30" data-original="https://cdn.tgdd.vn/ValueIcons/1/FullHD.png" class="lazy" />
            <img width="30" height="30" data-original="https://cdn.tgdd.vn/ValueIcons/1/vo-nhom-copy.png" class="lazy" />
            <img width="30" height="30" data-original="https://cdn.tgdd.vn/ValueIcons/1/nhe-copy.png" class="lazy" />
        </div>
        <a href="https://www.thegioididong.com/laptop/acer-swift-sf314-54-51ql-nxgxzsv001" class=laptop>
            <img width="180" height="180" src="<?= $assetsPrefix?>/jpg/acer-swift-sf314-54-51ql-nxgxzsv001-dai-dien-450x300-1-450x300-450x300-400x400.jpg">
            <div class="props">
                <span class="dotted">RAM 4GB</span>
                <span class="dotted">Ổ cứng 1TB</span>
            </div>
            <h3>Acer Swift SF314 54 51QL i5 8250U (NX.GXZSV.001)</h3>
            <div class="price">
                <strong>16.990.000₫</strong>
            </div>
            <div class="promo noimage">
                <p>
                    Cơ hội tr &#250;ng 38 xe Wave Alpha khi trả g &#243;p Home Credit và <b>4 K.mãi</b>
                    khác
                </p>
            </div>
            <label class="installment">Trả góp 0%</label>
        </a>
    </li>
     -->
<!-- </ul> -->
<a href="javascript:More(1)" class="viewmore"></a>