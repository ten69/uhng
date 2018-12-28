<?php
use aabc\widgets\Menu;
use aabc\helpers\ArrayHelper;
use common\components\Tuyen;
use aabc\helpers\Html;
// use frontend\models\SanphamFront;

use backend\models\Cauhinh;
$cache = Aabc::$app->dulieu;
$thetieude = json_decode($cache->get('cauhinh'.Cauhinh::thetieude),true);
$themota = json_decode($cache->get('cauhinh'.Cauhinh::themota),true);


$this->title = $thetieude;
$this->params['description'] = $themota;

use frontend\assets\CustomAsset;
$bundle = CustomAsset::register($this);
$assetsPrefix = $this->assetBundles[TempAsset]->baseUrl ;

// echo '<pre>';
// print_r($cart);
// echo '</pre>';
$session = Aabc::$app->session;
?>


<section id="wrap_cart">
    <div class="bar-top">
        <a href="/" class="buymore">Mua thêm sản phẩm khác</a>
        <div class="yourcart">Giỏ hàng của bạn</div>
    </div>
    <div class="wrap_cart">
        <div class="loading-cart" style="display: none;">
            <span class="cswrap">
                <span class="csdot"></span>
                <span class="csdot"></span>
                <span class="csdot"></span>
            </span>
        </div>
        <!-- <form id="formtest" novalidate="novalidate"> -->
            <style>
                #showonehour[value=true] ~ .area_other .area_address .onehour {
		            display: block !important;
		        }
		    </style>

            <?php if(count($cart) == 0){ ?>
                <style type="text/css">
                    .null_cart {
                        display: block;
                        overflow: hidden;
                        width: 490px;
                        margin: auto;
                        text-align: center;
                        font-size: 16px;
                        color: #666;
                        margin-top: 100px;
                        /*transform: translate(0,50%);
                        -webkit-transform: translate(0,50%);
                        -moz-transform: translate(0,50%);
                        -o-transform: translate(0,50%);
                        -ms-transform: translate(0,50%);*/
                        height: 240px;
                    }
                    .iconnull {
                        background-position: 0 -25px;
                        width: 70px;
                        height: 54px;
                        display: block;
                        margin: 10px auto;
                    }
                    .iconnoti {    
                        background-size: 80px 85px;
                        width: 20px;
                        height: 20px;
                        vertical-align: middle;
                        display: inline-block;
                    }
                    .buyother {
                        display: block;
                        overflow: hidden;
                        background: #fff;
                        line-height: 40px;
                        text-align: center;
                        margin: 15px auto;
                        width: 300px;
                        font-size: 14px;
                        color: #288ad6;
                        font-weight: 600;
                        text-transform: uppercase;
                        border: 1px solid #288ad6;
                        border-radius: 4px;
                    }

                    


                </style>

                <div class="null_cart">
                    <i class="iconnoti iconnull"></i>
                    Không có sản phẩm nào  trong giỏ hàng
                    <a href="/" class="buyother">Về trang chủ</a>                    
                </div>
            <?php }else{ ?>
                
                <style type="text/css">
                    .vat {
                        text-align: right;
                        font-size: 12px;
                    }

                    .vat span{
                        color: #c10017;
                    }
                </style>
                <div class="detail_cart">   
                    <form id="order_sp" method="POST">             
                    <input id="form-token" type="hidden" name="<?= Aabc::$app->request->csrfParam?>"           value="<?= Aabc::$app->request->csrfToken?>"/>

                    <ul class="listorder">
    					<?php

                        // echo '<pre>';
                        // print_r($cart);
                        // echo '</pre>';
                        $cart_success = [];
                        

                        // $cart_success = [
                        //     'san_pham' => [
                        //         0 => [
                        //             'ten' => 'IPhone x 64GB',
                        //             'anh' => 'http://tyahytnh.com',
                        //             'don_gia' => '1200000',
                        //             'so_luong' => 4,
                        //         ],
                        //     ],
                        //     'tong_gia' => 12000000,
                        // ];

                        

                        // echo '<pre>';
                        // print_r($cart);
                        // echo '</pre>';

                        $all_total_price = 0; 
                        if(is_array($cart)) foreach ($cart as $k => $item) {
    						$sp = Tuyen::_dulieu('sanpham', $item['sanpham']);
    						if($sp){
                                $total_price = $sp->sp_gia;	
                                $vat = 0;		
                                if(empty($sp->sp_vat)){
                                    $vat = (((int)$sp->sp_vat_value / 100) * $total_price) ;
                                    $total_price += $vat;
                                }
    					?> 
    						<li class=" justadded" data-value="106875" id="item106875" data-num="4">
                                <input type="hidden" name="cart[<?= $k?>][sanpham]" value="<?= $sp->sp_id?>">
    	                        <div class="colimg">
    	                            <a href="<?= $sp->sp_linkseo?>">
                                        <?php $hinh_anh = $sp->sp_images_cover('75x75'); ?>
    	                                <img width="55" src="<?= $hinh_anh ?>"></a>
                                        <?php $cart_success['san_pham'][$k]['anh'] = $hinh_anh; ?>

    	                            <button type="button" class="delete"><span></span>Xóa</button>
    	                        </div>
    	                        <div class="colinfo">
    	                            <strong><?= $item['gia']; ?></strong>
    	                            <a href="<?= $sp->sp_linkseo?>"><?= $sp->sp_tensp?></a>
                                        <?php $cart_success['san_pham'][$k]['ten'] = $sp->sp_tensp; ?>

                                    <p class="vat">
                                        <?php 
                                            if(!empty($sp->sp_vat)){
                                                echo $sp->sp_vat_label;
                                            }else{
                                                echo 'VAT '.$sp->sp_vat_value.'%: <span>+'.number_format($vat).Tuyen::_show_donvitiente().'</span>';
                                            }
                                        ?>                                        
                                    </p>

    								<div class="promotion  webnote">
    	                            <?php foreach ($sp->sp_khuyenmai as $idkm) { 
    	                            	$km = Tuyen::_dulieu('khuyenmai', $idkm);
    	                            	if($km){    	                            		
    	                            ?>
    	                                <span class=""><?= $km['cs_ten']?></span>
    	                                <div class="discout">
                                            <?php
                                                if($km['cs_typetyle'] == 1){
                                                    if(is_numeric($total_price)) $total_price -= (int)$km['cs_tylechietkhau'];

                                                    echo Tuyen::_show_gia_discount($km['cs_tylechietkhau'],'-');
                                                }
                                                elseif($km['cs_typetyle'] == 2){
                                                    echo '('.Tuyen::_show_gia_discount($km['cs_tylechietkhau'],'-',2) .')';                                          
                                                    $discount = (int)$km['cs_tylechietkhau'] / 100 * (int)$sp->sp_gia;
                                                    if(is_numeric($total_price)) $total_price -= $discount;
                                                    echo '<div style="padding: 0 2px"></div>'.Tuyen::_show_gia_discount($discount, '-') ;
                                                }
                                            ?>                                                
                                        </div>
    	                                <div style="clear: both"></div>
    								<?php } } ?>	                            
    	                            </div>

                                    <style type="text/css">
                                        .cart-pb-i span {
                                            font-size: 12px;
                                        }

                                        .cart-pb-i select {
                                            font-size: 12px;
                                            height: 28px !important;
                                        }
                                    </style>

    	                            <div class="cart-pb">	
    	                            	<style type="text/css">
    	                            		option{font-size: 12px}
    	                            	</style>		                        
        			                    <?php 
        			                    	$select_pb = $item['thongso'];
        			                        foreach ($sp['sp_phienban'] as $k_pb => $pb) {
        			                        	$giamgia = 0;
        			                            echo '<div class="cart-pb-i" style="padding:0 0 10px 0;">';
        			                            echo '<span>'.$pb['title'].'</span>';
        			                            echo '<select class="cart_e" name="cart['.$k.'][thongso]['.$k_pb.']" style="margin: 0 0 0 10px;">';
        			                            if(is_array($pb['option'])) foreach ($pb['option'] as $k_op => $op) {
        			                            	if(isset($select_pb[$k_pb]) && $k_op == $select_pb[$k_pb]){
        			                            		if(is_numeric($total_price)) $total_price += (int)$op['change'];

        			                            		$giamgia = $op['change'];
        			                                	echo '<option selected value="'.$k_op.'">'.$op['name'].'</option>';
        			                                }else{
        			                                	echo '<option value="'.$k_op.'">'.$op['name'].'</option>';
        			                                }
        			                            }
        			                            echo '</select>';
        			                            echo '<span class="discout">'.Tuyen::_show_gia_discount($giamgia).'</span>';
        			                            echo '</div>';
        			                        }
        			                    ?>                                             
    			                    </div>
                                    
                                    <span style="float: left;padding: 15px 15px 0 0; font-size: 12px">Số lượng</span>
    								    
    	                            <div class="choosenumber">	                                    
    	                                <input type="hidden" class="hdQuantity cart_e" name="cart[<?= $k?>][soluong]" value="<?= $item['soluong']?>">
    	                                <div class="abate <?= ($item['soluong'] > 1)?'active':'' ?>"></div>
    	                                <div class="number"><?= $item['soluong']?></div>
    	                                <div class="augment "></div>	                                
    	                            </div>


                                    <?php $cart_success['san_pham'][$k]['so_luong'] = $item['soluong']; ?>
                                    <?php $cart_success['san_pham'][$k]['don_gia'] = $total_price; ?>


    	                            <div class="total-price">
    	                            	<?php if(is_numeric($total_price)){ ?>
    	                            		<p><?= number_format($total_price).Tuyen::_show_donvitiente()?> x <?= $item['soluong']?> </p>
    	                            	<?php } ?>

                                    	<b>Thành tiền: </b>
                                    	<?php if(is_numeric($total_price)){ ?>
    	                                	<?php $total_price *= $item['soluong'] ?>
    	                                	<b><?= number_format($total_price).Tuyen::_show_donvitiente()?></b>
    	                                	<?php $all_total_price  += $total_price ?>
    	                                <?php }else{ ?>
    										<b><?= $total_price ?></b>
    	                                <?php } ?>
                                    </div>
    	                            
    	                        </div>
    	                    </li>
    					<?php } } ?>

                    </ul>
                    </form>

                    <div class="area_total">
                        <div>
                            <!-- <div>
                                <span>Tổng tiền:</span>
                                <span><?php // number_format($all_total_price)?>₫</span>
                            </div> -->
                            <div class="shipping_home">
                                <div class="total">
                                    <b>Tổng tiền hàng:</b>

                                    <?php 
                                        //Update lại tổng giá theo sp đã tính vào vào cart success
                                        $cart_success['tong_gia'] = $all_total_price;
                                        
                                        $session['cart_success'] = $cart_success;
                                    ?>

                                    <strong><?= number_format($all_total_price).Tuyen::_show_donvitiente()?></strong>
                                </div>
                            </div>
                        </div>

                        <?php 
                            // echo '<pre>';
                            // print_r($cart_success);
                            // echo '</pre>';
                        ?>
                        <!-- <div class="boxCouponCode" style="">
                            <div class="textcode">
                                Sử dụng mã giảm giá
                            </div>
                            <div class="inputcode " style="display:none;">
                                <button type="button">Áp dụng</button>
                                <input name="CouponCode" id="CouponCode" placeholder="Nhập mã giảm giá" maxlength="20">
                                <label id="CouponCode-error" class="error" for="CouponCode" style="display: none;"></label>
                            </div>
                        </div> -->
                    </div>                
                </div>


               <!--  <div class="infouser ">
                    <div class="malefemale">
                        <label class="anh choose"><i class="iconmobile-opt"></i>&nbsp;Anh</label>
                        <label class=""><i class="iconmobile-opt"></i>&nbsp;Chị</label>
                        <input data-val="true" data-val-number="The field iGender must be a number." data-val-required="The iGender field is required." id="BillingAddress_iGender" name="BillingAddress.iGender" type="hidden" value="1">
                    </div>
                    <div class="areainfo">
                        <div class="left">
                            <input type="text" class="saveinfo" name="BillingAddress.FullName" placeholder="Họ và tên" maxlength="50" value="Nguyễn Than">
                        </div>
                        <div class="right">
                            <input type="tel" class="saveinfo" name="BillingAddress.PhoneNumber" placeholder="Số điện thoại" maxlength="11" value="0905556876">
                        </div>
                        <input type="text" id="BillingAddress_Email" name="BillingAddress.Email" placeholder="Email (Để theo dõi quá trình chuyển hàng)" maxlength="100" class="saveinfo" style="display:none;">
                        <input type="text" class="saveinfo" style="" id="OrderNote" name="OrderNote" placeholder="Yêu cầu khác (không bắt buộc)" maxlength="300">
                    </div>
                </div> -->


               <!--  <div class="area_other">
                    <div class="textnote"><b>Để được phục vụ nhanh hơn,</b> hãy chọn thêm:</div>
                    <div class="address ">
                        <label class=""><i class="iconmobile-opt"></i>&nbsp;Địa chỉ giao hàng</label>
                    </div>
                    <div class="supermarket">
                        <label class=""><i class="iconmobile-opt"></i>&nbsp;Nhận tại siêu thị</label>
                    </div>
                    <div class="area_market ">
                        <div class="overlay">
                            <span class="cswrap">
                                <span class="csdot"></span>
                                <span class="csdot"></span>
                                <span class="csdot"></span>
                            </span>
                        </div>
                        <div class="citydis">
                            <div class="city"><span data-id="3">TP.Hồ Chí Minh</span></div>
                            <div class="listcity layer">
                                <div class="searchlocal">
                                    <div>
                                        <input name="txtSearch" type="text" placeholder="Nhập tỉnh, thành để tìm nhanh">
                                        <button type="submit" class="submit"><i class="iconmobile-search"></i></button>
                                    </div>
                                </div>
                                <div class="scroll">
                                    <aside><a data-value="3">TP.Hồ Chí Minh</a><a data-value="5">Hà Nội</a><a data-value="9">Đà Nẵng</a><a data-value="82">An Giang</a><a data-value="102">Bà Rịa - Vũng Tàu</a><a data-value="103">Bắc Giang</a><a data-value="104">Bắc Kạn</a><a data-value="105">Bạc Liêu</a><a data-value="106">Bắc Ninh</a><a data-value="107">Bến Tre</a><a data-value="108">Bình Định</a><a data-value="109">Bình Dương</a><a data-value="110">Bình Phước</a><a data-value="111">Bình Thuận</a><a data-value="81">Cà Mau</a><a data-value="7">Cần Thơ</a><a data-value="112">Cao Bằng</a><a data-value="6">Đắk Lắk</a><a data-value="113">Đắk Nông</a><a data-value="114">Điện Biên</a><a data-value="8">Đồng Nai</a><a data-value="115">Đồng Tháp</a><a data-value="116">Gia Lai</a><a data-value="117">Hà Giang</a><a data-value="118">Hà Nam</a><a data-value="120">Hà Tĩnh</a><a data-value="121">Hải Dương</a><a data-value="101">Hải Phòng</a><a data-value="122">Hậu Giang</a><a data-value="123">Hòa Bình</a><a data-value="124">Hưng Yên</a></aside>
                                    <aside><a data-value="125">Khánh Hòa</a><a data-value="126">Kiên Giang</a><a data-value="127">Kon Tum</a><a data-value="128">Lai Châu</a><a data-value="129">Lâm Đồng</a><a data-value="130">Lạng Sơn</a><a data-value="131">Lào Cai</a><a data-value="132">Long An</a><a data-value="133">Nam Định</a><a data-value="134">Nghệ An</a><a data-value="135">Ninh Bình</a><a data-value="136">Ninh Thuận</a><a data-value="137">Phú Thọ</a><a data-value="138">Phú Yên</a><a data-value="139">Quảng Bình</a><a data-value="140">Quảng Nam</a><a data-value="141">Quảng Ngãi</a><a data-value="142">Quảng Ninh</a><a data-value="143">Quảng Trị</a><a data-value="144">Sóc Trăng</a><a data-value="145">Sơn La</a><a data-value="146">Tây Ninh</a><a data-value="147">Thái Bình</a><a data-value="148">Thái Nguyên</a><a data-value="149">Thanh Hóa</a><a data-value="150">Thừa Thiên Huế</a><a data-value="151">Tiền Giang</a><a data-value="152">Trà Vinh</a><a data-value="153">Tuyên Quang</a><a data-value="154">Vĩnh Long</a><a data-value="155">Vĩnh Phúc</a><a data-value="156">Yên Bái</a></aside>
                                </div>
                            </div>
                            <div class="dist"><span data-id="0">Chọn quận, huyện</span></div>
                            <div class="listdist layer">
                                <div class="searchlocal">
                                    <div>
                                        <input name="txtSearch" type="text" placeholder="Nhập quận, huyện để tìm nhanh">
                                        <button type="submit" class="submit"><i class="iconmobile-search"></i></button>
                                    </div>
                                </div>
                                <div class="scroll">
                                    <aside><a data-value="16">Quận 1</a><a data-value="17">Quận 2</a><a data-value="18">Quận 3</a><a data-value="19">Quận 4</a><a data-value="20">Quận 5</a><a data-value="21">Quận 6</a><a data-value="22">Quận 7</a><a data-value="23">Quận 8</a><a data-value="24">Quận 9</a><a data-value="25">Quận 10</a><a data-value="26">Quận 11</a><a data-value="27">Quận 12</a></aside>
                                    <aside><a data-value="30">Quận Tân Bình</a><a data-value="33">Quận Tân Phú</a><a data-value="52">Quận Phú Nhuận</a><a data-value="29">Quận Gò Vấp</a><a data-value="51">Quận Bình Thạnh</a><a data-value="28">Quận Thủ Đức</a><a data-value="31">Quận Bình Tân</a><a data-value="32">Huyện Hóc Môn</a><a data-value="34">Huyện Củ Chi</a><a data-value="35">Huyện Nhà Bè</a><a data-value="36">Huyện Bình Chánh</a><a data-value="61">Huyện Cần Giờ</a></aside>
                                </div>
                            </div>
                        </div>
                        <div class="searchstore">
                            <div>
                                <input name="txtSearch" type="text" placeholder="Nhập tên đường để tìm nhanh siêu thị">
                                <button type="button" class="submit"><i class="iconmobile-search"></i></button>
                            </div>
                        </div>
                        <div class="clr"></div>
                        <ul class="listmarket"></ul>
                        <input class="ShipAtStore min1" id="BillingAddress_StoreId" name="BillingAddress.StoreId" type="hidden" value="0">
                        <input id="BillingAddress_StoreName" name="BillingAddress.StoreName" type="hidden" value="">
                        <div class="citydis timeship">
                            <div class="date">Thời gian nhận:&nbsp;&nbsp;<span data-id="0">Hôm nay 29/08</span></div>
                            <div class="listdate listreceive layer" style="display: none;">
                                <span data-value="0" class="in">Hôm nay 29/08</span>
                                <span data-value="1" class="in">Ngày mai 30/08</span>
                                <span data-value="2" class="in">Thứ Sáu 31/08</span>

                            </div>
                        </div>
                        <div class="note_market none">
                            <p>Sản phẩm hiện đã <b>tạm hết hàng tại siêu thị này</b>. Thời gian nhận hàng dự kiến sau <b>2 - 7 ngày</b>.</p>
                            <div class="areainfo">
                                <input type="text" style="" name="EmailSecondHome" placeholder="Email (để theo dõi quá trình chuyển hàng)" maxlength="100" class="saveinfo EmailSecond">
                            </div>
                        </div>
                        <div class="clr"></div>
                    </div>
                    <div class="area_address  ">
                        <div class="overlay">
                            <span class="cswrap">
                                <span class="csdot"></span>
                                <span class="csdot"></span>
                                <span class="csdot"></span>
                            </span>
                        </div>
                        <div class="firstaddress ">
                            <div class="citydis">
                                <div class="city"><span data-id="3">TP.Hồ Chí Minh</span></div>
                                <div class="listcity layer">
                                    <div class="searchlocal">
                                        <div>
                                            <input type="text" name="txtSearch" placeholder="Nhập tỉnh, thành để tìm nhanh">
                                            <button type="button" class="submit"><i class="iconmobile-search"></i></button>
                                        </div>
                                    </div>
                                    <div class="scroll">
                                        <aside><a class="n1" data-stock="1" data-value="3">TP.Hồ Chí Minh</a><a class="n1" data-stock="1" data-value="5">Hà Nội</a><a class="n0" data-stock="0" data-value="9">Đà Nẵng</a><a class="n0" data-stock="0" data-value="82">An Giang</a><a class="n1" data-stock="1" data-value="102">Bà Rịa - Vũng Tàu</a><a class="n0" data-stock="0" data-value="103">Bắc Giang</a><a class="n0" data-stock="0" data-value="104">Bắc Kạn</a><a class="n0" data-stock="0" data-value="105">Bạc Liêu</a><a class="n0" data-stock="0" data-value="106">Bắc Ninh</a><a class="n0" data-stock="0" data-value="107">Bến Tre</a><a class="n1" data-stock="1" data-value="108">Bình Định</a><a class="n0" data-stock="0" data-value="109">Bình Dương</a><a class="n0" data-stock="0" data-value="110">Bình Phước</a><a class="n0" data-stock="0" data-value="111">Bình Thuận</a><a class="n0" data-stock="0" data-value="81">Cà Mau</a><a class="n1" data-stock="1" data-value="7">Cần Thơ</a><a class="n0" data-stock="0" data-value="112">Cao Bằng</a><a class="n0" data-stock="0" data-value="6">Đắk Lắk</a><a class="n0" data-stock="0" data-value="113">Đắk Nông</a><a class="n0" data-stock="0" data-value="114">Điện Biên</a><a class="n1" data-stock="1" data-value="8">Đồng Nai</a><a class="n1" data-stock="1" data-value="115">Đồng Tháp</a><a class="n0" data-stock="0" data-value="116">Gia Lai</a><a class="n0" data-stock="0" data-value="117">Hà Giang</a><a class="n0" data-stock="0" data-value="118">Hà Nam</a><a class="n0" data-stock="0" data-value="120">Hà Tĩnh</a><a class="n0" data-stock="0" data-value="121">Hải Dương</a><a class="n0" data-stock="0" data-value="101">Hải Phòng</a><a class="n0" data-stock="0" data-value="122">Hậu Giang</a><a class="n0" data-stock="0" data-value="123">Hòa Bình</a><a class="n0" data-stock="0" data-value="124">Hưng Yên</a></aside>
                                        <aside><a class="n0" data-stock="0" data-value="125">Khánh Hòa</a><a class="n1" data-stock="1" data-value="126">Kiên Giang</a><a class="n0" data-stock="0" data-value="127">Kon Tum</a><a class="n0" data-stock="0" data-value="128">Lai Châu</a><a class="n0" data-stock="0" data-value="129">Lâm Đồng</a><a class="n0" data-stock="0" data-value="130">Lạng Sơn</a><a class="n0" data-stock="0" data-value="131">Lào Cai</a><a class="n1" data-stock="1" data-value="132">Long An</a><a class="n1" data-stock="1" data-value="133">Nam Định</a><a class="n0" data-stock="0" data-value="134">Nghệ An</a><a class="n0" data-stock="0" data-value="135">Ninh Bình</a><a class="n0" data-stock="0" data-value="136">Ninh Thuận</a><a class="n0" data-stock="0" data-value="137">Phú Thọ</a><a class="n0" data-stock="0" data-value="138">Phú Yên</a><a class="n0" data-stock="0" data-value="139">Quảng Bình</a><a class="n0" data-stock="0" data-value="140">Quảng Nam</a><a class="n0" data-stock="0" data-value="141">Quảng Ngãi</a><a class="n0" data-stock="0" data-value="142">Quảng Ninh</a><a class="n0" data-stock="0" data-value="143">Quảng Trị</a><a class="n0" data-stock="0" data-value="144">Sóc Trăng</a><a class="n0" data-stock="0" data-value="145">Sơn La</a><a class="n1" data-stock="1" data-value="146">Tây Ninh</a><a class="n0" data-stock="0" data-value="147">Thái Bình</a><a class="n0" data-stock="0" data-value="148">Thái Nguyên</a><a class="n0" data-stock="0" data-value="149">Thanh Hóa</a><a class="n1" data-stock="1" data-value="150">Thừa Thiên Huế</a><a class="n1" data-stock="1" data-value="151">Tiền Giang</a><a class="n1" data-stock="1" data-value="152">Trà Vinh</a><a class="n0" data-stock="0" data-value="153">Tuyên Quang</a><a class="n1" data-stock="1" data-value="154">Vĩnh Long</a><a class="n0" data-stock="0" data-value="155">Vĩnh Phúc</a><a class="n0" data-stock="0" data-value="156">Yên Bái</a></aside>
                                    </div>
                                </div>
                                <div class="dist"><span data-id="0">Chọn quận, huyện</span></div>
                                <div class="listdist layer">
                                    <div class="searchlocal">
                                        <div>
                                            <input name="txtSearch" type="text" placeholder="Nhập quận, huyện để tìm nhanh">
                                            <button type="submit" class="submit"><i class="iconmobile-search"></i></button>
                                        </div>
                                    </div>
                                    <div class="scroll">
                                        <aside><a class="n1" data-stock="1" data-value="16">Quận 1</a><a class="n1" data-stock="1" data-value="17">Quận 2</a><a class="n1" data-stock="1" data-value="18">Quận 3</a><a class="n1" data-stock="1" data-value="19">Quận 4</a><a class="n0" data-stock="0" data-value="20">Quận 5</a><a class="n0" data-stock="0" data-value="21">Quận 6</a><a class="n1" data-stock="1" data-value="22">Quận 7</a><a class="n0" data-stock="0" data-value="23">Quận 8</a><a class="n0" data-stock="0" data-value="24">Quận 9</a><a class="n0" data-stock="0" data-value="25">Quận 10</a><a class="n0" data-stock="0" data-value="26">Quận 11</a><a class="n1" data-stock="1" data-value="27">Quận 12</a></aside>
                                        <aside><a class="n1" data-stock="1" data-value="30">Quận Tân Bình</a><a class="n1" data-stock="1" data-value="33">Quận Tân Phú</a><a class="n1" data-stock="1" data-value="52">Quận Phú Nhuận</a><a class="n1" data-stock="1" data-value="29">Quận Gò Vấp</a><a class="n1" data-stock="1" data-value="51">Quận Bình Thạnh</a><a class="n0" data-stock="0" data-value="28">Quận Thủ Đức</a><a class="n0" data-stock="0" data-value="31">Quận Bình Tân</a><a class="n1" data-stock="1" data-value="32">Huyện Hóc Môn</a><a class="n0" data-stock="0" data-value="34">Huyện Củ Chi</a><a class="n0" data-stock="0" data-value="35">Huyện Nhà Bè</a><a class="n0" data-stock="0" data-value="36">Huyện Bình Chánh</a><a class="n0" data-stock="0" data-value="61">Huyện Cần Giờ</a></aside>
                                    </div>
                                </div>
                            </div>
                            <input data-val="true" data-val-number="The field ProvinceId must be a number." data-val-required="The ProvinceId field is required." id="BillingAddress_ProvinceId" name="BillingAddress.ProvinceId" type="hidden" value="3">
                            <input data-val="true" data-val-number="The field DistictId must be a number." data-val-required="The DistictId field is required." id="BillingAddress_DistictId" name="BillingAddress.DistictId" type="hidden" value="0">
                            <input type="text" placeholder="Số nhà, tên đường, phường / xã" id="BillingAddress_Address" name="BillingAddress.Address" maxlength="200" class="ShipAtHome homenumber saveinfo">
                        </div>
                        <div class="note_address none">
                            <p></p>
                            <div class="areainfo">
                                <input type="text" style="" name="EmailSecondHome" placeholder="Email (để theo dõi quá trình chuyển hàng)" maxlength="100" class="saveinfo EmailSecond">
                            </div>
                        </div>
                        <div class="showtimeship_address">
                            <div class="timeship">Thời gian giao:</div>
                            <div class="citydis">

                                <div class="date"><span data-id="0">Hôm nay 29/08</span></div>
                                <div class="listdate layer" style="display: none;">
                                    <span data-value="0" onclick="ChangeHours(this)" class="in  ">Hôm nay 29/08</span>
                                    <span data-value="1" onclick="ChangeHours(this)" class="in">Ngày mai 30/08</span>

                                    <span data-value="2" onclick="ChangeHours(this)">Thứ Sáu 31/08</span>

                                    <input data-val="true" data-val-number="The field ShipDay must be a number." data-val-required="The ShipDay field is required." id="BillingAddress_ShipDay" name="BillingAddress.ShipDay" type="hidden" value="0">
                                </div>

                                <div class="hours"><span>Trước 15 giờ</span></div>
                                <div class="listhours layer" style="display: block;">
                                    <span data-value="9" class="  today none" style="display: none;"><text>Trước 9 giờ</text></span>
                                    <span data-value="10" class="  today none" style="display: none;"><text>Trước 10 giờ</text></span>
                                    <span data-value="11" class="  today none" style="display: none;"><text>Trước 11 giờ</text></span>
                                    <span data-value="12" class="  today none" style="display: none;"><text>Trước 12 giờ</text></span>
                                    <span data-value="13" class="  today none" style="display: none;"><text>Trước 13 giờ</text></span>
                                    <span data-value="14" class="  today none" style="display: none;"><text>Trước 14 giờ</text></span>
                                    <span data-value="15" class=" ahour " selected=""><text class="c" style="display: none;">Trước 15 giờ</text><text class="h" style="">Giao hàng trong 90 phút</text></span>
                                    <span data-value="16" class="  "><text>Trước 16 giờ</text></span>
                                    <span data-value="17" class="  "><text>Trước 17 giờ</text></span>
                                    <span data-value="18" class="  "><text>Trước 18 giờ</text></span>
                                    <span data-value="19" class="  "><text>Trước 19 giờ</text></span>
                                    <span data-value="20" class="hcm  "><text>Trước 20 giờ</text></span>
                                    <span data-value="21" class="hcm  "><text>Trước 21 giờ</text></span>
                                    <span data-value="22" class="hcm  "><text>Trước 22 giờ</text></span>
                                    <input data-val="true" data-val-number="The field ShipHour must be a number." data-val-required="The ShipHour field is required." id="BillingAddress_ShipHour" name="BillingAddress.ShipHour" type="hidden" value="15">
                                </div>
                            </div>
                        </div>
                        <input data-val="true" data-val-number="The field IsTechSupportShiper must be a number." data-val-required="The IsTechSupportShiper field is required." id="IsTechSupportShiper" name="IsTechSupportShiper" type="hidden" value="0">
                        <div class="clr"></div>
                        <label class="htkt "><i class="iconmobile-checkbox"></i>&nbsp;Yêu cầu nhân viên hỗ trợ kỹ thuật đi giao hàng</label>
                        <div class="clr"></div>
                        <label class="cathe"><i class="iconmobile-checkbox"></i>&nbsp;Cà thẻ khi nhận hàng</label>
                        <div class="clr"></div>
                    </div>
                    <div class="boxCompany none">
                        <div class="billvat">
                            <label class="vat"><i class="iconmobile-checkbox"></i>&nbsp;Xuất hóa đơn công ty</label>
                            <div class="infocompany">
                                <input class="input IsCompany" id="CompanyInfo_CompanyName" maxlength="200" name="CompanyInfo.CompanyName" placeholder="Tên công ty" type="text" value="">
                                <input class="input IsCompany" id="CompanyInfo_CompanyAddress" maxlength="200" name="CompanyInfo.CompanyAddress" placeholder="Địa chỉ" type="text" value="">
                                <input class="input IsCompany" id="CompanyInfo_CompanyTaxno" maxlength="20" name="CompanyInfo.CompanyTaxno" placeholder="Mã số thuế" type="text" value="">
                            </div>
                        </div>

                    </div>
                </div> -->
              

                <!--<div class="area_secur captcha " style="display:none;">
                    <span>Để tiếp tục đặt hàng, vui lòng nhập mã bảo mật</span>
                    <div class="capcha">
                        <img width="130" height="40" src="/aj/orderv4/getcaptchaimage?prefix=1332519506" class="imgcaptcha">
                        <div class="changecode" onclick="$('img.imgcaptcha').attr('src','/aj/orderv4/getcaptchaimage?prefix='+Math.random())">Đổi mã khác</div> 
                    </div>
                    <div class="entercapcha">
                        <input class="inputcode" name="Captcha" type="tel" maxlength="4" placeholder="Nhập mã bảo mật">
                    </div>
                </div>-->

                <div class="message"></div>
                <div class="choosepayment" style="margin: 0 auto;width: 50%;">
                    <a href="thong-tin-thanh-toan.html" class="payoffline">Thông tin thanh toán<span>Nhập thông tin thanh toán, giao hàng</span></a>
                   <!--  <div class="payonline">
                        <div>Thanh toán online<span>Bằng thẻ ATM, Visa, Master</span></div>
                    </div> -->
                </div>
                <!-- <div class="onlinemethod">
                    <a href="javascript:void(0)" class="atm">Dùng thẻ ATM<span>Có internet Banking</span></a>
                    <a href="javascript:void(0)" class="visa">Dùng thẻ Visa, MASTER</a>
                    <a class="rechoose" href="javascript:;">Chọn lại hình thức thanh toán</a>
                </div> -->
                <div class="clr"></div>  
            <?php } ?>           
        <!-- </form> -->

    </div>
    <p style="display: none" class="provision">Bằng cách đặt hàng, bạn đồng ý với <a href="/tos" target="_blank">Điều khoản sử dụng</a> của Thegioididong</p>
</section>

<script>
            cart = [{"ProductId":106875,"IsBuy":false,"ProductPrice":23990000.0,"ProductName":"Apple Macbook Air MQD32SA/A i5 1.8GHz/8GB/128GB (2017)","CategoryName":"Laptop","BrandName":"Macbook","PriceClient":null,"ProductCode":"0220042000233","ProductNameCode":null,"Quantity":1,"IsShowColor":true,"arrColor":[{"activedDateField":null,"activedUserField":null,"categoryIDField":44,"colorCodeField":"Silver","colorIDField":5,"colorNameField":"Bạc","createdDateField":null,"createdUserField":null,"deletedDateField":null,"deletedUserField":null,"iconField":"5.jpg","isActivedField":false,"isDeletedField":false,"isExistField":true,"langIDField":null,"pictureField":null,"preOrderPriceField":0.0,"priceField":23990000.0,"productCodeField":"0220042000233","productIDField":106875,"statusField":4,"updatedDateField":null,"updatedUserField":null}],"CouponCode":null,"LinkImage":"//cdn.tgdd.vn/Products/Images/44/106875/apple-macbook-air-mqd32sa-a-i5-5350u-180x125.png","RefProductId":0,"IsBuyComBo":false,"ListPromotions":[]}];
        </script>