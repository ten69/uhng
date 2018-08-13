<?php
use aabc\widgets\Menu;
use aabc\helpers\ArrayHelper;
use common\components\Tuyen;

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
// print_r($model);
// echo '</pre>';

?>

 <section class="type0">
        <ul class="breadcrumb">
            <li>
                <a href="#" title="Trang chủ">Trang chủ</a>
                <span>›</span>
            </li>
            <li>
                <a href="#laptop">Laptop</a>
                <span>›</span>
            </li>            
        </ul>


        <div class="rowtop">
            <h1><?= $model['sp_tensp']?></h1>

            <!-- <div class="likeshare">
                <div id="fb-root"></div>
                <div class="fb-like" data-href="/laptop/apple-macbook-air-mqd42sa-a-i5-5350u" data-layout="button_count" data-action="like" data-show-faces="true" data-share="true"></div>
            </div> -->
        </div>
        <div class="clr"></div>
        <div class="rowdetail" id="normalproduct">
            <aside class="picture">
                <?php $imgcove = Tuyen::_dulieu('image',$model['sp_images'],'320x320'); ?>
                <img src="<?= $imgcove ?>"/>
                <!-- <img src="<?= $assetsPrefix?>/jpg/apple-macbook-air-mqd42sa-a-i5-5350u-8gb-256gb-bac-450x300-450x300.jpg" alt="Laptop Apple Macbook Air MQD42SA/A i5 1.8GHz/8GB/256GB (2017)" onclick="gotoGallery(-1,0);"> -->
                <div class="colorandpic">
                    <ul class="owl-carousel owl-theme tabscolor">

                        <?php 

                        $albums = json_decode($model['sp_album'],true);

                        if(is_array($albums)) foreach ($albums as $k => $album) { ?>
                            <?php 
                            $img = Tuyen::_dulieu('image',$album['list'][0],'75x75');
                            ?>
                            <li onclick="gotoGallery(<?= $model['sp_id']?>, <?= $k?>)" class="item">
                                <div>
                                    <img src="<?= $img?>">
                                </div>
                                <?= $album['title']?>
                            </li>

                        <?php }
                        ?>
                        <!-- <li onclick="gotoGallery(1,5)" class="item">
                            <div>
                                <img src="<?= $assetsPrefix?>/png/apple-macbook-air-mqd42sa-a-i5-5350u-180x125.png">
                            </div>
                            Bạc
                        </li>

                        <li onclick="gotoGallery(1,5)" class="item">
                            <div>
                                <img src="<?= $assetsPrefix?>/png/apple-macbook-air-mqd42sa-a-i5-5350u-180x125.png">
                            </div>
                            Vàng đồng
                        </li>

                        <li onclick="gotoGallery(1,5)" class="item">
                            <div>
                                <img src="<?= $assetsPrefix?>/png/apple-macbook-air-mqd42sa-a-i5-5350u-180x125.png">
                            </div>
                            Xanh
                        </li>

                        <li onclick="gotoGallery(7,0)" class="item">
                            <div>
                                <i class="icontgdd-box"></i>
                            </div>
                            Mở hộp,<br/>k.mãi
                        </li> -->
                       
                    </ul>
                    <div class="prev hide" onclick="slideFltNext(0)">
                        <i class="icontgdd-prevthumd"></i>
                    </div>
                    <div class="next hide" onclick="slideFltNext(1)">
                        <i class="icontgdd-nextthumd"></i>
                    </div>
                </div>
               <!--  <div class="wrap_rtglr hide">
                    <div class="pop">
                        <div class="hdpop">
                            Phản hồi không hài lòng bộ ảnh
                            <a href="javascript:closeRtGlr();" class="closehd">
                                <i class="iconcom-close"></i>
                            </a>
                        </div>
                        <div class="ctpop">
                            <div class="bifRtCt bRtGlr hide">
                                <textarea name="txtContent" rows="3" placeholder="Mời bạn góp ý để chúng tôi phục vụ tốt hơn"></textarea>
                                <span>Hãy để lại thông tin để được hỗ trợ khi cần thiết (Không bắt buộc):</span>
                                <div class="ifRtGd" data-val="0">
                                    <label onclick="rtAtcChangeGder(1)" class="ifGdM">
                                        <i></i>
                                        Anh
                                    </label>
                                    <label onclick="rtAtcChangeGder(2)" class="ifGdFm">
                                        <i></i>
                                        Chị
                                    </label>
                                </div>
                                <input type="text" name="txtFullName" placeholder="Họ tên">
                                <input type="text" name="txtPhoneNumber" placeholder="Số điện thoại">
                                <input type="text" name="txtEmail" placeholder="Email">
                                <button type="submit" onclick="sendRatingContent(3)">
                                    Gửi góp ý<span>Cam kết bảo mật thông tin cá nhân</span>
                                </button>
                                <label class="alert"></label>
                            </div>
                        </div>
                    </div>
                </div> -->
                <div class="slide_FT"></div>
            </aside>
            <aside class="price_sale">
                <div class="area_price">
                    <strong><?= $model['sp_gia']?></strong>
                    <label class="installment">Trả góp 0%</label>
                    <span></span>
                </div>
                <!-- <div class="cpGift cpGit cpApple type0">
                    <a href="https://www.thegioididong.com/mua-online-gia-re-hon" class="xct2">&nbsp;</a>
                    <div class="us">
                        <form id="frmNCTCoupon" onsubmit="return NCDGetCoupon()">
                            <input placeholder="Nhập số điện thoại" type="tel" id="txtNCDPhoneNumber" maxlength="11" />
                            <button class="ncp">
                                <span>Nhận mã coupon</span>
                            </button>
                        </form>
                        <div id="loading-ncd">
                            <span>Bạn vui lòng chờ trong giây lát...</span>
                        </div>
                    </div>
                </div> -->
                <div class="wrapcp popcoupon hide">
                    <div class="wrapPop">
                        <div class="titlebar">
                            <b>Thể lệ chương trình</b>
                            <a href="javascript:closeCpPopup();" class="back"></a>
                        </div>
                        <div class="ct">
                            <strong>THÔNG TIN CHƯƠNG TRÌNH</strong>
                            <br/>
                                Thời gian: 00:00 08/08 - 23:59 10/08<br/>
                                    <br/>
                                        <strong>THỂ LỆ CHƯƠNG TRÌNH:</strong>
                                        <br/>
                                            1 Số điện thoại chỉ nhận 1 Mã giảm giá trong suốt chương trình.<br/>
                                                Mã giảm giá có giá trị 7 ngày kể từ ngày nhận được.<br/>
                                                    Áp dụng cho các sản phẩm Điện thoại, Tablet, Laptop, đồng hồ thông minh mới. <br/>
                                                        Riêng phụ kiện sẽ chạy với mức giảm giá riêng - không áp dụng kèm mã giảm giá của chương trình.<br/>
                                                            <br/>
                                                                <strong>THÔNG TIN MỨC GIẢM:</strong>
                                                                <br/>
                                                                    Laptop: Giảm 3%<br/>
                                                                        Điện thoại,Tablet, Đồng hồ thông minh của hãng Apple: Giảm 3%<br/>
                                                                            Điện thoại, Tablet, Đồng hồ thông minh của hãng Samsung: Giảm 3% - 5% - 8%<br/>
                                                                                Điện thoại, Tablet, Đồng hồ thông minh của các hãng khác (Trừ Samsung, Apple, Laptop): Giảm 3% - 5% - 8% - 15%<br/>
                                                                                    <br/>
                                                                                        <strong>LƯU Ý: </strong>
                                                                                        <br/>
                                                                                            Coupon áp dụng đồng thời với khuyến mãi khác, trừ khuyến mãi giá sốc, giảm giá Visa, Mastercard và các mã giảm giá khác<br/>
                                                                                                Không áp dụng trả góp lãi suất đặc biệt (0%, 0.5%, 1%, 1.29%)<br/>
                                                                                                    Được áp dụng chuyển hàng<br/>
                                                                                                        Không áp dụng kèm chương trình Pre Order<br/>
                                                                                                            Không áp dụng đối với sản phẩm cũ<br/>Không áp dụng trả góp qua cổng ngân lượng
                        </div>
                    </div>
                </div>
                <script>
                    var type = 0;
                    var urlCoupon = "/mua-online-gia-re-hon/Home/GetCoupon?ran=";
                    var flagNCDGetCoupon = false;

                    function NCDGetCoupon() {
                        if (flagNCDGetCoupon)
                            return false;
                        flagNCDGetCoupon = true;
                        $('#loading-ncd').show();
                        $.ajax({
                            url: urlCoupon + Math.random(),
                            type: 'POST',
                            data: {
                                phoneNumber: $('#txtNCDPhoneNumber').val(),
                                type: type
                            },
                            success: function(e) {
                                flagNCDGetCoupon = false;
                                $('#loading-ncd').fadeOut();
                                alert(e.error);
                            },
                            error: function(e) {
                                flagNCDGetCoupon = false;
                                $('#loading-ncd').fadeOut();
                                alert('Hệ thống đang được nâng cấp. Bạn vui lòng chờ trong giây lát');
                            }
                        });
                        return false;
                    }

                    function openSdt() {
                        $(".cpGift .open").addClass("hide");
                        $(".cpGift .us").removeClass("hide");
                    }

                    function showCpPopup() {
                        $(".popcoupon").removeClass("hide");
                    }

                    function closeCpPopup() {
                        $(".popcoupon").addClass("hide");
                    }
                </script>
                
                <div class="tsh">
                    <span>
                        <a href="https://www.thegioididong.com/giao-trong-1-gio">Nhận hàng trong 90 phút</a>
                    </span>
                </div>

                <div class="area_promotion zero">
                    <strong data-count="2">Khuyến mãi</strong>
                    <div class="pro-img">
                        <ul class="t2">
                            <li class="notapply" data-date="8/31/2018 11:00:00 PM" data-g="Tặng" data-return="1000000">
                                <label>
                                    <img class="prmImg" src="<?= $assetsPrefix?>/jpg/phieu-mua-hang-1-trieu-1-200x200.jpg" data-promoid="383054" data-prdid="142314" title="Click để xem hình lớn" />
                                    <h3>
                                        <span>
                                            Phiếu mua hàng trị giá 1 triệu
                                            <label class='sao'>*</label>
                                        </span>
                                    </h3>
                                </label>
                            </li>
                        </ul>
                    </div>
                    <div class="infopr">
                        <span class="pro385621 " data-g="WebNote" data-date="8/31/2018 11:00:00 PM" data-return="">
                            Cơ hội trúng 38 xe Wave Alpha khi trả góp Home Credit <a href=https://www.thegioididong.com/tin-tuc/mua-tra-gop-home-credit-co-hoi-trung-38-xe-wave-alpha-1104361>Xem chi tiết</a>
                        </span>
                    </div>
                    <div class="clr"></div>
                    <div class="not-repay">* Không áp dụng khi mua trả góp 0%</div>
                </div>
                <script>
                    function ChoosePromtion(groupid, index, t) {
                        $('.pro' + groupid).removeClass('active');
                        $(t).addClass('active');
                        var protxt = $(t).find(".dscp u").html();
                        $('.notechoose').html('Bạn chọn khuyến mãi: ' + protxt).show();
                        BuyChoose();
                    }

                    function BuyChoose() {
                        $('.buy_now').unbind("click");
                        if ($('.option-repay').hasClass('active')) {
                            $('.buy_now').unbind("click");
                            $('.buy_now').click(function() {
                                window.location.href = $('.buy_ins').attr('href');
                                return false;
                            });
                        } else {
                            $('.buy_now').click(function() {
                                var paraPromotion = '';
                                $('.area_promotion .pro-chosse.active').each(function() {
                                    paraPromotion += $(this).attr('data-group') + ";" + $(this).attr('data-index') + "$";
                                });
                                var url = $(this).attr('href');
                                if (paraPromotion != '') {
                                    url += '&promotion=' + paraPromotion
                                    $(this).attr('href', url);
                                }
                            });
                        }

                    }
                </script>
                <div class="notechoose"></div>
                <div class="area_order">
                    <a href="https://www.thegioididong.com/them-vao-gio-hang?ProductId=106880" class="buy_now" data-value="106880">
                        <b>Mua ngay </b>
                        <span>Giao trong 90 ph &#250;t hoặc nhận tại siêu thị</span>
                    </a>
                    <a class="buy_repay " href="https://www.thegioididong.com/tra-gop/laptop/apple-macbook-air-mqd42sa-a-i5-5350u">
                        <b>Mua trả góp 0%</b>
                        <span>Thủ tục đơn giản</span>
                    </a>
                    <a class="buy_repay s " href="https://www.thegioididong.com/tra-gop/laptop/apple-macbook-air-mqd42sa-a-i5-5350u?m=credit">
                        <b>Trả góp 0% qua thẻ</b>
                        <span>Visa, Master, JCB</span>
                    </a>
                </div>
                <div class="callorder">
                    <div class="ct">
                        <span>
                            Gọi đặt mua: <a href="tel:18001060">1800.1060</a>
                            (miễn phí - 7:30-22:00)
                        </span>
                    </div>
                </div>
            </aside>
            <div class="rightInfo">
                <div class="checkexist">
                    <strong class="kthlbl" onclick="$('.layerstore').toggle()">
                        <i class="iconshop-local"></i>
                        Kiểm tra có hàng tại nơi bạn ở không?
                    </strong>
                    <div class="layerstore">
                        <div class="city">TP.Hồ Chí Minh</div>
                        <div class="listcity">
                            <div class="searchlocal">
                                <form>
                                    <input name="txtPro" id="txtPro" type="text" placeholder="Nhập tỉnh, thành để tìm nhanh">
                                    <button type="button" class="submit">
                                        <i class="iconmobile-search"></i>
                                    </button>
                                </form>
                            </div>
                            <div class="scroll">
                                <aside>
                                    <a data-value='3'>TP.Hồ Chí Minh</a>
                                    <a data-value='5'>Hà Nội</a>
                                    <a data-value='9'>Đà Nẵng</a>
                                    <a data-value='82'>An Giang</a>
                                    <a data-value='102'>Bà Rịa - Vũng Tàu</a>
                                    <a data-value='103'>Bắc Giang</a>
                                    <a data-value='104'>Bắc Kạn</a>
                                    <a data-value='105'>Bạc Liêu</a>
                                    <a data-value='106'>Bắc Ninh</a>
                                    <a data-value='107'>Bến Tre</a>
                                    <a data-value='108'>Bình Định</a>
                                    <a data-value='109'>Bình Dương</a>
                                    <a data-value='110'>Bình Phước</a>
                                    <a data-value='111'>Bình Thuận</a>
                                    <a data-value='81'>Cà Mau</a>
                                    <a data-value='7'>Cần Thơ</a>
                                    <a data-value='112'>Cao Bằng</a>
                                    <a data-value='6'>Đắk Lắk</a>
                                    <a data-value='113'>Đắk Nông</a>
                                    <a data-value='114'>Điện Biên</a>
                                    <a data-value='8'>Đồng Nai</a>
                                    <a data-value='115'>Đồng Tháp</a>
                                    <a data-value='116'>Gia Lai</a>
                                    <a data-value='117'>Hà Giang</a>
                                    <a data-value='118'>Hà Nam</a>
                                    <a data-value='120'>Hà Tĩnh</a>
                                    <a data-value='121'>Hải Dương</a>
                                    <a data-value='101'>Hải Phòng</a>
                                    <a data-value='122'>Hậu Giang</a>
                                    <a data-value='123'>Hòa Bình</a>
                                    <a data-value='124'>Hưng Yên</a>
                                </aside>
                                <aside>
                                    <a data-value='125'>Khánh Hòa</a>
                                    <a data-value='126'>Kiên Giang</a>
                                    <a data-value='127'>Kon Tum</a>
                                    <a data-value='128'>Lai Châu</a>
                                    <a data-value='129'>Lâm Đồng</a>
                                    <a data-value='130'>Lạng Sơn</a>
                                    <a data-value='131'>Lào Cai</a>
                                    <a data-value='132'>Long An</a>
                                    <a data-value='133'>Nam Định</a>
                                    <a data-value='134'>Nghệ An</a>
                                    <a data-value='135'>Ninh Bình</a>
                                    <a data-value='136'>Ninh Thuận</a>
                                    <a data-value='137'>Phú Thọ</a>
                                    <a data-value='138'>Phú Yên</a>
                                    <a data-value='139'>Quảng Bình</a>
                                    <a data-value='140'>Quảng Nam</a>
                                    <a data-value='141'>Quảng Ngãi</a>
                                    <a data-value='142'>Quảng Ninh</a>
                                    <a data-value='143'>Quảng Trị</a>
                                    <a data-value='144'>Sóc Trăng</a>
                                    <a data-value='145'>Sơn La</a>
                                    <a data-value='146'>Tây Ninh</a>
                                    <a data-value='147'>Thái Bình</a>
                                    <a data-value='148'>Thái Nguyên</a>
                                    <a data-value='149'>Thanh Hóa</a>
                                    <a data-value='150'>Thừa Thiên Huế</a>
                                    <a data-value='151'>Tiền Giang</a>
                                    <a data-value='152'>Trà Vinh</a>
                                    <a data-value='153'>Tuyên Quang</a>
                                    <a data-value='154'>Vĩnh Long</a>
                                    <a data-value='155'>Vĩnh Phúc</a>
                                    <a data-value='156'>Yên Bái</a>
                                </aside>
                            </div>
                        </div>
                        <div class="dist">
                            <span>Chọn quận, huyện</span>
                        </div>
                        <div class="listdist">
                            <div class="searchlocal">
                                <form>
                                    <input name="txtDis" id="txtDis" type="text" placeholder="Nhập quận, huyện để tìm nhanh">
                                    <button type="button" class="submit">
                                        <i class="iconmobile-search"></i>
                                    </button>
                                </form>
                            </div>
                            <div class="scroll">
                                <aside>
                                    <a data-value='16'>Quận 1</a>
                                    <a data-value='17'>Quận 2</a>
                                    <a data-value='18'>Quận 3</a>
                                    <a data-value='19'>Quận 4</a>
                                    <a data-value='20'>Quận 5</a>
                                    <a data-value='21'>Quận 6</a>
                                    <a data-value='22'>Quận 7</a>
                                    <a data-value='23'>Quận 8</a>
                                    <a data-value='24'>Quận 9</a>
                                    <a data-value='25'>Quận 10</a>
                                    <a data-value='26'>Quận 11</a>
                                    <a data-value='27'>Quận 12</a>
                                </aside>
                                <aside>
                                    <a data-value='30'>Quận Tân Bình</a>
                                    <a data-value='33'>Quận Tân Phú</a>
                                    <a data-value='52'>Quận Phú Nhuận</a>
                                    <a data-value='29'>Quận Gò Vấp</a>
                                    <a data-value='51'>Quận Bình Thạnh</a>
                                    <a data-value='28'>Quận Thủ Đức</a>
                                    <a data-value='31'>Quận Bình Tân</a>
                                    <a data-value='32'>Huyện Hóc Môn</a>
                                    <a data-value='34'>Huyện Củ Chi</a>
                                    <a data-value='35'>Huyện Nhà Bè</a>
                                    <a data-value='36'>Huyện Bình Chánh</a>
                                    <a data-value='61'>Huyện Cần Giờ</a>
                                </aside>
                            </div>
                        </div>
                        <div class="clr"></div>
                        <div class="choosecolor">
                            <span>Màu: Bạc</span>
                            <div class="listcolor ">
                                <a class="choosed" data-value="0220042000234">
                                    <div>
                                        <img width="30" height="30" data-original="//cdn.tgdd.vn/Products/Images/44/106880/apple-macbook-air-mqd42sa-a-i5-5350u-180x125.png" class="lazy">
                                    </div>
                                    Bạc

                                </a>
                            </div>
                        </div>
                        <div class="clr"></div>
                        <div class="listmarket" style="display: none">
                            <strong class="dsstlbl">Danh sách siêu thị</strong>
                            <ul class="listst"></ul>
                            <div class="clr"></div>
                            <form method="get" action="https://www.thegioididong.com/them-vao-gio-hang" class="frm_store">
                                <input id="ProvinceId" name="ProvinceId" type="hidden" value="3" />
                                <input id="DistricId" name="DistricId" type="hidden" value="0" />
                                <input id="ProductCode" name="ProductCode" type="hidden" value="0220042000234" />
                                <input id="StoreId" name="StoreId" type="hidden" value="0" />
                                <input id="ProductId" name="ProductId" type="hidden" value="106880" />
                                <input id="StockStatus" name="StockStatus" type="hidden" value="" />
                                <input id="IsStock" name="IsStock" type="hidden" value="true" />
                                <button type="submit" class="none btn_store"></button>
                            </form>
                        </div>
                    </div>
                    <div class="clr"></div>
                </div>
                <ul class="policy ">
                    <li class="inpr">
                        <span>
                            Trong hộp có:
                            <a class="stdImg" href="javascript:void(0)" onclick="showGalleryPS(100,0);">
                                D &#226;y nguồn, S &#225;ch hướng dẫn, Th &#249;ng m &#225;y, Adapter sạc(+ c &#243;c sạc) <i class='icondetail-camera standkit' href='<?= $assetsPrefix?>/jpg/apple-macbook-air-mqd42sa-a-i5-5350u-bo-ban-hang-org-1-org.jpg'></i>
                            </a>
                        </span>
                    </li>
                    <li class="wrpr">
                        <span>
                            Bảo hành chính hãng 12 tháng. <a href="javascript:openPopWrt();">Xem chi tiết</a>
                        </span>
                    </li>
                    <li class="timeship">
                        Giao hàng tận nơi miễn phí trong <strong>60 phút</strong>
                        . <a href="https://www.thegioididong.com/giao-hang" target="blank">Tìm hiểu</a>
                    </li>
                    <li>
                        <i class='icon-poltick'></i>
                        <strong>1 đổi 1 trong 1 tháng</strong>
                        nếu lỗi, đổi sản phẩm tại nhà trong 1 ngày.
                    </li>
                </ul>
                <div class='promote'>
                    <b>ƯU ĐÃI THÊM</b>
                    <span>
                        Mua hàng ở 76 siêu thị nhận Phiếu mua hàng dùng trải nghiệm dịch vụ tại Bách Hóa Xanh (áp dụng cho SP đang có KM phiếu mua hàng) <a href='https://www.thegioididong.com/tin-tuc/mua-dien-thoai-the-gioi-di-dong-tang-pmh-bach-hoa-xanh-1092607' target='_blank'>Xem chi tiết</a>
                    </span>
                </div>
                <div class="bannerdt">
                    <a href='https://www.thegioididong.com/tin-tuc/laptop-ban-chay-nhat-cua-tgdd-trong-ngay-hoi-back-to-school-1104556' onclick="jQuery.ajax({ url: '//www.thegioididong.com/bannertracking?bid=23011&r='+ (new Date).getTime(),   async: true, cache: false });">
                        <img src='<?= $assetsPrefix?>/png/27_07_2018_16_42_31_laptop-ban-chay---desktop.png' alt='Laptop bán chạy B2S' />
                    </a>
                </div>
                <div class="wrap_wrtp hide">
                    <div class="pop">
                        <div class="hdpop">
                            CHÍNH SÁCH BẢO HÀNH, ĐỔI TRẢ
                            <a href="javascript:closePopWrt();" class="closehd">
                                <span>✖</span>
                            </a>
                        </div>
                        <div class="ctW">
                            <span class="tlt">BẢO HÀNH CHÍNH HÃNG:</span>
                            <span>
                                Thân máy 12 tháng, pin 12 th &#225;ng, sạc 12 th &#225;ng - <a class="pWarr" target="_blank" href="https://www.thegioididong.com/bao-hanh/macbook-imac">Xem điểm bảo hành Macbook - iMac</a>
                            </span>
                            <div class="cs">
                                <div>
                                    <strong>
                                        <span style="font-size:14px;">CHÍNH SÁCH ĐỔI TRẢ:</span>
                                    </strong>
                                </div>
                                <table border="1" cellpadding="0" cellspacing="0" style="width:695px;" width="696">
                                    <tbody>
                                        <tr>
                                            <td style="width:102px;"></td>
                                            <td style="width: 282px; text-align: center;">
                                                <strong>THÁNG 1</strong>
                                            </td>
                                            <td style="width: 312px; text-align: center;">
                                                <strong>THÁNG 2 - 12</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width:102px;height:130px;">
                                                <strong>
                                                    SẢN PHẨM<br/>
                                                        LỖI DO NHÀ<br/>SẢN XUẤT
                                                </strong>
                                            </td>
                                            <td style="width:282px;height:130px;">
                                                <strong>ĐỔI SẢN PHẨM HOẶC HOÀN TIỀN:</strong>
                                                <br/>
                                                    -1 đổi 1 hoặc đổi sản phẩm khác trong trường hợp hết hàng<br/>
                                                        -Hoặc hoàn tiền mất phí 20%<br/>
                                            </td>
                                            <td style="width:312px;height:130px;">
                                                <strong>GỬI BẢO HÀNH:</strong>
                                                <br/>
                                                    -Hỗ trợ sản phẩm dùng trong thời gian chờ bảo hành (giá trị thấp hơn)<br/>Hoặc hoàn tiền mất phí, thêm 5%/tháng so với tháng thứ 1 (20%)
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width:102px;height:61px;">
                                                <strong>
                                                    SẢN PHẨM<br/>KHÔNG LỖI
                                                </strong>
                                            </td>
                                            <td style="width:282px;height:61px;">Hoàn tiền mất phí 20%</td>
                                            <td style="width:312px;height:61px;">Hoàn tiền mất phí, thêm 5%/tháng so với tháng thứ 1 (20%)</td>
                                        </tr>
                                        <tr>
                                            <td style="width:102px;height:65px;">
                                                <strong>
                                                    SẢN PHẨM<br/>
                                                        LỖI DO<br/>NGƯỜI DÙNG
                                                </strong>
                                            </td>
                                            <td colspan="2" style="width:595px;height:65px;">
                                                <strong>KHÔNG BẢO HÀNH, ĐỔI TRẢ.</strong>
                                                Thegioididong.com hỗ trợ chuyển TTBH hãng, khách hàng chịu phí sửa chữa.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <a class="xct" href="https://www.thegioididong.com/chinh-sach-bao-hanh-san-pham">XEM CHI TIẾT</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="clr"></div>
        <div class="box_content">
            <aside class="left_content">
                <div class="characteristics">
                    <h2>Đặc điểm nổi bật của Apple Macbook Air MQD42SA/A i5 1.8GHz/8GB/256GB (2017)</h2>
                    <!-- slider -->
                    <div id="owl-detail" class="owl-carousel owl-detail">
                        <div class="item">
                            <img class="lazyOwl" data-src="<?= $assetsPrefix?>/jpg/-apple-macbook-air-mqd42sa-a-i5-5350u-tk.jpg" />
                        </div>
                        <div class="item">
                            <img class="lazyOwl" data-src="<?= $assetsPrefix?>/jpg/-apple-macbook-air-mqd42sa-a-i5-5350u-ch.jpg" />
                            <a class="slLnk" target="_blank" href="https://www.thegioididong.com/tin-tuc/tim-hieu-vi-xu-ly-may-tinh-cpu-intel-596066#broadwell">Tìm hiểu thêm</a>
                        </div>
                        <div class="item">
                            <img class="lazyOwl" data-src="<?= $assetsPrefix?>/jpg/-apple-macbook-air-mqd42sa-a-i5-5350u-o-cung.jpg" />
                            <a class="slLnk" target="_blank" href="https://www.thegioididong.com/hoi-dap/o-cung-ssd-la-gi-923073">Tìm hiểu thêm</a>
                        </div>
                        <div class="item">
                            <img class="lazyOwl" data-src="<?= $assetsPrefix?>/jpg/-apple-macbook-air-mqd42sa-a-i5-5350u-fix.jpg" />
                            <a class="slLnk" target="_blank" href="https://www.thegioididong.com/tin-tuc/cac-cong-nghe-hien-thi-tren-man-hinh-laptop-597377#ledbacklit">Tìm hiểu thêm</a>
                        </div>
                        <div class="item">
                            <img class="lazyOwl" data-src="<?= $assetsPrefix?>/jpg/vi-vn-apple-macbook-air-mqd42sa-a-i5-5350u-kn.jpg" />
                            <a class="slLnk" target="_blank" href="https://www.thegioididong.com/hoi-dap/magsafe-2-tren-macbook-la-gi-959220">Tìm hiểu thêm</a>
                        </div>
                        <div class="item">
                            <img class="lazyOwl" data-src="<?= $assetsPrefix?>/jpg/vi-vn-apple-macbook-air-mqd42sa-a-i5-5350u-pin.jpg" />
                        </div>
                        <div class="item">
                            <img alt="Bộ sản phẩm chuẩn" data-src="//cdn.tgdd.vn/Products/Images/44/106880/Kit/apple-macbook-air-mqd42sa-a-i5-5350u-bo-ban-hang-org-1-org.jpg" class="lazyOwl" />
                            <div class="des">
                                <p>Bộ sản phẩm chuẩn: D &#226;y nguồn, S &#225;ch hướng dẫn, Th &#249;ng m &#225;y, Adapter sạc(+ c &#243;c sạc)</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="boxArticle">
                    <article class="area_article" style="height: 500px">
                        <h2>
                            <strong>Macbook Air MQD42SA/A i5 5350U</strong>
                            <strong>với thiết kế vỏ nhôm nguyên khối Unibody rất đẹp, chắc chắn và sang trọng. Máy siêu mỏng và siêu nhẹ, hiệu năng ổn định mượt mà, thời lượng pin cực lâu, phục vụ tốt cho nhu cầu làm việc lẫn giải trí.</strong>
                        </h2>
                        <h3>
                            <strong>Thiết kế siêu mỏng và nhẹ</strong>
                        </h3>
                        <p>
                            Với thiết kế đặc trưng của dòng <a href="https://www.thegioididong.com/tim-kiem?key=Macbook+Air+" target="_blank" title="MacBook Air">MacBook Air</a>
                            , phiên bản này chỉ mỏng <strong>1.7 cm</strong>
                            và trọng lượng là <strong>1.35 kg</strong>
                            , rất tiện lợi và dễ dàng để bạn luôn mang theo bên mình. Logo quả táo Apple phát sáng tạo nên đặc trưng riêng khác biệt.
                        </p>
                        <p>
                            <a class="preventdefault" href="<?= $assetsPrefix?>/jpg/apple-macbook-air-mqd42sa-a-i5-5350u-tk.jpg" onclick="return false;">
                                <img alt="Thiết kế siêu mỏng và nhẹ" data-original="https://cdn.tgdd.vn/Products/Images/44/106880/apple-macbook-air-mqd42sa-a-i5-5350u-tk.jpg" class='lazy' data-original="https://cdn.tgdd.vn/Products/Images/44/106880/apple-macbook-air-mqd42sa-a-i5-5350u-tk.jpg" title="Thiết kế siêu mỏng và nhẹ" />
                            </a>
                        </p>
                        <p>Ngoài ra thiết kế logo quả táo phát sáng tạo nên đặc trưng riêng khác biệt của Apple cũng được giữ nguyên trên tác phẩm này.</p>
                        <p>
                            <a class="preventdefault" href="<?= $assetsPrefix?>/jpg/apple-macbook-air-mqd42sa-a-i5-5350u-logo.jpg" onclick="return false;">
                                <img alt="Logo táo phát sáng" data-original="https://cdn.tgdd.vn/Products/Images/44/106880/apple-macbook-air-mqd42sa-a-i5-5350u-logo.jpg" class='lazy' data-original="https://cdn.tgdd.vn/Products/Images/44/106880/apple-macbook-air-mqd42sa-a-i5-5350u-logo.jpg" title="Logo táo phát sáng" />
                            </a>
                        </p>
                        <h3>
                            <strong>Hiệu năng mượt mà </strong>
                        </h3>
                        <p>
                            <strong>Macbook Air MQD42SA/A i5 5350U</strong>
                            có bộ xử lý <a href="https://www.thegioididong.com/tin-tuc/tim-hieu-vi-xu-ly-may-tinh-cpu-intel-596066#broadwell" target="_blank" title="Intel Core i5 Broadwell">Intel Core i5 Broadwell</a>
                            tốc độ <strong>1.80 GHz</strong>
                            , card đồ họa tích hợp <a href="https://www.thegioididong.com/hoi-dap/intel-hd-graphics-754665#iris" target="_blank" title="Intel HD Graphics 6000">Intel HD Graphics 6000</a>
                            , bộ nhớ <strong>RAM</strong>
                            <strong>8 GB</strong>
                            , cùng ổ cứng lưu trữ <a href="https://www.thegioididong.com/hoi-dap/o-cung-ssd-la-gi-923073" target="_blank" title="SSD">SSD</a>
                            <strong>256 GB</strong>
                            giúp máy chạy mượt mà các thao tác sử dụng.
                        </p>
                        <p>
                            <a class="preventdefault" href="<?= $assetsPrefix?>/jpg/apple-macbook-air-mqd42sa-a-i5-5350u-dh.jpg" onclick="return false;">
                                <img alt="Hiệu năng mượt mà" data-original="https://cdn.tgdd.vn/Products/Images/44/106880/apple-macbook-air-mqd42sa-a-i5-5350u-dh.jpg" class='lazy' data-original="https://cdn.tgdd.vn/Products/Images/44/106880/apple-macbook-air-mqd42sa-a-i5-5350u-dh.jpg" title="Hiệu năng mượt mà" />
                            </a>
                        </p>
                        <h3>
                            <strong>Cổng Thunderbolt hiện đại</strong>
                        </h3>
                        <p>
                            <strong>Macbook Air MQD42SA/A i5 5350U</strong>
                            còn được trang bị cổng <a href="https://www.thegioididong.com/tin-tuc/intel-cong-bo-chuan-thunderbolt-moi-nhanh-hon-gap--516567" target="_blank" title="Thunderbolt thế hệ 2">Thunderbolt thế hệ 2</a>
                            cho tốc độ truyền tải dữ liệu cao hơn gấp đôi so với <strong>Thunderbolt 1</strong>
                            , đồng thời còn có thể xuất hình ảnh độ phân giải cao ra màn hình 4K.
                        </p>
                        <p>
                            <a class="preventdefault" href="<?= $assetsPrefix?>/jpg/apple-macbook-air-mqd42sa-a-i5-5350u-kn.jpg" onclick="return false;">
                                <img alt="Cổng Thunderbolt hiện đại" data-original="https://cdn.tgdd.vn/Products/Images/44/106880/apple-macbook-air-mqd42sa-a-i5-5350u-kn.jpg" class='lazy' data-original="https://cdn.tgdd.vn/Products/Images/44/106880/apple-macbook-air-mqd42sa-a-i5-5350u-kn.jpg" title="Cổng Thunderbolt hiện đại" />
                            </a>
                        </p>
                        <h3>
                            <strong>Màn hình</strong>
                        </h3>
                        <p>
                            <strong>Macbook Air MQD42SA/A</strong>
                            có màn hình rộng <strong>13.3 inch</strong>
                            , độ phân giải là <strong>WXGA+(1440 x 900)</strong>
                            sử dụng công nghệ màn hình <a href="https://www.thegioididong.com/tin-tuc/cac-cong-nghe-hien-thi-tren-man-hinh-laptop-597377#ledbacklit" target="_blank" title="LED Backlit">LED Backlit</a>
                            , hình ảnh hiển thị khá chất lượng, tươi sáng.
                        </p>
                        <p>
                            <a class="preventdefault" href="<?= $assetsPrefix?>/jpg/apple-macbook-air-mqd42sa-a-i5-5350u-mh.jpg" onclick="return false;">
                                <img alt="Màn hình" data-original="https://cdn.tgdd.vn/Products/Images/44/106880/apple-macbook-air-mqd42sa-a-i5-5350u-mh.jpg" class='lazy' data-original="https://cdn.tgdd.vn/Products/Images/44/106880/apple-macbook-air-mqd42sa-a-i5-5350u-mh.jpg" title="Màn hình" />
                            </a>
                        </p>
                        <h3>
                            <strong>Thời lượng pin lên đến 12 giờ sử dụng</strong>
                        </h3>
                        <p>
                            Vấn đề pin luôn là một trăn trở khi dùng các máy laptop mỏng, nhưng với <strong>Macbook Air MQD42SA/A i5</strong>
                            thời gian khoảng <strong>12 tiếng</strong>
                            sử dụng sau một lần sạc đầy là một ưu điểm rất xứng đáng để người dùng lựa chọn.
                        </p>
                        <div class="boxRtAtc">
                            <div class="likewied">
                                <div class="likeshare">
                                    <div class="fb-like" data-href="/laptop/apple-macbook-air-mqd42sa-a-i5-5350u" data-layout="button_count" data-action="like" data-size="small" data-show-faces="false" data-share="true"></div>
                                </div>
                                <a onclick="showRatingAtc(this)" class="messenger">
                                    <b class="iconcom-likeAtc"></b>
                                    Không hài lòng bài viết
                                </a>
                            </div>
                            <div class="bifRtCt bRtAtc hide">
                                <textarea name="txtContent" rows="3" placeholder="Mời bạn góp ý để chúng tôi phục vụ tốt hơn"></textarea>
                                <span>Hãy để lại thông tin để được hỗ trợ khi cần thiết (Không bắt buộc):</span>
                                <div class="ifRtGd" data-val="0">
                                    <label onclick="rtAtcChangeGder(1)" class="ifGdM">
                                        <i></i>
                                        Anh
                                    </label>
                                    <label onclick="rtAtcChangeGder(2)" class="ifGdFm">
                                        <i></i>
                                        Chị
                                    </label>
                                </div>
                                <input type="text" name="txtFullName" placeholder="Họ tên">
                                <input type="text" name="txtPhoneNumber" placeholder="Số điện thoại">
                                <input type="text" name="txtEmail" placeholder="Email">
                                <button type="submit" onclick="sendRatingContent(2)">
                                    Gửi góp ý<span>Cam kết bảo mật thông tin cá nhân</span>
                                </button>
                                <label class="alert"></label>
                            </div>
                        </div>
                    </article>
                    <p class="show-more" style="display: block;" onclick="showArticle();">
                        <a href="javascript:;" class="readmore">Đọc thêm </a>
                    </p>
                    <div class="bottom_order ">
                        <div class="info_sp">
                            <img class="lazy" width="70" height="70" data-original="<?= $assetsPrefix?>/jpg/apple-macbook-air-mqd42sa-a-i5-5350u-8gb-256gb-bac-450x300-230x153.jpg" />
                            <h3>Laptop Apple Macbook Air MQD42SA/A i5 1.8GHz/8GB/256GB (2017)</h3>
                            <strong>28.990.000₫</strong>
                            <span></span>
                        </div>
                        <div class="area_order">
                            <a href="https://www.thegioididong.com/them-vao-gio-hang?ProductId=106880" class="buy_now" data-value="106880">
                                <b>Mua ngay </b>
                                <span>Giao trong 90 ph &#250;t hoặc nhận tại siêu thị</span>
                            </a>
                            <a class="buy_repay " href="https://www.thegioididong.com/tra-gop/laptop/apple-macbook-air-mqd42sa-a-i5-5350u">
                                <b>Mua trả góp 0%</b>
                                <span>Thủ tục đơn giản</span>
                            </a>
                            <a class="buy_repay s " href="https://www.thegioididong.com/tra-gop/laptop/apple-macbook-air-mqd42sa-a-i5-5350u?m=credit">
                                <b>Trả góp 0% qua thẻ</b>
                                <span>Visa, Master, JCB</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="clr"></div>
                <div class="compare">
                    <div class="tcpr">
                        <h4>So sánh với các sản phẩm tương tự</h4>
                        <div class="sggProd">
                            <form action="javascript:void(0)">
                                <input type="text" placeholder="Nhập tên sản phẩm bạn muốn so sánh" onkeyup="SuggestCompare(this,event)" />
                                <button type="submit">
                                    <i class="icontgdd-search"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <ul>
                        <li class=laptop>
                            <a href="apple-macbook-air-mqd42sa-a-i5-5350u">
                                <img data-original="https://cdn.tgdd.vn/Products/Images/44/106880/apple-macbook-air-mqd42sa-a-i5-5350u-8gb-256gb-bac-450x300-230x153.jpg" class="lazy" />
                                <span class="bdx">Bạn đang xem: </span>
                                <h3>Apple Macbook Air MQD42SA/A i5 1.8GHz (2017)</h3>
                                <strong>28.990.000₫</strong>
                                <div class="desc">
                                    <span>CPU Intel Core i5 Broadwell, 1.80 GHz</span>
                                    <span>RAM 8 GB</span>
                                    <span>Pin Khoảng 12 tiếng</span>
                                </div>
                            </a>
                        </li>
                        <li class=laptop>
                            <a href="https://www.thegioididong.com/laptop/lg-14z970-gah52a5">
                                <img data-original="https://cdn.tgdd.vn/Products/Images/44/113889/lg-14z970-gah52a5-450x300-230x153.jpg" class="lazy" />
                                <span class="bdx"></span>
                                <h3>LG Gram 14Z970 i5 7200U (AH52A5)</h3>
                                <strong>28.190.000₫</strong>
                                <div class="desc">
                                    <span>CPU Intel Core i5 Kabylake, 2.50 GHz</span>
                                    <span>RAM 8 GB</span>
                                    <span>Pin Li-Ion 4 cell</span>
                                </div>
                            </a>
                            <a href="https://www.thegioididong.com/laptop/apple-macbook-air-mqd42sa-a-i5-5350u-vs-lg-14z970-gah52a5" class="compdetail">So sánh chi tiết</a>
                        </li>
                        <li class=laptop>
                            <a href="https://www.thegioididong.com/laptop/asus-gl503ge-en021t">
                                <img data-original="https://cdn.tgdd.vn/Products/Images/44/164238/asus-gl503ge-en021t-19-400x400.jpg" class="lazy" />
                                <span class="bdx"></span>
                                <h3>Asus Scar GL503GE i7 8750H (EN021T)</h3>
                                <strong>29.990.000₫</strong>
                                <div class="desc">
                                    <span>CPU Intel Core i7 Coffee Lake, 2.20 GHz</span>
                                    <span>RAM 8 GB</span>
                                    <span>Pin Li-Ion 4 cell</span>
                                </div>
                            </a>
                            <a href="https://www.thegioididong.com/laptop/apple-macbook-air-mqd42sa-a-i5-5350u-vs-asus-gl503ge-en021t" class="compdetail">So sánh chi tiết</a>
                        </li>
                        <li class=laptop>
                            <a href="https://www.thegioididong.com/laptop/hp-envy-13-ah0027tu-4me94pa">
                                <img data-original="https://cdn.tgdd.vn/Products/Images/44/169423/hp-envy-13-ah0027tu-4me94pa-14-400x400.jpg" class="lazy" />
                                <span class="bdx"></span>
                                <h3>HP Envy 13 ah0027TU i7 8550U (4ME94PA)</h3>
                                <strong>26.990.000₫</strong>
                                <span class="rtp">
                                    <i class="iconcom-txtstar"></i>
                                    <i class="iconcom-txtstar"></i>
                                    <i class="iconcom-txtstar"></i>
                                    <i class="iconcom-txtstar"></i>
                                    <i class="iconcom-txtstar"></i>
                                </span>
                                <div class="desc">
                                    <span>CPU Intel Core i7 Kabylake Refresh, 1.80 GHz</span>
                                    <span>RAM 8 GB</span>
                                    <span>Pin Li-Ion 4 cell</span>
                                </div>
                            </a>
                            <a href="https://www.thegioididong.com/laptop/apple-macbook-air-mqd42sa-a-i5-5350u-vs-hp-envy-13-ah0027tu-4me94pa" class="compdetail">So sánh chi tiết</a>
                        </li>
                    </ul>
                </div>
                <div class="clr"></div>
                <div class="comdetail">
                    <div class="boxRatingCmt" id="boxRatingCmt">
                        <div class="hrt" id="danhgia">
                            <div class="tltRt first">
                                <h3 data-s="" data-gpa="" data-c="">H &#227;y l &#224;người đầu ti &#234;n đ&#225;nh gi &#225;Apple Macbook Air MQD42SA/A i5 1.8GHz (2017)</h3>
                            </div>
                        </div>
                        <div class="toprt">
                            <div class="crt">
                                <div class="bcrt">
                                    <a href="javascript:showInputRating()">Gửi đánh giá của bạn</a>
                                </div>
                            </div>
                            <div class="clr"></div>
                            <form class="input" name="fRatingComment" style="display: none">
                                <input type="hidden" name="hdfStar" id="hdfStar" value="0" />
                                <input type="hidden" name="hdfProductID" id="hdfProductID" value="106880" />
                                <input type="hidden" name="hdfRatingImg" class="hdfRatingImg" />
                                <div class="ips">
                                    <span>Chọn đánh giá của bạn</span>
                                    <span class="lStar">
                                        <i class="iconcom-unstar" id="s1"></i>
                                        <i class="iconcom-unstar" id="s2"></i>
                                        <i class="iconcom-unstar" id="s3"></i>
                                        <i class="iconcom-unstar" id="s4"></i>
                                        <i class="iconcom-unstar" id="s5"></i>
                                    </span>
                                    <span class="rsStar hide"></span>
                                </div>
                                <div class="clr"></div>
                                <div class="ipt hide">
                                    <div class="ct">
                                        <textarea name="fRContent" placeholder="Nhập đánh giá về sản phẩm (tối thiểu 80 ký tự)" onkeyup="countTxtRating()"></textarea>
                                        <div class="extCt">
                                            <label onclick="javascript:void(0);" class="lnksimg btnRatingUpload">
                                                <i class="iconcom-pict"></i>
                                                Đính kèm ảnh
                                            </label>
                                            <span class="ckt"></span>
                                            <input id="hdFileRatingUpload" type="file" class="hide" accept="image/x-png, image/gif, image/jpeg" />
                                        </div>
                                    </div>
                                    <div class="if">
                                        <input type="text" name="fRName" placeholder="Họ tên" />
                                        <input type="text" name="fRPhone" placeholder="Số điện thoại" />
                                        <input type="text" name="fREmail" placeholder="Email" />
                                        <a href="javascript:submitRatingComment();">GỬI ĐÁNH GIÁ</a>
                                    </div>
                                    <div class="clr"></div>
                                    <ul class="resImg hide"></ul>
                                    <span class="lbMsgRt"></span>
                                </div>
                            </form>
                        </div>
                        <div class="wrap_fdback hide">
                            <div class="pop">
                                <div class="hdpop">
                                    Tìm hiều thêm về Gửi đánh giá để đổi quà
                                    <a href="javascript:closeFeedback();" class="closehd">
                                        <i class="iconcom-close"></i>
                                    </a>
                                </div>
                                <div class="ctpop">
                                    <p>Nhằm mang đến cho khách hàng góc nhìn thực tế về sản phẩm. Thegioididong.com tạo nên một sân chơi để khuyến khích mọi người cùng tham gia và xây dựng cộng đồng tư vấn sản phẩm hữu ích nhất cho người dùng. Hãy để lại đánh giá của bạn cho sản phẩm để tích điểm đổi quà nhé!</p>
                                    <i>Lưu ý:</i>
                                    <span>- Mỗi số điện thoại được nhận 1 lần coupon.</span>
                                    <span>- Nội dung đánh giá sẽ được duyệt trong vòng 60 phút. (Thời gian làm việc từ 7h30 đến 22h)</span>
                                    <span>- Coupon dùng để mua tất cả sản phẩm Thegioididong và DienmayXANH đang kinh doanh.</span>
                                    <span>- Coupon có thời hạn sử dụng trong vòng 30 ngày kể từ ngày phát hành.</span>
                                    <span>- Coupon chỉ dùng để mua hàng online.</span>
                                    <span>- Thegioididong chỉ tặng coupon cho các khách hàng đánh giá có số điện thoại trùng với thông tin đã mua của hệ thống.</span>
                                    <span>- Để tối ưu trong việc xác thực thông tin đánh giá, Thegioididong sẽ dựa trên số điện thoại quý khách cung cấp để tra cứu lịch sử mua hàng nhưng vẫn đảm bảo tính bảo mật cho thông tin của quý khách.</span>
                                    <span>
                                        - Thegioididong có quyền kiểm duyệt các đánh giá của quý khách để phù hợp với quy định của website, tham khảo tại đây: <a href="https://www.thegioididong.com/huong-dan-dang-binh-luan" target="_blank">https://www.thegioididong.com/huong-dan-dang-binh-luan</a>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="wrap_comment" title="Bình luận sản phẩm" id="comment" cmtcategoryId="2" siteid="1" detailid="106880" cateid="15" urlpage="" pageSize="5">
                        <div class="tltCmt">
                            <textarea id="txtEditor" name="" cols="" rows="" class="parent_input txtEditor" placeholder="Mời bạn để lại bình luận..." onclick="cmtaddcommentclick();" onkeyup="cmtaddcommentclick();"></textarea>
                            <div class="sortcomment">
                                <div class="statistical hide" id="notifycmtmsg">
                                    Bình luận mới vừa được thêm vào. <a href="javascript:void(0)">Click để xem</a>
                                </div>
                            </div>
                            <div class="clr"></div>
                            <div class="midcmt">
                                <span class="totalcomment">
                                    127 bình luận
                                    <span class="tech" onclick="showCmtTech();">
                                        <i class="iconcom-uncheck"></i>
                                        Xem bình luận kỹ thuật
                                    </span>
                                </span>
                                <div class="s_comment">
                                    <form method="post" onsubmit="return false;">
                                        <input class="cmtKey" type="text" placeholder="Tìm theo nội dung, người gửi..." onkeyup="cmtSearch(event);">
                                        <i class="iconcom-search"></i>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <ul class="listcomment">
                            <li class="comment_ask" id="28963446">
                                <div class="rowuser">
                                    <a href="javascript:void(0)">
                                        <div>L</div>
                                        <strong onclick="selCmt(28963446)">Lợi</strong>
                                    </a>
                                </div>
                                <div class="question">Chương trình nhận coupon 8/8 mua laptop có dc áp dụng kèm chương trình đổi điểm thi song song luôn ko bạn?</div>
                                <div class="actionuser" data-cl="0">
                                    <a href="javascript:void(0)" class="respondent" onclick="cmtaddreplyclick(28963446)">Trả lời</a>
                                    <a href="javascript:void(0)" class="time" onclick="cmtReport(28963446)">2 giờ trước </a>
                                </div>
                                <div class="listreply" id="r28963446">
                                    <div class="reply " id="28964208" data-parent="28963446">
                                        <div class="rowuser">
                                            <a href="javascript:void(0)">
                                                <div>H</div>
                                                <strong onclick="selCmt(28964208)">Lan Hương</strong>
                                                <b class="qtv">Quản trị viên</b>
                                            </a>
                                        </div>
                                        <div class="cont">
                                            Chào anh,<br> Khuyến mãi giảm giá ngày đẹp không áp dụng đồng thời khuyến mãi khác như đổi điểm ạ. Anh chọn 1 thôi ạ.<br>Thông tin đến anh ạ.
                                        </div>
                                        <div class="actionuser" data-cl="0">
                                            <a href="javascript:void(0)" class="respondent" onclick="cmtChildAddReplyClick(28964208,28963446)">Trả lời</a>
                                            <a href="javascript:satisfiedCmt(28964208);" class="favor satis cmthl">
                                                <i class="iconcom-like"></i>
                                                Hài lòng<span></span>
                                            </a>
                                            <a href="javascript:unsatisfiedCmt(28964208);" class="favor satis cmtkhl">
                                                <i class="iconcom-unlike"></i>
                                                Không hài lòng<span></span>
                                            </a>
                                            <a href="javascript:void(0)" class="time" onclick="cmtReport(28964208)">1 giờ trước </a>
                                            <div id="wrs28964208" class="wrapsatis" style="display: none;">
                                                <div class="wrsct">
                                                    <i class="iconcom-close" onclick="closeSatis();"></i>
                                                    <span>
                                                        Thegioididong.com rất tiếc đã làm bạn chưa hài lòng. Mời bạn góp ý để <b>QTV Lan Hương</b>
                                                        phục vụ tốt hơn:
                                                    </span>
                                                    <textarea placeholder="Nhập nội dung góp ý" type="text" class="ustCt"></textarea>
                                                    <span>Hãy để lại thông tin để được hỗ trợ khi cần thiết (không bắt buộc):</span>
                                                    <input placeholder="Họ tên" type="text" class="ustName" />
                                                    <input placeholder="Số điện thoại" type="text" class="ustPhone" />
                                                    <a href="javascript:sendUnSatisfied(28964208)">GỬI</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="inputreply hide"></div>
                            </li>
                            <li class="comment_ask" id="28871460">
                                <div class="rowuser">
                                    <a href="javascript:void(0)">
                                        <div>M</div>
                                        <strong onclick="selCmt(28871460)">Manh</strong>
                                    </a>
                                </div>
                                <div class="question">Chào shop. Mình muốn mua một laptop giá dưới 30 tr Mình cần để mua cho cháu học kỹ thuật, đồ họa nặng. Shop có thể tư vấn giúp mình được không? Hiện tại mình đang phân vân giữa macbook với envy của hp</div>
                                <div class="actionuser" data-cl="0">
                                    <a href="javascript:void(0)" class="respondent" onclick="cmtaddreplyclick(28871460)">Trả lời</a>
                                    <a href="javascript:void(0)" class="time" onclick="cmtReport(28871460)">4 ngày trước </a>
                                </div>
                                <div class="listreply" id="r28871460">
                                    <div class="reply " id="28871483" data-parent="28871460">
                                        <div class="rowuser">
                                            <a href="javascript:void(0)">
                                                <div>H</div>
                                                <strong onclick="selCmt(28871483)">Minh Hoàng</strong>
                                                <b class="qtv">Quản trị viên</b>
                                            </a>
                                        </div>
                                        <div class="cont">
                                            Chào anh ! <br> Theo em thấy sản phẩm <a target="_blank" target="_blank" href="https://www.thegioididong.com/laptop/hp-envy-13-ah0025tu-4me92pa" target="_blank">HP Envy 13 ah0025TU i5 8250U/8GB/128GB/Win10/(4ME92PA) </a>
                                            giá 20.990.000đ sẽ đủ đáp ứng nhu cầu của mình rồi anh nha, hỗ trợ học tập tốt, đồ họa mượt mà, ổn định nha anh.<br>Thông tin đến anh.
                                        </div>
                                        <div class="actionuser" data-cl="0">
                                            <a href="javascript:void(0)" class="respondent" onclick="cmtChildAddReplyClick(28871483,28871460)">Trả lời</a>
                                            <a href="javascript:satisfiedCmt(28871483);" class="favor satis cmthl">
                                                <i class="iconcom-like"></i>
                                                Hài lòng<span></span>
                                            </a>
                                            <a href="javascript:unsatisfiedCmt(28871483);" class="favor satis cmtkhl">
                                                <i class="iconcom-unlike"></i>
                                                Không hài lòng<span></span>
                                            </a>
                                            <a href="javascript:void(0)" class="time" onclick="cmtReport(28871483)">4 ngày trước </a>
                                            <div id="wrs28871483" class="wrapsatis" style="display: none;">
                                                <div class="wrsct">
                                                    <i class="iconcom-close" onclick="closeSatis();"></i>
                                                    <span>
                                                        Thegioididong.com rất tiếc đã làm bạn chưa hài lòng. Mời bạn góp ý để <b>QTV Minh Hoàng</b>
                                                        phục vụ tốt hơn:
                                                    </span>
                                                    <textarea placeholder="Nhập nội dung góp ý" type="text" class="ustCt"></textarea>
                                                    <span>Hãy để lại thông tin để được hỗ trợ khi cần thiết (không bắt buộc):</span>
                                                    <input placeholder="Họ tên" type="text" class="ustName" />
                                                    <input placeholder="Số điện thoại" type="text" class="ustPhone" />
                                                    <a href="javascript:sendUnSatisfied(28871483)">GỬI</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="reply " id="28873620" data-parent="28871460">
                                        <div class="rowuser">
                                            <a href="javascript:void(0)">
                                                <div>M</div>
                                                <strong onclick="selCmt(28873620)">Manh</strong>
                                            </a>
                                        </div>
                                        <div class="cont">@Minh Hoàng: chào qtv. thế muốn mua macbook thì nên mua máy nào phù hợp với nhu cầu trên?</div>
                                        <div class="actionuser" data-cl="0">
                                            <a href="javascript:void(0)" class="respondent" onclick="cmtChildAddReplyClick(28873620,28871460)">Trả lời</a>
                                            <a href="javascript:void(0)" class="time" onclick="cmtReport(28873620)">4 ngày trước </a>
                                        </div>
                                    </div>
                                    <div class="reply " id="28874938" data-parent="28871460">
                                        <div class="rowuser">
                                            <a href="javascript:void(0)">
                                                <div>P</div>
                                                <strong onclick="selCmt(28874938)">Xuân Phúc</strong>
                                                <b class="qtv">Quản trị viên</b>
                                            </a>
                                        </div>
                                        <div class="cont">
                                            Chào bạn, xin lỗi đã để bạn đợi lâu nhé<br> Trong tầm giá này, bạn vui lòng tham khảo <a target="_blank" target="_blank" href="apple-macbook-air-mqd42sa-a-i5-5350u">Laptop Apple Macbook Air MQD42SA/A i5 1.8GHz/8GB/256GB (2017)</a>
                                            , tuy nhiên máy chưa phù hợp cho làm công việc đồ họa bạn nhé. <br> Bạn nên chọn dòng Macbook Pro có card VGA rời làm đồ họa là tốt nhất nhé bạn.  <br>Thông tin đến bạn ạ!
                                        </div>
                                        <div class="actionuser" data-cl="0">
                                            <a href="javascript:void(0)" class="respondent" onclick="cmtChildAddReplyClick(28874938,28871460)">Trả lời</a>
                                            <a href="javascript:satisfiedCmt(28874938);" class="favor satis cmthl">
                                                <i class="iconcom-like"></i>
                                                Hài lòng<span></span>
                                            </a>
                                            <a href="javascript:unsatisfiedCmt(28874938);" class="favor satis cmtkhl">
                                                <i class="iconcom-unlike"></i>
                                                Không hài lòng<span></span>
                                            </a>
                                            <a href="javascript:void(0)" class="time" onclick="cmtReport(28874938)">4 ngày trước </a>
                                            <div id="wrs28874938" class="wrapsatis" style="display: none;">
                                                <div class="wrsct">
                                                    <i class="iconcom-close" onclick="closeSatis();"></i>
                                                    <span>
                                                        Thegioididong.com rất tiếc đã làm bạn chưa hài lòng. Mời bạn góp ý để <b>QTV Xuân Phúc</b>
                                                        phục vụ tốt hơn:
                                                    </span>
                                                    <textarea placeholder="Nhập nội dung góp ý" type="text" class="ustCt"></textarea>
                                                    <span>Hãy để lại thông tin để được hỗ trợ khi cần thiết (không bắt buộc):</span>
                                                    <input placeholder="Họ tên" type="text" class="ustName" />
                                                    <input placeholder="Số điện thoại" type="text" class="ustPhone" />
                                                    <a href="javascript:sendUnSatisfied(28874938)">GỬI</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="inputreply hide"></div>
                            </li>
                            <li class="comment_ask" id="28843303">
                                <div class="rowuser">
                                    <a href="javascript:void(0)">
                                        <div>T</div>
                                        <strong onclick="selCmt(28843303)">Thinhnd74</strong>
                                    </a>
                                </div>
                                <div class="question">Laptop Apple Macbook Air MQD42SA/A i5 1.8GHz/8GB/256GB (2017). Xin hỏi TGDĐ có hàng ngay không?,Laptop Apple Macbook Air MQD42SA/A i5 1.8GHz/8GB/256GB (2017). Xin hỏi TGDĐ có hàng ngay không?</div>
                                <div class="actionuser" data-cl="0">
                                    <a href="javascript:void(0)" class="respondent" onclick="cmtaddreplyclick(28843303)">Trả lời</a>
                                    <a href="javascript:void(0)" class="time" onclick="cmtReport(28843303)">5 ngày trước </a>
                                </div>
                                <div class="listreply" id="r28843303">
                                    <div class="reply " id="28843330" data-parent="28843303">
                                        <div class="rowuser">
                                            <a href="javascript:void(0)">
                                                <div>A</div>
                                                <strong onclick="selCmt(28843330)">Nguyễn Tuấn Anh</strong>
                                                <b class="qtv">Quản trị viên</b>
                                            </a>
                                        </div>
                                        <div class="cont">
                                            Chào anh,<br> Hiện sản phẩm trên đang hết hàng trên toàn hệ thống ạ, anh cung cấp giúp em số điện thoại để bên em hỗ trợ anh giao hàng từ 2 đến 7 ngày nha.<br>Sớm nhận được hồi âm từ anh.
                                        </div>
                                        <div class="actionuser" data-cl="0">
                                            <a href="javascript:void(0)" class="respondent" onclick="cmtChildAddReplyClick(28843330,28843303)">Trả lời</a>
                                            <a href="javascript:satisfiedCmt(28843330);" class="favor satis cmthl">
                                                <i class="iconcom-like"></i>
                                                Hài lòng<span></span>
                                            </a>
                                            <a href="javascript:unsatisfiedCmt(28843330);" class="favor satis cmtkhl">
                                                <i class="iconcom-unlike"></i>
                                                Không hài lòng<span></span>
                                            </a>
                                            <a href="javascript:void(0)" class="time" onclick="cmtReport(28843330)">5 ngày trước </a>
                                            <div id="wrs28843330" class="wrapsatis" style="display: none;">
                                                <div class="wrsct">
                                                    <i class="iconcom-close" onclick="closeSatis();"></i>
                                                    <span>
                                                        Thegioididong.com rất tiếc đã làm bạn chưa hài lòng. Mời bạn góp ý để <b>QTV Nguyễn Tuấn Anh</b>
                                                        phục vụ tốt hơn:
                                                    </span>
                                                    <textarea placeholder="Nhập nội dung góp ý" type="text" class="ustCt"></textarea>
                                                    <span>Hãy để lại thông tin để được hỗ trợ khi cần thiết (không bắt buộc):</span>
                                                    <input placeholder="Họ tên" type="text" class="ustName" />
                                                    <input placeholder="Số điện thoại" type="text" class="ustPhone" />
                                                    <a href="javascript:sendUnSatisfied(28843330)">GỬI</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="inputreply hide"></div>
                            </li>
                            <li class="comment_ask" id="28761206">
                                <div class="rowuser">
                                    <a href="javascript:void(0)">
                                        <div>K</div>
                                        <strong onclick="selCmt(28761206)">Khoa</strong>
                                    </a>
                                </div>
                                <div class="question">Chơi fifa online 4 đc k??</div>
                                <div class="actionuser" data-cl="0">
                                    <a href="javascript:void(0)" class="respondent" onclick="cmtaddreplyclick(28761206)">Trả lời</a>
                                    <a href="javascript:void(0)" class="time" onclick="cmtReport(28761206)">10 ngày trước </a>
                                </div>
                                <div class="listreply" id="r28761206">
                                    <div class="reply " id="28761832" data-parent="28761206">
                                        <div class="rowuser">
                                            <a href="javascript:void(0)">
                                                <div>T</div>
                                                <strong onclick="selCmt(28761832)">Minh Tân</strong>
                                                <b class="qtv">Quản trị viên</b>
                                            </a>
                                        </div>
                                        <div class="cont">
                                            Chào bạn <br> Máy không được trang bị card đồ họa rời nên chưa đáp ứng để chơi tốt Game trên bạn nhé .<br>Thông tin đến bạn .
                                        </div>
                                        <div class="actionuser" data-cl="0">
                                            <a href="javascript:void(0)" class="respondent" onclick="cmtChildAddReplyClick(28761832,28761206)">Trả lời</a>
                                            <a href="javascript:satisfiedCmt(28761832);" class="favor satis cmthl">
                                                <i class="iconcom-like"></i>
                                                Hài lòng<span></span>
                                            </a>
                                            <a href="javascript:unsatisfiedCmt(28761832);" class="favor satis cmtkhl">
                                                <i class="iconcom-unlike"></i>
                                                Không hài lòng<span></span>
                                            </a>
                                            <a href="javascript:void(0)" class="time" onclick="cmtReport(28761832)">10 ngày trước </a>
                                            <div id="wrs28761832" class="wrapsatis" style="display: none;">
                                                <div class="wrsct">
                                                    <i class="iconcom-close" onclick="closeSatis();"></i>
                                                    <span>
                                                        Thegioididong.com rất tiếc đã làm bạn chưa hài lòng. Mời bạn góp ý để <b>QTV Minh Tân</b>
                                                        phục vụ tốt hơn:
                                                    </span>
                                                    <textarea placeholder="Nhập nội dung góp ý" type="text" class="ustCt"></textarea>
                                                    <span>Hãy để lại thông tin để được hỗ trợ khi cần thiết (không bắt buộc):</span>
                                                    <input placeholder="Họ tên" type="text" class="ustName" />
                                                    <input placeholder="Số điện thoại" type="text" class="ustPhone" />
                                                    <a href="javascript:sendUnSatisfied(28761832)">GỬI</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="inputreply hide"></div>
                            </li>
                            <li class="comment_ask" id="28752453">
                                <div class="rowuser">
                                    <a href="javascript:void(0)">
                                        <div>N</div>
                                        <strong onclick="selCmt(28752453)">Ngọc</strong>
                                    </a>
                                </div>
                                <div class="question">Cho mình hỏi máy này có thể sử dụng các phần mềm công nghệ như photoshop hay ko vậy?</div>
                                <div class="actionuser" data-cl="0">
                                    <a href="javascript:void(0)" class="respondent" onclick="cmtaddreplyclick(28752453)">Trả lời</a>
                                    <a href="javascript:void(0)" class="time" onclick="cmtReport(28752453)">10 ngày trước </a>
                                </div>
                                <div class="listreply" id="r28752453">
                                    <div class="reply " id="28752751" data-parent="28752453">
                                        <div class="rowuser">
                                            <a href="javascript:void(0)">
                                                <div>N</div>
                                                <strong onclick="selCmt(28752751)">Duy Ngọc</strong>
                                                <b class="qtv">Quản trị viên</b>
                                            </a>
                                        </div>
                                        <div class="cont">
                                            Chào bạn,<br> Máy tính này thì vẫn có thể sử dụng các phần mềm như PhotoShop được bạn nhé.<br>Xin thông tin đến bạn!
                                        </div>
                                        <div class="actionuser" data-cl="0">
                                            <a href="javascript:void(0)" class="respondent" onclick="cmtChildAddReplyClick(28752751,28752453)">Trả lời</a>
                                            <a href="javascript:satisfiedCmt(28752751);" class="favor satis cmthl">
                                                <i class="iconcom-like"></i>
                                                Hài lòng<span></span>
                                            </a>
                                            <a href="javascript:unsatisfiedCmt(28752751);" class="favor satis cmtkhl">
                                                <i class="iconcom-unlike"></i>
                                                Không hài lòng<span></span>
                                            </a>
                                            <a href="javascript:void(0)" class="time" onclick="cmtReport(28752751)">10 ngày trước </a>
                                            <div id="wrs28752751" class="wrapsatis" style="display: none;">
                                                <div class="wrsct">
                                                    <i class="iconcom-close" onclick="closeSatis();"></i>
                                                    <span>
                                                        Thegioididong.com rất tiếc đã làm bạn chưa hài lòng. Mời bạn góp ý để <b>QTV Duy Ngọc</b>
                                                        phục vụ tốt hơn:
                                                    </span>
                                                    <textarea placeholder="Nhập nội dung góp ý" type="text" class="ustCt"></textarea>
                                                    <span>Hãy để lại thông tin để được hỗ trợ khi cần thiết (không bắt buộc):</span>
                                                    <input placeholder="Họ tên" type="text" class="ustName" />
                                                    <input placeholder="Số điện thoại" type="text" class="ustPhone" />
                                                    <a href="javascript:sendUnSatisfied(28752751)">GỬI</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="inputreply hide"></div>
                            </li>
                            <li class="comment_ask" id="28694047">
                                <div class="rowuser">
                                    <a href="javascript:void(0)">
                                        <div>N</div>
                                        <strong onclick="selCmt(28694047)">Nghia</strong>
                                    </a>
                                </div>
                                <div class="question">Máy này chơi game offline với 1 số game như thiên long bát bộ . Được không qtv</div>
                                <div class="actionuser" data-cl="0">
                                    <a href="javascript:void(0)" class="respondent" onclick="cmtaddreplyclick(28694047)">Trả lời</a>
                                    <a href="javascript:void(0)" class="time" onclick="cmtReport(28694047)">13 ngày trước </a>
                                </div>
                                <div class="listreply" id="r28694047">
                                    <div class="reply " id="28696841" data-parent="28694047">
                                        <div class="rowuser">
                                            <a href="javascript:void(0)">
                                                <div>T</div>
                                                <strong onclick="selCmt(28696841)">Hà Thanh</strong>
                                                <b class="qtv">Quản trị viên</b>
                                            </a>
                                        </div>
                                        <div class="cont">
                                            Chào bạn,<br> Dòng macbook thì sẽ kén game và có nhiều game Offline máy không hỗ trợ bạn nhé.<br>Thông tin đến bạn !
                                        </div>
                                        <div class="actionuser" data-cl="0">
                                            <a href="javascript:void(0)" class="respondent" onclick="cmtChildAddReplyClick(28696841,28694047)">Trả lời</a>
                                            <a href="javascript:satisfiedCmt(28696841);" class="favor satis cmthl">
                                                <i class="iconcom-like"></i>
                                                Hài lòng<span></span>
                                            </a>
                                            <a href="javascript:unsatisfiedCmt(28696841);" class="favor satis cmtkhl">
                                                <i class="iconcom-unlike"></i>
                                                Không hài lòng<span></span>
                                            </a>
                                            <a href="javascript:void(0)" class="time" onclick="cmtReport(28696841)">13 ngày trước </a>
                                            <div id="wrs28696841" class="wrapsatis" style="display: none;">
                                                <div class="wrsct">
                                                    <i class="iconcom-close" onclick="closeSatis();"></i>
                                                    <span>
                                                        Thegioididong.com rất tiếc đã làm bạn chưa hài lòng. Mời bạn góp ý để <b>QTV Hà Thanh</b>
                                                        phục vụ tốt hơn:
                                                    </span>
                                                    <textarea placeholder="Nhập nội dung góp ý" type="text" class="ustCt"></textarea>
                                                    <span>Hãy để lại thông tin để được hỗ trợ khi cần thiết (không bắt buộc):</span>
                                                    <input placeholder="Họ tên" type="text" class="ustName" />
                                                    <input placeholder="Số điện thoại" type="text" class="ustPhone" />
                                                    <a href="javascript:sendUnSatisfied(28696841)">GỬI</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="reply " id="28697138" data-parent="28694047">
                                        <div class="rowuser">
                                            <a href="javascript:void(0)">
                                                <div>N</div>
                                                <strong onclick="selCmt(28697138)">Nghĩa</strong>
                                            </a>
                                        </div>
                                        <div class="cont">@Hà Thanh: như game liên quân mobile .sử dụng phần mềm giả lập để chơi .chỉnh cấu hình cao , fps cao . Có giật lag không</div>
                                        <div class="actionuser" data-cl="0">
                                            <a href="javascript:void(0)" class="respondent" onclick="cmtChildAddReplyClick(28697138,28694047)">Trả lời</a>
                                            <a href="javascript:void(0)" class="time" onclick="cmtReport(28697138)">13 ngày trước </a>
                                        </div>
                                    </div>
                                    <div class="reply " id="28698540" data-parent="28694047">
                                        <div class="rowuser">
                                            <a href="javascript:void(0)">
                                                <div>T</div>
                                                <strong onclick="selCmt(28698540)">Hà Thanh</strong>
                                                <b class="qtv">Quản trị viên</b>
                                            </a>
                                        </div>
                                        <div class="cont">
                                            Nếu sử dụng giả lập chơi game liên quân thì ổn bạn nha.<br>Thông tin đến bạn !
                                        </div>
                                        <div class="actionuser" data-cl="0">
                                            <a href="javascript:void(0)" class="respondent" onclick="cmtChildAddReplyClick(28698540,28694047)">Trả lời</a>
                                            <a href="javascript:satisfiedCmt(28698540);" class="favor satis cmthl">
                                                <i class="iconcom-like"></i>
                                                Hài lòng<span></span>
                                            </a>
                                            <a href="javascript:unsatisfiedCmt(28698540);" class="favor satis cmtkhl">
                                                <i class="iconcom-unlike"></i>
                                                Không hài lòng<span></span>
                                            </a>
                                            <a href="javascript:void(0)" class="time" onclick="cmtReport(28698540)">13 ngày trước </a>
                                            <div id="wrs28698540" class="wrapsatis" style="display: none;">
                                                <div class="wrsct">
                                                    <i class="iconcom-close" onclick="closeSatis();"></i>
                                                    <span>
                                                        Thegioididong.com rất tiếc đã làm bạn chưa hài lòng. Mời bạn góp ý để <b>QTV Hà Thanh</b>
                                                        phục vụ tốt hơn:
                                                    </span>
                                                    <textarea placeholder="Nhập nội dung góp ý" type="text" class="ustCt"></textarea>
                                                    <span>Hãy để lại thông tin để được hỗ trợ khi cần thiết (không bắt buộc):</span>
                                                    <input placeholder="Họ tên" type="text" class="ustName" />
                                                    <input placeholder="Số điện thoại" type="text" class="ustPhone" />
                                                    <a href="javascript:sendUnSatisfied(28698540)">GỬI</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="inputreply hide"></div>
                            </li>
                            <li class="comment_ask" id="28674226">
                                <div class="rowuser">
                                    <a href="javascript:void(0)">
                                        <div>Q</div>
                                        <strong onclick="selCmt(28674226)">Phương Quỳnh</strong>
                                    </a>
                                </div>
                                <div class="question">Macbook air có cảm biến vân tay k ạ</div>
                                <div class="actionuser" data-cl="0">
                                    <a href="javascript:void(0)" class="respondent" onclick="cmtaddreplyclick(28674226)">Trả lời</a>
                                    <a href="javascript:void(0)" class="time" onclick="cmtReport(28674226)">14 ngày trước </a>
                                </div>
                                <div class="listreply" id="r28674226">
                                    <div class="reply " id="28675821" data-parent="28674226">
                                        <div class="rowuser">
                                            <a href="javascript:void(0)">
                                                <div>N</div>
                                                <strong onclick="selCmt(28675821)">Duy Ngọc</strong>
                                                <b class="qtv">Quản trị viên</b>
                                            </a>
                                        </div>
                                        <div class="cont">
                                            Chào bạn,<br> Máy tính này thì không có cảm biến vân tay bạn nhé.<br>Xin thông tin đến bạn!
                                        </div>
                                        <div class="actionuser" data-cl="0">
                                            <a href="javascript:void(0)" class="respondent" onclick="cmtChildAddReplyClick(28675821,28674226)">Trả lời</a>
                                            <a href="javascript:satisfiedCmt(28675821);" class="favor satis cmthl">
                                                <i class="iconcom-like"></i>
                                                Hài lòng<span></span>
                                            </a>
                                            <a href="javascript:unsatisfiedCmt(28675821);" class="favor satis cmtkhl">
                                                <i class="iconcom-unlike"></i>
                                                Không hài lòng<span></span>
                                            </a>
                                            <a href="javascript:void(0)" class="time" onclick="cmtReport(28675821)">14 ngày trước </a>
                                            <div id="wrs28675821" class="wrapsatis" style="display: none;">
                                                <div class="wrsct">
                                                    <i class="iconcom-close" onclick="closeSatis();"></i>
                                                    <span>
                                                        Thegioididong.com rất tiếc đã làm bạn chưa hài lòng. Mời bạn góp ý để <b>QTV Duy Ngọc</b>
                                                        phục vụ tốt hơn:
                                                    </span>
                                                    <textarea placeholder="Nhập nội dung góp ý" type="text" class="ustCt"></textarea>
                                                    <span>Hãy để lại thông tin để được hỗ trợ khi cần thiết (không bắt buộc):</span>
                                                    <input placeholder="Họ tên" type="text" class="ustName" />
                                                    <input placeholder="Số điện thoại" type="text" class="ustPhone" />
                                                    <a href="javascript:sendUnSatisfied(28675821)">GỬI</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="inputreply hide"></div>
                            </li>
                            <li class="comment_ask" id="28660877">
                                <div class="rowuser">
                                    <a href="javascript:void(0)">
                                        <div>H</div>
                                        <strong onclick="selCmt(28660877)">Hạnh</strong>
                                    </a>
                                </div>
                                <div class="question">Máy thế hệ 6 hay 7 ạh</div>
                                <div class="actionuser" data-cl="0">
                                    <a href="javascript:void(0)" class="respondent" onclick="cmtaddreplyclick(28660877)">Trả lời</a>
                                    <a href="javascript:void(0)" class="time" onclick="cmtReport(28660877)">14 ngày trước </a>
                                </div>
                                <div class="listreply" id="r28660877">
                                    <div class="reply " id="28661217" data-parent="28660877">
                                        <div class="rowuser">
                                            <a href="javascript:void(0)">
                                                <div>Đ</div>
                                                <strong onclick="selCmt(28661217)">Khoa Đăng</strong>
                                                <b class="qtv">Quản trị viên</b>
                                            </a>
                                        </div>
                                        <div class="cont">
                                            Chào bạn, <br> Sản phẩm  này là thế hệ thứ 5 bạn nhé. <br>Thông tin đến bạn.
                                        </div>
                                        <div class="actionuser" data-cl="0">
                                            <a href="javascript:void(0)" class="respondent" onclick="cmtChildAddReplyClick(28661217,28660877)">Trả lời</a>
                                            <a href="javascript:satisfiedCmt(28661217);" class="favor satis cmthl">
                                                <i class="iconcom-like"></i>
                                                Hài lòng<span></span>
                                            </a>
                                            <a href="javascript:unsatisfiedCmt(28661217);" class="favor satis cmtkhl">
                                                <i class="iconcom-unlike"></i>
                                                Không hài lòng<span></span>
                                            </a>
                                            <a href="javascript:void(0)" class="time" onclick="cmtReport(28661217)">14 ngày trước </a>
                                            <div id="wrs28661217" class="wrapsatis" style="display: none;">
                                                <div class="wrsct">
                                                    <i class="iconcom-close" onclick="closeSatis();"></i>
                                                    <span>
                                                        Thegioididong.com rất tiếc đã làm bạn chưa hài lòng. Mời bạn góp ý để <b>QTV Khoa Đăng</b>
                                                        phục vụ tốt hơn:
                                                    </span>
                                                    <textarea placeholder="Nhập nội dung góp ý" type="text" class="ustCt"></textarea>
                                                    <span>Hãy để lại thông tin để được hỗ trợ khi cần thiết (không bắt buộc):</span>
                                                    <input placeholder="Họ tên" type="text" class="ustName" />
                                                    <input placeholder="Số điện thoại" type="text" class="ustPhone" />
                                                    <a href="javascript:sendUnSatisfied(28661217)">GỬI</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="inputreply hide"></div>
                            </li>
                            <li class="comment_ask" id="28616939">
                                <div class="rowuser">
                                    <a href="javascript:void(0)">
                                        <div>a</div>
                                        <strong onclick="selCmt(28616939)">Áàá</strong>
                                    </a>
                                </div>
                                <div class="question">bạn có thể tư vấn laptop cho mình đc ko laptop asus chơi game đc , ổ cứng sdd, mỏng nhẹ giá ko cần thiết có pin trâu</div>
                                <div class="actionuser" data-cl="0">
                                    <a href="javascript:void(0)" class="respondent" onclick="cmtaddreplyclick(28616939)">Trả lời</a>
                                    <a href="javascript:void(0)" class="time" onclick="cmtReport(28616939)">17 ngày trước </a>
                                </div>
                                <div class="listreply" id="r28616939">
                                    <div class="reply " id="28619066" data-parent="28616939">
                                        <div class="rowuser">
                                            <a href="javascript:void(0)">
                                                <div>N</div>
                                                <strong onclick="selCmt(28619066)">Duy Ngọc</strong>
                                                <b class="qtv">Quản trị viên</b>
                                            </a>
                                        </div>
                                        <div class="cont">
                                            Chào bạn,<br> Bạn có thể tham khảo<a target="_blank" target="_blank" href="https://www.thegioididong.com/laptop/asus-ux430ua-i5-8250u-gv334t">Laptop Asus UX430UA i5 8250U/8GB/256GB/Win10/(GV334T)</a>
                                            , chơi được các game không đòi hỏi cấu hình cao, có SSD, mỏng nhẹ bạn nhé.<br>Chia sẻ cùng bạn !
                                        </div>
                                        <div class="actionuser" data-cl="0">
                                            <a href="javascript:void(0)" class="respondent" onclick="cmtChildAddReplyClick(28619066,28616939)">Trả lời</a>
                                            <a href="javascript:satisfiedCmt(28619066);" class="favor satis cmthl">
                                                <i class="iconcom-like"></i>
                                                Hài lòng<span></span>
                                            </a>
                                            <a href="javascript:unsatisfiedCmt(28619066);" class="favor satis cmtkhl">
                                                <i class="iconcom-unlike"></i>
                                                Không hài lòng<span></span>
                                            </a>
                                            <a href="javascript:void(0)" class="time" onclick="cmtReport(28619066)">17 ngày trước </a>
                                            <div id="wrs28619066" class="wrapsatis" style="display: none;">
                                                <div class="wrsct">
                                                    <i class="iconcom-close" onclick="closeSatis();"></i>
                                                    <span>
                                                        Thegioididong.com rất tiếc đã làm bạn chưa hài lòng. Mời bạn góp ý để <b>QTV Duy Ngọc</b>
                                                        phục vụ tốt hơn:
                                                    </span>
                                                    <textarea placeholder="Nhập nội dung góp ý" type="text" class="ustCt"></textarea>
                                                    <span>Hãy để lại thông tin để được hỗ trợ khi cần thiết (không bắt buộc):</span>
                                                    <input placeholder="Họ tên" type="text" class="ustName" />
                                                    <input placeholder="Số điện thoại" type="text" class="ustPhone" />
                                                    <a href="javascript:sendUnSatisfied(28619066)">GỬI</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="inputreply hide"></div>
                            </li>
                            <li class="comment_ask" id="28613886">
                                <div class="rowuser">
                                    <a href="javascript:void(0)">
                                        <div>H</div>
                                        <strong onclick="selCmt(28613886)">Khánh Hoàng</strong>
                                    </a>
                                </div>
                                <div class="question">cho hỏi macbook có thể chơi tựa game như rules, liên minh, đột kích đc ko ạ</div>
                                <div class="actionuser" data-cl="0">
                                    <a href="javascript:void(0)" class="respondent" onclick="cmtaddreplyclick(28613886)">Trả lời</a>
                                    <a href="javascript:void(0)" class="time" onclick="cmtReport(28613886)">17 ngày trước </a>
                                </div>
                                <div class="listreply" id="r28613886">
                                    <div class="reply " id="28613919" data-parent="28613886">
                                        <div class="rowuser">
                                            <a href="javascript:void(0)">
                                                <img class="lazy" class='lazy' data-original="<?= $assetsPrefix?>/jpg/1105114574a3.jpg?v=1105114537" />
                                                <i class="iconcom-adm"></i>
                                                <strong onclick="selCmt(28613919)">Xuân Đạt</strong>
                                                <b class="qtv">Quản trị viên</b>
                                            </a>
                                        </div>
                                        <div class="cont">
                                            Chào bạn, <br> Macbook cấu hình cao và bạn cài hệ điều hành Windows thì vẫn chơi được các game trên. <br> Tuy nhiên Macbook nhìn chung không phù hợp cho chơi game , để chơi game bạn nên chọn các máy Windows sẽ tốt hơn bạn nhé. <br>Thông tin đến bạn .
                                        </div>
                                        <div class="actionuser" data-cl="0">
                                            <a href="javascript:void(0)" class="respondent" onclick="cmtChildAddReplyClick(28613919,28613886)">Trả lời</a>
                                            <a href="javascript:satisfiedCmt(28613919);" class="favor satis cmthl">
                                                <i class="iconcom-like"></i>
                                                Hài lòng<span></span>
                                            </a>
                                            <a href="javascript:unsatisfiedCmt(28613919);" class="favor satis cmtkhl">
                                                <i class="iconcom-unlike"></i>
                                                Không hài lòng<span></span>
                                            </a>
                                            <a href="javascript:void(0)" class="time" onclick="cmtReport(28613919)">17 ngày trước </a>
                                            <div id="wrs28613919" class="wrapsatis" style="display: none;">
                                                <div class="wrsct">
                                                    <i class="iconcom-close" onclick="closeSatis();"></i>
                                                    <span>
                                                        Thegioididong.com rất tiếc đã làm bạn chưa hài lòng. Mời bạn góp ý để <b>QTV Xuân Đạt</b>
                                                        phục vụ tốt hơn:
                                                    </span>
                                                    <textarea placeholder="Nhập nội dung góp ý" type="text" class="ustCt"></textarea>
                                                    <span>Hãy để lại thông tin để được hỗ trợ khi cần thiết (không bắt buộc):</span>
                                                    <input placeholder="Họ tên" type="text" class="ustName" />
                                                    <input placeholder="Số điện thoại" type="text" class="ustPhone" />
                                                    <a href="javascript:sendUnSatisfied(28613919)">GỬI</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="reply " id="28614093" data-parent="28613886">
                                        <div class="rowuser">
                                            <a href="javascript:void(0)">
                                                <div>H</div>
                                                <strong onclick="selCmt(28614093)">Khánh Hoàng</strong>
                                            </a>
                                        </div>
                                        <div class="cont">@Xuân Đạt: mình là sinh viên mình có thể mua về đem đi học này nọ. Chơi vài game giải tí đc ko tư vấn hộ vấn dùm mk</div>
                                        <div class="actionuser" data-cl="0">
                                            <a href="javascript:void(0)" class="respondent" onclick="cmtChildAddReplyClick(28614093,28613886)">Trả lời</a>
                                            <a href="javascript:void(0)" class="time" onclick="cmtReport(28614093)">17 ngày trước </a>
                                        </div>
                                    </div>
                                    <div class="fullcomment" onclick="showFullCmt(28613886)">
                                        <i class="iconcom-comblue"></i>
                                        Xem tiếp 8 trả lời khác ▾
                                    </div>
                                    <div class="reply hide" id="28614127" data-parent="28613886">
                                        <div class="rowuser">
                                            <a href="javascript:void(0)">
                                                <img class="lazy" class='lazy' data-original="<?= $assetsPrefix?>/jpg/1105114574a3.jpg?v=1105114537" />
                                                <i class="iconcom-adm"></i>
                                                <strong onclick="selCmt(28614127)">Xuân Đạt</strong>
                                                <b class="qtv">Quản trị viên</b>
                                            </a>
                                        </div>
                                        <div class="cont">
                                            Bạn vui lòng cung cấp thêm thông tin cho mình là bạn cần mua máy tính trong tầm giá nào để mình giúp bạn nhé. <br>mong phản hồi từ bạn  !
                                        </div>
                                        <div class="actionuser" data-cl="0">
                                            <a href="javascript:void(0)" class="respondent" onclick="cmtChildAddReplyClick(28614127,28613886)">Trả lời</a>
                                            <a href="javascript:satisfiedCmt(28614127);" class="favor satis cmthl">
                                                <i class="iconcom-like"></i>
                                                Hài lòng<span></span>
                                            </a>
                                            <a href="javascript:unsatisfiedCmt(28614127);" class="favor satis cmtkhl">
                                                <i class="iconcom-unlike"></i>
                                                Không hài lòng<span></span>
                                            </a>
                                            <a href="javascript:void(0)" class="time" onclick="cmtReport(28614127)">17 ngày trước </a>
                                            <div id="wrs28614127" class="wrapsatis" style="display: none;">
                                                <div class="wrsct">
                                                    <i class="iconcom-close" onclick="closeSatis();"></i>
                                                    <span>
                                                        Thegioididong.com rất tiếc đã làm bạn chưa hài lòng. Mời bạn góp ý để <b>QTV Xuân Đạt</b>
                                                        phục vụ tốt hơn:
                                                    </span>
                                                    <textarea placeholder="Nhập nội dung góp ý" type="text" class="ustCt"></textarea>
                                                    <span>Hãy để lại thông tin để được hỗ trợ khi cần thiết (không bắt buộc):</span>
                                                    <input placeholder="Họ tên" type="text" class="ustName" />
                                                    <input placeholder="Số điện thoại" type="text" class="ustPhone" />
                                                    <a href="javascript:sendUnSatisfied(28614127)">GỬI</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="reply hide" id="28614210" data-parent="28613886">
                                        <div class="rowuser">
                                            <a href="javascript:void(0)">
                                                <div>H</div>
                                                <strong onclick="selCmt(28614210)">Khánh Hoàng</strong>
                                            </a>
                                        </div>
                                        <div class="cont">@Xuân Đạt: mình cần 1 laptop mỏng nhẹ có thể chơi game giải trí rules lâu lâu mới chơi pin trâu tí</div>
                                        <div class="actionuser" data-cl="0">
                                            <a href="javascript:void(0)" class="respondent" onclick="cmtChildAddReplyClick(28614210,28613886)">Trả lời</a>
                                            <a href="javascript:void(0)" class="time" onclick="cmtReport(28614210)">17 ngày trước </a>
                                        </div>
                                    </div>
                                    <div class="reply hide" id="28614298" data-parent="28613886">
                                        <div class="rowuser">
                                            <a href="javascript:void(0)">
                                                <div>M</div>
                                                <strong onclick="selCmt(28614298)">Quốc Minh</strong>
                                                <b class="qtv">Quản trị viên</b>
                                            </a>
                                        </div>
                                        <div class="cont">
                                            Dạ hiện tại bạn có đang nghiêng về hãng nào, cũng như tầm giá máy khoảng bao nhiêu chưa ạ; bạn kiểm tra lại và phản hồi giúp mình nhé. <br>Mong bạn thông cảm vì sự bất tiện này ạ!
                                        </div>
                                        <div class="actionuser" data-cl="0">
                                            <a href="javascript:void(0)" class="respondent" onclick="cmtChildAddReplyClick(28614298,28613886)">Trả lời</a>
                                            <a href="javascript:satisfiedCmt(28614298);" class="favor satis cmthl">
                                                <i class="iconcom-like"></i>
                                                Hài lòng<span></span>
                                            </a>
                                            <a href="javascript:unsatisfiedCmt(28614298);" class="favor satis cmtkhl">
                                                <i class="iconcom-unlike"></i>
                                                Không hài lòng<span></span>
                                            </a>
                                            <a href="javascript:void(0)" class="time" onclick="cmtReport(28614298)">17 ngày trước </a>
                                            <div id="wrs28614298" class="wrapsatis" style="display: none;">
                                                <div class="wrsct">
                                                    <i class="iconcom-close" onclick="closeSatis();"></i>
                                                    <span>
                                                        Thegioididong.com rất tiếc đã làm bạn chưa hài lòng. Mời bạn góp ý để <b>QTV Quốc Minh</b>
                                                        phục vụ tốt hơn:
                                                    </span>
                                                    <textarea placeholder="Nhập nội dung góp ý" type="text" class="ustCt"></textarea>
                                                    <span>Hãy để lại thông tin để được hỗ trợ khi cần thiết (không bắt buộc):</span>
                                                    <input placeholder="Họ tên" type="text" class="ustName" />
                                                    <input placeholder="Số điện thoại" type="text" class="ustPhone" />
                                                    <a href="javascript:sendUnSatisfied(28614298)">GỬI</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="reply hide" id="28614805" data-parent="28613886">
                                        <div class="rowuser">
                                            <a href="javascript:void(0)">
                                                <div>H</div>
                                                <strong onclick="selCmt(28614805)">Khánh Hoàng</strong>
                                            </a>
                                        </div>
                                        <div class="cont">@Xuân Đạt: trừ mac ra mình nghiêng về hãng asus</div>
                                        <div class="actionuser" data-cl="0">
                                            <a href="javascript:void(0)" class="respondent" onclick="cmtChildAddReplyClick(28614805,28613886)">Trả lời</a>
                                            <a href="javascript:void(0)" class="time" onclick="cmtReport(28614805)">17 ngày trước </a>
                                        </div>
                                    </div>
                                    <div class="reply hide" id="28614923" data-parent="28613886">
                                        <div class="rowuser">
                                            <a href="javascript:void(0)">
                                                <div>T</div>
                                                <strong onclick="selCmt(28614923)">Hà Thanh</strong>
                                                <b class="qtv">Quản trị viên</b>
                                            </a>
                                        </div>
                                        <div class="cont">
                                            Bạn cần trong tầm giá bao nhiêu vậy bạn, mong bạn sớm phản hồi thông tin đến bên mình có thể hỗ trợ bạn nha. <br>Thông tin đến bạn !
                                        </div>
                                        <div class="actionuser" data-cl="0">
                                            <a href="javascript:void(0)" class="respondent" onclick="cmtChildAddReplyClick(28614923,28613886)">Trả lời</a>
                                            <a href="javascript:satisfiedCmt(28614923);" class="favor satis cmthl">
                                                <i class="iconcom-like"></i>
                                                Hài lòng<span></span>
                                            </a>
                                            <a href="javascript:unsatisfiedCmt(28614923);" class="favor satis cmtkhl">
                                                <i class="iconcom-unlike"></i>
                                                Không hài lòng<span></span>
                                            </a>
                                            <a href="javascript:void(0)" class="time" onclick="cmtReport(28614923)">17 ngày trước </a>
                                            <div id="wrs28614923" class="wrapsatis" style="display: none;">
                                                <div class="wrsct">
                                                    <i class="iconcom-close" onclick="closeSatis();"></i>
                                                    <span>
                                                        Thegioididong.com rất tiếc đã làm bạn chưa hài lòng. Mời bạn góp ý để <b>QTV Hà Thanh</b>
                                                        phục vụ tốt hơn:
                                                    </span>
                                                    <textarea placeholder="Nhập nội dung góp ý" type="text" class="ustCt"></textarea>
                                                    <span>Hãy để lại thông tin để được hỗ trợ khi cần thiết (không bắt buộc):</span>
                                                    <input placeholder="Họ tên" type="text" class="ustName" />
                                                    <input placeholder="Số điện thoại" type="text" class="ustPhone" />
                                                    <a href="javascript:sendUnSatisfied(28614923)">GỬI</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="reply hide" id="28616141" data-parent="28613886">
                                        <div class="rowuser">
                                            <a href="javascript:void(0)">
                                                <div>H</div>
                                                <strong onclick="selCmt(28616141)">Khánh Hoàng</strong>
                                            </a>
                                        </div>
                                        <div class="cont">@Hà Thanh: dưới 30tr</div>
                                        <div class="actionuser" data-cl="0">
                                            <a href="javascript:void(0)" class="respondent" onclick="cmtChildAddReplyClick(28616141,28613886)">Trả lời</a>
                                            <a href="javascript:void(0)" class="time" onclick="cmtReport(28616141)">17 ngày trước </a>
                                        </div>
                                    </div>
                                    <div class="reply hide" id="28616178" data-parent="28613886">
                                        <div class="rowuser">
                                            <a href="javascript:void(0)">
                                                <div>P</div>
                                                <strong onclick="selCmt(28616178)">Xuân Phúc</strong>
                                                <b class="qtv">Quản trị viên</b>
                                            </a>
                                        </div>
                                        <div class="cont">
                                            Chào bạn, <br> Bạn vui lòng tham khảo <a target="_blank" target="_blank" href="https://www.thegioididong.com/laptop/asus-s510uq-i5-8250u-bq475t">Laptop Asus S510UQ i5 8250U/4GB/1TB/2GB 940MX/Win10/(BQ475T)</a>
                                            có cấu hình chơi game khá tốt bạn nhé <br>Thông tin đến bạn ạ!
                                        </div>
                                        <div class="actionuser" data-cl="0">
                                            <a href="javascript:void(0)" class="respondent" onclick="cmtChildAddReplyClick(28616178,28613886)">Trả lời</a>
                                            <a href="javascript:satisfiedCmt(28616178);" class="favor satis cmthl">
                                                <i class="iconcom-like"></i>
                                                Hài lòng<span></span>
                                            </a>
                                            <a href="javascript:unsatisfiedCmt(28616178);" class="favor satis cmtkhl">
                                                <i class="iconcom-unlike"></i>
                                                Không hài lòng<span></span>
                                            </a>
                                            <a href="javascript:void(0)" class="time" onclick="cmtReport(28616178)">17 ngày trước </a>
                                            <div id="wrs28616178" class="wrapsatis" style="display: none;">
                                                <div class="wrsct">
                                                    <i class="iconcom-close" onclick="closeSatis();"></i>
                                                    <span>
                                                        Thegioididong.com rất tiếc đã làm bạn chưa hài lòng. Mời bạn góp ý để <b>QTV Xuân Phúc</b>
                                                        phục vụ tốt hơn:
                                                    </span>
                                                    <textarea placeholder="Nhập nội dung góp ý" type="text" class="ustCt"></textarea>
                                                    <span>Hãy để lại thông tin để được hỗ trợ khi cần thiết (không bắt buộc):</span>
                                                    <input placeholder="Họ tên" type="text" class="ustName" />
                                                    <input placeholder="Số điện thoại" type="text" class="ustPhone" />
                                                    <a href="javascript:sendUnSatisfied(28616178)">GỬI</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="reply hide" id="28616269" data-parent="28613886">
                                        <div class="rowuser">
                                            <a href="javascript:void(0)">
                                                <div>H</div>
                                                <strong onclick="selCmt(28616269)">Khánh Hoàng</strong>
                                            </a>
                                        </div>
                                        <div class="cont">@Xuân Phúc: cảm ơn bạn rất nhiều đã tư vấn</div>
                                        <div class="actionuser" data-cl="0">
                                            <a href="javascript:void(0)" class="respondent" onclick="cmtChildAddReplyClick(28616269,28613886)">Trả lời</a>
                                            <a href="javascript:void(0)" class="time" onclick="cmtReport(28616269)">17 ngày trước </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="inputreply hide"></div>
                            </li>
                            <div class="pagcomment">
                                <span class="active">1</span>
                                <a onclick="listcomment(2,1,2);return false;" title="trang 2">2</a>
                                <a onclick="listcomment(2,1,3);return false;" title="trang 3">3</a>
                                <a onclick="listcomment(2,1,4);return false;" title="trang 4">4</a>
                                <span>...</span>
                                <a onclick="listcomment(2,1,13);return false;" title="trang 13">13</a>
                                <a onclick="listcomment(2,1,2);return false;" title="trang 2">»</a>
                            </div>
                        </ul>
                        <textarea id="txtEditorExt" name="" cols="" rows="" class="txtEditor" placeholder="Mời Bạn để lại bình luận..."></textarea>
                        <div class="ajaxcomment hide">
                            <div id="loadcomment"></div>
                        </div>
                    </div>
                </div>
            </aside>
            <aside class="right_content">
                <div class="tableparameter">
                    <h2>Thông số kỹ thuật</h2>
                    <ul class="parameter">
                        <li class="g92_94_93">
                            <span>CPU:</span>
                            <div>
                                <a href='https://www.thegioididong.com/tin-tuc/tim-hieu-vi-xu-ly-may-tinh-cpu-intel-596066#broadwell' target='_blank'>Intel Core i5 Broadwell</a>
                                , 1.80 GHz
                            </div>
                        </li>
                        <li class="g146_149_155">
                            <span>RAM:</span>
                            <div>
                                8 GB, <a href='https://www.thegioididong.com/hoi-dap/ram-ddr3l-on-board-la-nhu-the-nao-951049' target='_blank'>DDR3L(On board)</a>
                                , 1600 MHz
                            </div>
                        </li>
                        <li class="g184">
                            <span>Ổ cứng:</span>
                            <div>
                                <a href='https://www.thegioididong.com/hoi-dap/o-cung-ssd-la-gi-923073' target='_blank'>SSD: 256 GB</a>
                            </div>
                        </li>
                        <li class="g187_189">
                            <span>Màn hình:</span>
                            <div>13.3 inch, WXGA+(1440 x 900)</div>
                        </li>
                        <li class="g193_191">
                            <span>Card màn hình:</span>
                            <div>
                                <a href='https://www.thegioididong.com/hoi-dap/card-do-hoa-tich-hop-la-gi-950047' target='_blank'>Card đồ họa tích hợp</a>
                                , <a href='https://www.thegioididong.com/hoi-dap/intel-hd-graphics-754665#iris' target='_blank'>Intel HD Graphics 6000</a>
                            </div>
                        </li>
                        <li class="g200">
                            <span>Cổng kết nối:</span>
                            <div>
                                <a href='https://www.thegioididong.com/hoi-dap/magsafe-2-tren-macbook-la-gi-959220?clearcache=1' target='_blank'>MagSafe 2</a>
                                , <a href='https://www.thegioididong.com/hoi-dap/usb-30-la-gi-926737' target='_blank'>2 x USB 3.0</a>
                                , <a href='https://www.thegioididong.com/tin-tuc/intel-cong-bo-chuan-thunderbolt-moi-nhanh-hon-gap--516567' target='_blank'>Thunderbolt 2</a>
                            </div>
                        </li>
                        <li class="g11085">
                            <span>Đặc biệt:</span>
                            <div>Có đèn bàn phím</div>
                        </li>
                        <li class="g8599">
                            <span>Hệ điều hành:</span>
                            <div>
                                <a href='https://www.thegioididong.com/hoi-dap/mac-os-la-gi-838020' target='_blank'>Mac OS</a>
                            </div>
                        </li>
                        <li class="g7903_11082">
                            <span>Thiết kế:</span>
                            <div>Vỏ kim loại nguyên khối, PIN liền</div>
                        </li>
                        <li class="g254_255">
                            <span>Kích thước:</span>
                            <div>Dày 17 mm, 1.35 Kg</div>
                        </li>
                    </ul>
                    <button type="button" class="viewparameterfull" onclick="getFullSpec(106880)">Xem cấu hình chi tiết</button>
                    <div class="closebtn none">
                        <i class="icondetail-closepara"></i>
                    </div>
                    <div class="fullparameter">
                        <div class="scroll">
                            <h3>Thông số kỹ thuật chi tiết Apple Macbook Air MQD42SA/A i5 1.8GHz/8GB/256GB (2017)</h3>
                            <img id="imgKit" width="500" height="430" alt="Thông số kỹ thuật 106880">
                            <ul class="parameterfull"></ul>
                        </div>
                    </div>
                </div>
                <div class="clr"></div>
                <div class="newslist">
                    <h4>Tin tức về laptop</h4>
                    <ul>
                        <li>
                            <a href="https://www.thegioididong.com/tin-tuc/macbook-air-chinh-thuc-giam-den-4-trieu-van-co-khu-1092551">
                                <img data-original="https://cdn.tgdd.vn/Files/2018/06/02/1092551/3_801x450-300x200.jpg" class="lazy">
                                <h3>Macbook Air ch &#237;nh thức giảm đến 4 triệu, vẫn c &#243;khuyến m &#227;i hấp dẫn</h3>
                            </a>
                        </li>
                        <li>
                            <a href="https://www.thegioididong.com/tin-tuc/zenbook-ux370u-sieu-mong-thiet-ke-2-trong-1-len-ke-1107444">
                                <img data-original="https://cdn.tgdd.vn/Files/2018/08/07/1107444/asus-zenbook-flip-s-01_800x450-300x200.jpg" class="lazy">
                                <h3>Zenbook UX370U si &#234;u mỏng, thiết kế 2 trong 1, cấu h &#236;nh mạnh mẽ l &#234;n kệ</h3>
                            </a>
                        </li>
                        <li>
                            <a href="https://www.thegioididong.com/tin-tuc/danh-gia-asus-vivobook-15-x540ub-laptop-moi-nhan-n-1105465">
                                <img data-original="https://cdn.tgdd.vn/Files/2018/07/31/1105465/anhbiachamdiem_800x450-300x200.jpg" class="lazy">
                                <h3>Đ&#225;nh gi &#225;ASUS X540UB i3 6006U: Laptop c &#243;card đồ họa rời, gi &#225;tốt</h3>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="newslist">
                    <h4>Hướng dẫn về Apple Macbook Air MQD42SA/A i5 1.8GHz/8GB/256GB (2017)</h4>
                    <ul>
                        <li>
                            <a href="https://www.thegioididong.com/hoi-dap/top-5-tinh-nang-duoc-yeu-thich-nhat-cua-mac-os-moj-1099407" target="_blank">
                                <img data-original="https://cdn.tgdd.vn/hoi-dap/1099407/Thumbnail/top-5-tinh-nang-duoc-yeu-thich-tren-mac-mojave-1.jpg" class="lazy">
                                <h3>Top 5 t &#237;nh năng được y &#234;u th &#237;ch nhất của Mac OS Mojave tr &#234;n Macbook</h3>
                            </a>
                        </li>
                        <li>
                            <a href="https://www.thegioididong.com/hoi-dap/cach-doi-ten-cho-may-macbook-1098868" target="_blank">
                                <img data-original="https://cdn.tgdd.vn/hoi-dap/1098868/Thumbnail/doi-ten-macbook-nhanh-chong-1.jpg" class="lazy">
                                <h3>C &#225;ch đổi t &#234;n cho m &#225;y Macbook</h3>
                            </a>
                        </li>
                        <li>
                            <a href="https://www.thegioididong.com/hoi-dap/cach-xoa-mang-wifi-tren-macbook-1095540" target="_blank">
                                <img data-original="https://cdn.tgdd.vn/hoi-dap/1095540/Thumbnail/cach-xoa-mang-wifi-macbook-1.jpg" class="lazy">
                                <h3>C &#225;ch xo &#225;mạng Wifi tr &#234;n Macbook</h3>
                                <span>
                                    <i class="icontgdd-com"></i>
                                    2 Bình luận
                                </span>
                            </a>
                        </li>
                    </ul>
                    <a href="https://www.thegioididong.com/hoi-dap/san-pham/apple-macbook-air-mqd42sa-a-i5-5350u?f=huong-dan" class="viewall" target="_blank">Xem thêm hướng dẫn</a>
                </div>
                <div class="accessories">
                    <div>
                        <h3>Phụ kiện Apple Macbook Air MQD42SA/A i5 1.8GHz (2017)</h3>
                    </div>
                    <ul>
                        <li>
                            <a href="https://www.thegioididong.com/tai-nghe/tai-nghe-bluetooth-awei-a920bs">
                                <img data-original="https://cdn.tgdd.vn/Products/Images/54/146881/tai-nghe-bluetooth-awei-a920bs-ava-600x600.jpg" class="lazy" />
                                <h3>Tai nghe Bluetooth Awei A920BS</h3>
                                <strong class="gs">315.000₫</strong>
                                <strong class="gg">450.000₫</strong>
                                <label class="per">Giảm 30%</label>
                            </a>
                            <a href="https://www.thegioididong.com/them-vao-gio-hang?ProductId=146881" class="buyacc">Mua</a>
                        </li>
                        <li>
                            <a href="https://www.thegioididong.com/usb/usb-sandisk-sdcz43-16gb-30">
                                <img data-original="https://cdn.tgdd.vn/Products/Images/75/71754/usb-sandisk-sdcz43-16gb-30-ava-1-600x600.jpg" class="lazy" />
                                <h3>USB 3.0 16 GB Sandisk SDCZ43</h3>
                                <strong class="gs">200.000₫</strong>
                                <strong class="gg">250.000₫</strong>
                                <label class="per">Giảm 20%</label>
                            </a>
                            <a href="https://www.thegioididong.com/them-vao-gio-hang?ProductId=71754" class="buyacc">Mua</a>
                        </li>
                        <li>
                            <a href="https://www.thegioididong.com/phan-mem/eset-nod32-antivirus-cho-windows-1-pc">
                                <img data-original="https://cdn.tgdd.vn/Products/Images/85/109490/eset-nod32-antivirus-cho-windows-1-pc-1-2-600x600.jpg" class="lazy" />
                                <h3>ESET NOD32 Antivirus cho Windows - 1 PC</h3>
                                <strong>150.000₫</strong>
                            </a>
                            <a href="https://www.thegioididong.com/them-vao-gio-hang?ProductId=109490" class="buyacc">Mua</a>
                        </li>
                        <li>
                            <a href="https://www.thegioididong.com/chuot-may-tinh/chuot-co-day-apple-mb112-trang">
                                <img data-original="https://cdn.tgdd.vn/Products/Images/86/88049/chuot-co-day-apple-mb112-trang-1-8-600x600.jpg" class="lazy" />
                                <h3>Chuột c &#243;d &#226;y Apple MB112 Trắng</h3>
                                <strong>1.390.000₫</strong>
                            </a>
                            <a href="https://www.thegioididong.com/them-vao-gio-hang?ProductId=88049" class="buyacc">Mua</a>
                        </li>
                        <li>
                            <a href="https://www.thegioididong.com/loa-laptop/loa-bluetooth-awei-y200">
                                <img data-original="https://cdn.tgdd.vn/Products/Images/382/82652/loa-bluetooth-awei-y200-avatar-1-600x600.jpg" class="lazy" />
                                <h3>Loa Bluetooth Awei Y200</h3>
                                <strong class="gs">388.000₫</strong>
                                <strong class="gg">600.000₫</strong>
                            </a>
                            <a href="https://www.thegioididong.com/them-vao-gio-hang?ProductId=82652" class="buyacc">Mua</a>
                        </li>
                    </ul>
                    <a class="viewall" href="https://www.thegioididong.com/phu-kien/laptop/apple-macbook-air-mqd42sa-a-i5-5350u">Xem tất cả phụ kiện Apple Macbook Air MQD42SA/A i5 1.8GHz (2017)</a>
                </div>
            </aside>
        </div>
        <div class="clr"></div>
    </section>





    <script type="application/ld+json">
        {
            "@context": "http://schema.org",
            "@type": "BreadcrumbList",
            "itemListElement": [{
                    "@type": "ListItem",
                    "position": 1,
                    "item": {
                        "@id": "https://www.thegioididong.com",
                        "name": "Thế Giới Di Động"
                    }
                },
                {
                    "@type": "ListItem",
                    "position": 2,
                    "item": {
                        "@id": "https://www.thegioididong.com/laptop",
                        "name": "Laptop"
                    }
                },
                {
                    "@type": "ListItem",
                    "position": 3,
                    "item": {
                        "@id": "https://www.thegioididong.com/laptop-apple-macbook",
                        "name": "Macbook - iMac"
                    }
                }
            ]
        }
    </script>
    <script>
        var productPrice = 28990000;
    </script>