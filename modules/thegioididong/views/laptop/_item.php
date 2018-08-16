<?php
use aabc\widgets\Menu;
use aabc\helpers\ArrayHelper;
use common\components\Tuyen;
use common\cont\D;
use backend\models\Cauhinh;

$sanpham = Tuyen::_dulieu('sanpham', $idsp);

if($sanpham){
$img = Tuyen::_dulieu('image',$sanpham['sp_images'],'180x180');

?>

 <li>
    <a href="<?= $sanpham['sp_linkseo']?>">
        
        <img width="180" height="180" src="<?= $img?>" /> 
         <div class="props">
            <span class="dotted">RAM 4GB</span>
            <span class="dotted">Ổ cứng 1TB</span>
        </div>
        <h3><?= $sanpham['sp_tensp']?></h3>
        <div class="price">
            <strong>
            <?php if(is_numeric($sanpham['sp_gia'])){ 
                echo number_format($sanpham['sp_gia']) .' '. Tuyen::_show_donvitiente();
            }else{
                echo $sanpham['sp_gia'];
            } ?>
            </strong>
        </div>                    
        <div class="promo noimage">
            <p>
                Giảm 3 triệu thanh to &#225;n online bằng thẻ Mastercard và <b>2 K.mãi</b>
                khác
            </p>
        </div>
    </a>
</li>

<?php } ?>