<?php
use aabc\widgets\Menu;
use aabc\helpers\ArrayHelper;
use common\components\Tuyen;
use aabc\helpers\Html;
use aabc\widgets\ActiveForm;

use backend\models\Cauhinh;
$cache = Aabc::$app->dulieu;
$thetieude = json_decode($cache->get('cauhinh'.Cauhinh::thetieude),true);
$themota = json_decode($cache->get('cauhinh'.Cauhinh::themota),true);


$this->title = $thetieude;
$this->params['description'] = $themota;

use frontend\assets\CustomAsset;
$bundle = CustomAsset::register($this);
$assetsPrefix = $this->assetBundles[TempAsset]->baseUrl ;

function role_show($a = '')
{
    $b = Tuyen::_dulieu('cauhinh',$a);
    return ($b == 2 || $b == 3);
}

?>

<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

<section id="wrap_cart" style="margin-top: 80px ;">    
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
                .areainfo div.left.on-top span {
                    font-size: 12px;
                    top: 5px;
                }
                .areainfo>div.left  {
                    position: relative;                    
                    width: calc(100% / 1 - 10px) !important;
                    margin-bottom: 10px !important;
                }

                .areainfo>div.left:nth-child(odd) {
                    /*margin: 0 10px 0 0;*/
                }

                .areainfo>div.left:nth-child(even) {
                    /*margin: 0 0 0 10px;*/
                }



                .areainfo .left div>span{
                    position: absolute;
                    /*font-size: 12px;
                    top: 12px;*/
                    font-size: 14px;
                    top: 26px;
                    left: -10px;
                    color: #aaa;
                    padding: 0;
                    margin: 0 0 0 9px;
                    transition: all 0.1s;
                 }
                 .areainfo .left div>span>label{
                    font-size: inherit;
                    color: inherit;
                 }

                 .areainfo .left input{
                    padding: 15px 0 3px 0;
                    transition: 0.3s;                    
                    background: transparent;
                    position: relative;
                    border: none;
                    border-bottom: 1px solid #ccc;
                    border-radius: initial;
                    /*color: #2085d4;*/
                    color: #777;
                    text-indent: 0px;
                 }

                 .has-error .ferror {
                    color: #D00;
                    font-size: 12px;
                    left: 5px;
                    position: absolute;
                    left: 0;
                    bottom: -12px;
                }

                .areainfo .has-error input {
                    border-bottom: 1px solid #E00;
                }
                .clearfix{clear:both;}
            </style>


            <style type="text/css">
                .wrap_cart{
                    max-width: 1200px;
                }
                
                .httt{
                    width: 40%;
                    float: left;
                    padding: 0 15px 0 0;
                    position: relative;
                    height: 420px;
                }
                .httt-content {
                    border: 1px solid #ddd;
                    padding: 10px;
                }
                .httt-check{
                    cursor: pointer;
                }

                .infouser {
                    width: 25%;
                    float: left;
                }
                .area_other{
                    position: absolute;
                    width: calc(25% - 10px);
                }
                #httt{
                    height: 368px;
                    overflow: hidden;
                }
                .read-more{
                    position: absolute;
                    bottom: 0;
                    width: calc(100% - 17px);
                    padding: 0;
                    text-align: center;
                    background: #FFF;
                    z-index: 999;
                    cursor: pointer;
                    color: #1a7ecb;
                }
                .read-more:before{
                    height: 60px;
                    margin-top: -60px;
                    content: -webkit-gradient(linear,0% 100%,0% 0%,from(#fff),color-stop(.2,#fff),to(rgba(255,255,255,0)));
                    display: block;
                    width: 100%;
                    color: black;
                }
                .read-more:after{
                    content: '';
                    width: 0;
                    right: 0;
                    border-top: 6px solid #288ad6;
                    border-left: 6px solid transparent;
                    border-right: 6px solid transparent;
                    display: inline-block;
                    vertical-align: middle;
                    margin: -2px 0 0 5px;
                }


                .httt.r-more {
                    height: auto;
                }

                .r-more .read-more {
                    display: none;
                }

                .r-more #httt {
                    height: auto;
                    overflow: auto;
                }
            </style>


                <?php $form = ActiveForm::begin( ['id' => 'dang-ky-form']); ?>
           
                <div class="infouser">

                    <h1 style="font-size: 16px;text-transform: uppercase;font-weight: bold;margin: 0px 12px 10px 0;border-bottom: 1px solid #14ace7;color: #0b9ed6;">
                        Thông tin người mua
                    </h1>

                    <?php 

                     $template_input = '
                            <span>{label}</span>
                            {input}
                            {error}';

                     ?>


                    <div class="areainfo">
                        <?php if(role_show(Cauhinh::cart_gioitinh)){ ?>
                        <div class="left" style="margin: 5px 0 0 0">
                            <label class="gt-check choose">
                                <i class="iconmobile-opt"></i>&nbsp;Anh
                                <input type="hidden" <?= $model->gioitinh == 1?'':'disabled'?> class="form-control" value="1" name="CartForm[gioitinh]">
                            </label>

                            <label class="gt-check" style="margin: 0 0 0 20px;">
                                <i class="iconmobile-opt"></i>&nbsp;Chị
                                <input type="hidden" <?= $model->gioitinh == 2?'':'disabled'?> class="form-control" value="2" name="CartForm[gioitinh]">
                            </label>

                            <script type="text/javascript">
                                $('.gt-check').click(function(){
                                    var _this = $(this), _not_this = $('.gt-check').not(_this);
                                    _not_this.removeClass('choose')
                                    _this.addClass('choose');
                                    _not_this.find('>input').prop('disabled',true)
                                    _this.find('>input').prop('disabled',false)
                                })
                            </script>

                            <?php 
                                //echo $form->field($model, 'gioitinh',['template' => $template_input, 'options' => ['class' => '']])->textInput(['maxlength' => true]);
                            ?>                            
                        </div>
                        <?php } ?>


                        <?php if(role_show(Cauhinh::cart_hoten)){ ?>
                         <div class="left <?= empty($model->hoten)?'':'on-top'?>">
                            <?php 
                                echo $form->field($model, 'hoten',['template' => $template_input, 'options' => ['class' => '']])->textInput(['maxlength' => true]);
                            ?>                            
                        </div>
                        <?php } ?>



                        <?php if(role_show(Cauhinh::cart_dienthoai)){ ?>
                        <div class="left <?= empty($model->dienthoai)?'':'on-top'?>">
                            <?php 
                                echo $form->field($model, 'dienthoai',['template' => $template_input, 'options' => ['class' => '']])->textInput(['maxlength' => true]);
                            ?>
                        </div>  
                        <?php } ?>


                        <?php if(role_show(Cauhinh::cart_email)){ ?>
                        <div class="left <?= empty($model->email)?'':'on-top'?>">
                            <?php 
                                echo $form->field($model, 'email',['template' => $template_input, 'options' => ['class' => '']])->textInput(['maxlength' => true]);
                            ?>
                        </div>  
                        <?php } ?>




                        <?php if(role_show(Cauhinh::cart_ghichu)){ ?>
                        <div class="hide left <?= empty($model->ghichu)?'':'on-top'?>">
                            <?php 
                                echo $form->field($model, 'ghichu',['template' => $template_input, 'options' => ['class' => '']])->textInput(['maxlength' => true]);
                            ?>
                        </div> 
                        <?php } ?>

                        


                        <?php if(role_show(Cauhinh::cart_diachi)){ ?>

                            <h1 style="font-size: 16px;text-transform: uppercase;font-weight: bold;margin: 0px 0px 10px 0;border-bottom: 1px solid #14ace7;color: #0b9ed6;">
                                Thông tin giao hàng
                            </h1>

                            <div class="left" style="margin: 10px 0 0 0; display: contents;">
                                <label class="gh-home">
                                    <i class="iconmobile-opt"></i>&nbsp;Giao hàng tận nhà
                                    <input type="hidden" disabled class="form-control" value="1" name="CartForm[giaohang]">
                                </label>

                                <label class="gh-home" style="margin: 0 0 0 20px;">
                                    <i class="iconmobile-opt"></i>&nbsp;Nhận tại cửa hàng
                                    <input type="hidden" disabled class="form-control" value="2" name="CartForm[giaohang]">
                                </label>

                                <script type="text/javascript">
                                    $('.gh-home').click(function(){
                                        var _this = $(this), _not_this = $('.gh-home').not(_this);
                                        _not_this.removeClass('choose')
                                        _this.addClass('choose');
                                        _not_this.find('>input').prop('disabled',true)
                                        _this.find('>input').prop('disabled',false)
                                        var val = _this.find('>input').val();
                                        if(val == 1){
                                            $('.dia-chi').removeClass('hide')
                                        }else{
                                            $('.dia-chi').addClass('hide')
                                        }
                                    })
                                </script>                 
                            </div>


                            <?php 
                                echo $form->field($model, 'tinh')->hiddenInput()->label(false);
                                echo $form->field($model, 'huyen')->hiddenInput()->label(false);
                            ?>

                            <div class="clearfix"></div>
                            <!-- <p style="margin: 10px 0 10px 0;color: #aaa;"></p> -->
                            <div class="dia-chi hide area_other" style="margin: 10px 0 0 0">  

                                <div class="area_market ">
                                    <div class="overlay">
                                        <span class="cswrap">
                                            <span class="csdot"></span>
                                            <span class="csdot"></span>
                                            <span class="csdot"></span>
                                        </span>
                                    </div>
                                    <div class="citydis">
                                        <div class="city"><span data-id="3">Chọn tỉnh, thành</span></div>
                                        <div class="listcity layer">
                                            <div class="searchlocal">
                                                <div>
                                                    <input type="text" placeholder="Nhập tỉnh, thành để tìm nhanh">
                                                    <button type="submit" class="submit"><i class="iconmobile-search"></i></button>
                                                </div>
                                            </div>
                                            <div class="scroll">                                                
                                                <?php 
                                                    $ship = Tuyen::_dulieu('cauhinh',Cauhinh::ship);      
                                                    foreach ($ship as $k_ship => $v_ship) {
                                                        echo '<a data-value="'.$k_ship.'">'.$v_ship['tinh'].'</a>';
                                                    }
                                                ?>                                                    
                                            </div>
                                        </div>
                                        <div class="dist"><span data-id="0">Chọn quận, huyện</span></div>
                                        <div class="listdist layer">
                                            <div class="searchlocal">
                                                <div>
                                                    <input type="text" placeholder="Nhập quận, huyện để tìm nhanh">
                                                    <button type="submit" class="submit"><i class="iconmobile-search"></i></button>
                                                </div>
                                            </div>
                                            <div class="scroll">
                                                <?php 
                                                    $ship = Tuyen::_dulieu('cauhinh',Cauhinh::ship);      
                                                    foreach ($ship as $k_ship => $v_ship) {
                                                        if(is_array($v_ship['item'])) foreach ($v_ship['item'] as $k_item => $v_item) {
                                                            if(is_array($v_item['huyen'])) foreach ($v_item['huyen'] as $k_huyen => $v_huyen) {
                                                                echo '<a data-id="'.$k_huyen.'" class="hide t-'.$k_ship.'" >'.$v_huyen.'</a>';
                                                            }
                                                        }                                                        
                                                    }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                                                        
                                </div>                                
                                
                            </div>

                            <div class="dia-chi hide left <?= empty($model->xa)?'':'on-top'?>" style="margin: 50px 0 0 0;">
                                <?php 
                                    echo $form->field($model, 'xa',['template' => $template_input, 'options' => ['class' => '']])->textInput(['maxlength' => true]);
                                ?>
                            </div>  

                        <?php } ?> 

                       

                               
                        <div class="clearfix"></div>
                    </div>

                      
                </div>
    

                <div class="httt">

                    <h1 style="font-size: 16px;text-transform: uppercase;font-weight: bold;margin: 0px 0px 10px 0;border-bottom: 1px solid #14ace7;color: #0b9ed6;">
                        Hình thức thanh toán
                    </h1>

                    <div id="httt">
                        <style type="text/css">
                            #httt ol,#httt ul,#httt dl {
                                padding: 0 40px;
                            }
                            #httt p {
                                display: block;
                                -webkit-margin-before: 1em;
                                -webkit-margin-after: 1em;
                                -webkit-margin-start: 0px;
                                -webkit-margin-end: 0px;
                            }
                        </style>
                    <?php
                        $httt = Tuyen::_dulieu('cauhinh', Cauhinh::hinhthucthanhtoan);
                        $dem = 0;
                        foreach ($httt as $k_httt => $v_httt) {
                    ?>
                        <p>
                            <label data-id="httt-<?= $k_httt?>" class="httt-check <?= empty($dem)?'choose':''?>"><i class="iconmobile-opt"></i>&nbsp;<?= $v_httt['label']?>
                                <input type="hidden" <?= empty($dem)?'':'disabled'?> id="cartform-phuongthuc" class="form-control" value="<?= $k_httt?>" name="CartForm[phuongthuc]" />
                            </label>
                    </p>
                    <?php 
                        $dem += 1;                           
                        }

                        $dem = 0;
                        foreach ($httt as $k_httt => $v_httt) {
                    ?>
                        <div class="httt-content httt-<?= $k_httt?> <?= empty($dem)?'':'hide'?>">
                            <?= $v_httt['content']?>
                        </div>
                    <?php 
                        $dem += 1;                           
                        }  

                    ?>
                    </div>

                    
                    <div class="read-more">Đọc thêm</div>
                </div>
                

                <script type="text/javascript">
                    $('.read-more').on('click',function(){
                        $('.httt').addClass('r-more')
                    })


                    $('.httt-check').on('click',function(){
                        $('.httt').removeClass('r-more')
                        // $('.httt-check').removeClass('choose')
                        // $(this).addClass('choose')
                        var id = $(this).data('id');
                        $('.httt-content').addClass('hide')
                        $('.'+id).removeClass('hide')

                        // $(this).parents('#httt').find('input').prop('disabled',true)
                        // $(this).find('>input').prop('disabled',false)

                        var _this = $(this), _not_this = $('.httt-check').not(_this);
                        _not_this.removeClass('choose')
                        _this.addClass('choose');
                        _not_this.find('>input').prop('disabled',true)
                        _this.find('>input').prop('disabled',false)
                    })
                </script>

                <script type="text/javascript">
                      $(".areainfo input").focus(function() {        
                    //$('.saveinfo').on('keyup keypress', function(){        
                        _this = $(this)
                        _parent = _this.parent()
                        _span = _parent.find('>span')        
                        _span.css('font-size','12px')
                        _span.css('top','5px')
                    })
                    
                    $(".areainfo input").blur(function() {            
                        _val = $(this).val()
                        _this = $(this)
                        _parent = _this.parent()
                        _span = _parent.find('>span')
                        if(_val == ''){                        
                            _span.css('font-size','14px')
                            _span.css('top','26px')
                        }     
                    })
                </script>


                <style type="text/css">
                    #tthoadon{
                        float: left;
                        margin: 0 0 0 20px;
                        width: calc(30% - 70px);
                    }
                    ul.tt-sp{}
                    ul.tt-sp li{
                        float: left;
                        width: 100%;
                        margin: 0 0 5px 0;
                        border-bottom: 1px solid #eee;
                        padding: 0 0 5px 0;
                    }
                    ul.tt-sp li .img{
                        width: 60px;
                        float: left;
                    }
                    ul.tt-sp li .tieude{
                        font-size: 13px;
                        color: #333;
                    }
                    ul.tt-sp li .price{
                        text-align: right;
                    }
                    ul.tt-sp li .price p{
                        font-size: 12px;
                    }
                    ul.tt-sp li .price b{}
                </style>
                <div id="tthoadon">
                    <h1 style="font-size: 16px;text-transform: uppercase;font-weight: bold;margin: 0px 0px 10px 0;border-bottom: 1px solid #14ace7;color: #0b9ed6;">
                        Thông tin đơn hàng
                    </h1>

                    <ul class="tt-sp">
                        <?php
                        $session = Aabc::$app->session;                            
                        $cart_success = $session['cart_success'];

                        if(is_array($cart_success['san_pham'])) foreach ($cart_success['san_pham'] as $k => $item) {
                        ?> 
                            <li class="">
                                <div class="img">                                    
                                    <img width="55" src="<?= $item['anh'] ?>">
                                </div>
                                <div class="">                                    
                                    <a class="tieude"><?= $item['ten']?></a>
                                    <div class="price">
                                        <?php if(is_numeric($item['don_gia'])){ ?>
                                            <p><?= number_format($item['don_gia']).Tuyen::_show_donvitiente()?> x <?= $item['so_luong']?> </p>
                                        <?php } ?>

                                        <?php if(is_numeric($item['don_gia'])){ ?>
                                            <?php $total_price = $item['don_gia'] * $item['so_luong'] ?>
                                            <b><?= number_format($total_price).Tuyen::_show_donvitiente()?></b>
                                        <?php }else{ ?>
                                            <b><?= $item['don_gia'] ?></b>
                                        <?php } ?>
                                    </div>
                                    
                                </div>
                            </li>
                        <?php } ?>
                            
                            <li>
                        <?php
                            echo 'Tổng tiền hàng: '. number_format($cart_success['tong_gia']).Tuyen::_show_donvitiente();
                        ?>
                            </li>
                    </ul>



                </div>
            


                <div class="area_secur captcha " style="display:none;">
                    <span>Để tiếp tục đặt hàng, vui lòng nhập mã bảo mật</span>
                    <div class="capcha">
                        <!-- <img width="130" height="40" src="/aj/orderv4/getcaptchaimage?prefix=1332519506" class="imgcaptcha">
                        <div class="changecode" onclick="$('img.imgcaptcha').attr('src','/aj/orderv4/getcaptchaimage?prefix='+Math.random())">Đổi mã khác</div> -->
                    </div>
                    <div class="entercapcha">
                        <input disabled class="inputcode" name="Captcha" type="tel" maxlength="4" placeholder="Nhập mã bảo mật">
                    </div>
                </div>
                
                <style type="text/css">
                    .payonline {
                        float: left;
                        overflow: hidden;
                        margin: 10px 30px 20px 30px;
                        position: relative;
                        width: 260px;opacity: 
                    }
                    .counterror{
                        display: none;
                    }
                </style>
                <div class="message"></div>

                <div class="clearfix"></div>

                    
                <div class="">
                    <a href="gio-hang.html" class="payoffline">Quay lại</a>
                   <!--  <div class="payonline">
                        <div>Thanh toán online<span>Bằng thẻ ATM, Visa, Master</span></div>
                    </div> -->
                </div>

                <!-- <div class="choosepayment"> -->
                <div class="">
                    <div class="payonline">
                        <div>
                            <button>Thanh toán &amp; giao hàng</button>                            
                        </div>
                    </div>
                </div>
                
                <?php ActiveForm::end(); ?>

                <div class="clr"></div>  
                    
        <!-- </form> -->

    </div>
    <p class="provision">Bằng cách đặt hàng, bạn đồng ý với <a href="/tos" target="_blank">Điều khoản sử dụng</a> của Thegioididong</p>
</section>

<script>
            cart = [{"ProductId":106875,"IsBuy":false,"ProductPrice":23990000.0,"ProductName":"Apple Macbook Air MQD32SA/A i5 1.8GHz/8GB/128GB (2017)","CategoryName":"Laptop","BrandName":"Macbook","PriceClient":null,"ProductCode":"0220042000233","ProductNameCode":null,"Quantity":1,"IsShowColor":true,"arrColor":[{"activedDateField":null,"activedUserField":null,"categoryIDField":44,"colorCodeField":"Silver","colorIDField":5,"colorNameField":"Bạc","createdDateField":null,"createdUserField":null,"deletedDateField":null,"deletedUserField":null,"iconField":"5.jpg","isActivedField":false,"isDeletedField":false,"isExistField":true,"langIDField":null,"pictureField":null,"preOrderPriceField":0.0,"priceField":23990000.0,"productCodeField":"0220042000233","productIDField":106875,"statusField":4,"updatedDateField":null,"updatedUserField":null}],"CouponCode":null,"LinkImage":"//cdn.tgdd.vn/Products/Images/44/106875/apple-macbook-air-mqd32sa-a-i5-5350u-180x125.png","RefProductId":0,"IsBuyComBo":false,"ListPromotions":[]}];
        </script>