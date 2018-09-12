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


<section id="wrap_cart">
    <div class="bar-top">
        <a href="/gio-hang.html" class="buymore">Quay lại giỏ hàng</a>
        
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
                .areainfo div.on-top span {
                    font-size: 12px;
                    top: 5px;
                }
                
                .areainfo>div {
                    position: relative;                    
                    width: calc(100% / 2 - 10px) !important;
                    margin-bottom: 10px !important;
                }

                .areainfo>div:nth-child(odd) {
                    margin: 0 10px 0 0;
                }

                .areainfo>div:nth-child(even) {
                    margin: 0 0 0 10px;
                }



                .areainfo div>span{
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
                 .areainfo div>span>label{
                    font-size: inherit;
                    color: inherit;
                 }

                 .areainfo input{
                    padding: 15px 0 3px 0;
                    transition: 0.3s;                    
                    background: transparent;
                    position: relative;
                    border: none;
                    border-bottom: 1px solid #ccc;
                    border-radius: initial;
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

                .area_other{
                    padding: 15px 30px;
                    width: auto;
                    position: relative;
                }
            </style>

                <div class="area_other">                    
                    <div class="address ">
                        <label class="choose"><i class="iconmobile-opt"></i>&nbsp;Bạn đã có tài khoản</label>
                    </div>
                    <div class="supermarket">
                        <a href="dang-ky.html"><label class=""><i class="iconmobile-opt"></i>&nbsp;Bạn chưa có tài khoản</label></a>
                    </div>
                   
                </div>


                <hr style="border: 1px solid #e1e1e1;margin: 0 30px;">

                <?php $form = ActiveForm::begin( ['id' => 'dang-ky-form']); ?>
           
                <div class="infouser ">
                    <h1 style="font-size: 16px;margin: 20px 0;text-transform: uppercase;font-weight: bold;">
                        Đăng nhập hệ thống
                    </h1>
                     

                     <?php 

                     $template_input = '
                            <span>{label}</span>
                            {input}
                            {error}';

                     ?>


                    <div class="areainfo">
                       
                        <div class="left">
                            <?php 
                                echo $form->field($model, 'username',['template' => $template_input, 'options' => ['class' => '']])->textInput(['maxlength' => true]);
                            ?>                            
                        </div>
                       
                        <div class="left">
                            <?php 
                                echo $form->field($model, 'password',['template' => $template_input, 'options' => ['class' => '']])->passwordInput(['maxlength' => true]);
                            ?>                           
                        </div>
                 
                    </div>

                      
                </div>
    
                <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

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
                <!-- <div class="choosepayment"> -->
                <div class="">
                    <div class="payonline">
                        <div>
                            <button>Đăng nhập</button>
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