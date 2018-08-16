<?php
use aabc\widgets\Menu;
use aabc\helpers\ArrayHelper;
use common\components\Tuyen;
use common\cont\D;
use backend\models\Cauhinh;

// $this->title = $thetieude ;
// $this->params['description'] = $themota;

use frontend\assets\CustomAsset;
$bundle = CustomAsset::register($this);
$assetsPrefix = $this->assetBundles[TempAsset]->baseUrl ;
?>




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
<a href="javascript:More()" class="viewmore">Xem thêm 70 laptop</a>