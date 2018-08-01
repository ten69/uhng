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



<?php echo $this->render('2',[
    'assetsPrefix' => $assetsPrefix,
    'model' => $model,
]); ?>   


<ul class="homeproduct">

<?php 
   // echo '<pre>';
   // print_r($model['list_thongso']);
   // echo '</pre>';

   // echo '<pre>';
   // print_r($listsp);
   // echo '</pre>';
    if(is_array($model['dm_listsp'])) foreach ($model['dm_listsp'] as $k => $idsp) {
        $sanpham = Tuyen::_dulieu('sanpham', $idsp);
        if($sanpham){ 
            $img = Tuyen::_dulieu('image',$sanpham['sp_images'],'180x180');

    ?>            
            <li>
                <a href="#123">
                    <img width="180" height="180" src="<?= $img?>">
                    <h3><?= $sanpham['sp_tensp']?></h3>
                    <div class="price">
                        <strong>
                        <?php if(is_numeric($sanpham['sp_gia'])){ 
                            echo number_format($sanpham['sp_gia']) .' '. $donvitiente;
                        }else{
                            echo $sanpham['sp_gia'];
                        } ?>
                        </strong>
                    </div>
                    <div class="ratingresult">
                        <i class="icontgdd-ystar"></i>
                        <i class="icontgdd-ystar"></i>
                        <i class="icontgdd-ystar"></i>
                        <i class="icontgdd-ystar"></i>
                        <i class="icontgdd-ystar"></i>
                        <span>33 đánh giá</span>
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

<?php        }
    }
?>

</ul>
<!--  <li class="feature">
    <a href="https://www.thegioididong.com/dtdd/iphone-x-256gb">
        <img width="600" height="275" src="<?php // $assetsPrefix?>/jpg/iphone-x-256gb-14.jpg">
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
        <img width="180" height="180" src="<?php // $assetsPrefix?>/jpg/iphone-x-256gb-silver-400x400.jpg">
        <h3>iPhone X 256GB Silver</h3>
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
        <div class="promo noimage">
            <p>
                Giảm 3 triệu thanh to &#225;n online bằng thẻ Mastercard và <b>2 K.mãi</b>
                khác
            </p>
        </div>
        <label class="discount">GIẢM 2.000.000₫</label>
    </a>
</li> -->