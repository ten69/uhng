<?php
use aabc\helpers\ArrayHelper;
use common\components\Tuyen;
$noibat_array = Tuyen::_dulieu('module', '10');
?>

<?php foreach ($noibat_array as $k => $v) { ?>
    
    <?php 
        $noibat = Tuyen::_dulieu('danhmuc',$k);
    ?>

    <div class="navigat ">
        <h2><?= $v['label']?></h2>
        <div class="viewallcat">
        <?php if(!empty($v['child'])) foreach ($v['child'] as $k_child => $child) {?>            
            <a ><?= $child['label']?></a>                 
        <?php } ?>
        </div>
    </div> 
    <div id="owl-promo" class="owl-carousel homepromo">

        <?php 
            foreach ($noibat['dm_listsp'] as $k_nb => $v_nb) {
                $sp = Tuyen::_dulieu('sanpham',$v_nb);  
                $image = explode('-', $sp['sp_images']); 
                $s_html = '';
                if(!empty($image['0'])) $s_html = Tuyen::_dulieu('image',$image['0'],'180x180');
                if(!empty($sp)){
                $spnn = Tuyen::_dulieu('spnn',$sp['sp_id']);
        ?>
                    <div class="item">            
                        <a>
                            <img width="180" height="180" src="<?= $s_html?>">
                            <h3><?= $sp['sp_tensp'] ?></h3>
                            <div class="price">
                                <strong><?= number_format($sp['sp_gia']) ?> ₫</strong>
                            </div>
                            <div class="promo noimage">
                                <p>
                                    <?= $spnn['spnn_gioithieu'] ?>                                    
                                </p>
                            </div>
                            <!-- <label class="installment">Trả góp 1%</label> -->
                        </a>
                    </div>
            <?php } ?>
        <?php } ?>

    </div>
<?php } ?>

<div class="navigat ">
    <h2>17 khuyến mãi hot nhất</h2>
</div>
<div id="owl-promo" class="owl-carousel homepromo">
    <div class="item">
        <!--#region Ngành hàng chính -->
        <a href="https://www.thegioididong.com/dtdd/samsung-galaxy-j6">
            <img width="180" height="180" src="<?= $assetsPrefix?>/jpg/samsung-galaxy-j6-2018-1-600x600-400x400.jpg">
            <h3>Samsung Galaxy J6</h3>
            <div class="price">
                <strong>5.290.000₫</strong>
            </div>
            <div class="promo noimage">
                <p>
                    Giảm ngay 500.000đ (từ 08 - 10/06) và <b>2 K.mãi</b>
                    khác
                </p>
            </div>
            <label class="installment">Trả góp 1%</label>
        </a>
        <!--#endregion -->
    </div>
    <div class="item">
        <!--#region Ngành hàng chính -->
        <a href="https://www.thegioididong.com/dtdd/iphone-8-plus">
            <img width="180" height="180" src="<?= $assetsPrefix?>/jpg/iphone-8-plus-hh-400x400.jpg">
            <h3>iPhone 8 Plus 64GB</h3>
            <div class="price">
                <strong>23.990.000₫</strong>
            </div>
            <div class="promo noimage">
                <p>
                    Giảm ngay 1 triệu và <b>2 K.mãi</b>
                    khác
                </p>
            </div>
            <label class="installment">Trả góp 0%</label>
        </a>
        <!--#endregion -->
    </div>
    <div class="item">
        <!--#region Ngành hàng chính -->
        <a href="https://www.thegioididong.com/dtdd/oppo-f5-youth">
            <img width="180" height="180" src="<?= $assetsPrefix?>/jpg/oppo-f5-youth-vang-hong-6001-400x400.jpg">
            <h3>OPPO F5 Youth</h3>
            <div class="price">
                <strong>5.990.000₫</strong>
            </div>
            <div class="promo noimage">
                <p>Giảm ngay 500.000đ (từ 09 - 10/06)</p>
            </div>
            <label class="installment">Trả góp 0%</label>
        </a>
        <!--#endregion -->
    </div>
    <div class="item">
        <!--#region Ngành hàng chính -->
        <a href="https://www.thegioididong.com/dtdd/vivo-v9-xanh-sapphire">
            <img width="180" height="180" src="<?= $assetsPrefix?>/jpg/vivo-v9-xanh-sapphire-1-400x400.jpg">
            <h3>Vivo V9 Xanh Sapphire</h3>
            <div class="price">
                <strong>7.990.000₫</strong>
            </div>
            <div class="promo">
                <img width="30" height="30" data-original="https://cdn2.tgdd.vn/Products/Images/2102/142312/phieu-mua-hang-200000d-1-100x100.jpg" class="lazy">
                <p>
                    Tặng Phiếu mua h &#224;ng 200.000đ khi mua online và <b>1 K.mãi</b>
                    khác
                </p>
            </div>
            <label class="installment">Trả góp 0%</label>
        </a>
        <!--#endregion -->
    </div>
    <div class="item">
        <!--#region Ngành hàng chính -->
        <a href="https://www.thegioididong.com/dtdd/samsung-galaxy-s8-plus">
            <img width="180" height="180" src="<?= $assetsPrefix?>/jpg/samsung-galaxy-s8-plus-hh-600x600-400x400.jpg">
            <h3>Samsung Galaxy S8 Plus</h3>
            <div class="price">
                <strong>17.990.000₫</strong>
            </div>
            <div class="promo noimage">
                <p>Giảm ngay 3 triệu (từ 08 - 10/06)</p>
            </div>
            <label class="installment">Trả góp 0%</label>
        </a>
        <!--#endregion -->
    </div>
    <div class="item">
        <!--#region Ngành hàng chính -->
        <a href="https://www.thegioididong.com/dtdd/huawei-nova-3e-hong">
            <img width="180" height="180" data-original="https://cdn4.tgdd.vn/Products/Images/42/159569/huawei-nova-3e-hong-10-400x400.jpg" class="lazy">
            <h3>Huawei Nova 3e Hồng</h3>
            <div class="price">
                <strong>6.990.000₫</strong>
            </div>
            <div class="promo noimage">
                <p>
                    Thẻ phần mềm diệt Virus ESET và <b>3 K.mãi</b>
                    khác
                </p>
            </div>
            <label class="installment">Trả góp 0%</label>
        </a>
        <!--#endregion -->
    </div>
    <div class="item">
        <!--#region Ngành hàng chính -->
        <a href="https://www.thegioididong.com/laptop/acer-swift-sf314-54-51ql-nxgxzsv001" class=laptop>
            <img width="180" height="180" data-original="https://cdn3.tgdd.vn/Products/Images/44/161558/acer-swift-sf314-54-51ql-nxgxzsv001-dai-dien-600-450x300-450x300-450x300-450x300-400x400.jpg" class="lazy">
            <h3>Acer Swift SF314 54 51QL i5 8250U (NX.GXZSV.001)</h3>
            <div class="price">
                <strong>16.990.000₫</strong>
            </div>
            <div class="promo">
                <img width="30" height="30" data-original="https://cdn3.tgdd.vn/Products/Images/2102/128988/phieu-mua-hang-500000d-1-100x100.jpg" class="lazy">
                <p>
                    Tặng Phiếu mua h &#224;ng 500.000đ khi mua online  và <b>5 K.mãi</b>
                    khác
                </p>
            </div>
            <label class="installment">Trả góp 0%</label>
        </a>
        <!--#endregion -->
    </div>
    <div class="item">
        <!--#region Ngành hàng chính -->
        <a href="https://www.thegioididong.com/laptop/apple-macbook-air-mqd32sa-a-i5-5350u" class=laptop>
            <img width="180" height="180" data-original="https://cdn.tgdd.vn/Products/Images/44/106875/apple-macbook-air-mqd32sa-a-i5-5350u-400-1-450x300-230x153.jpg" class="lazy">
            <h3>Apple Macbook Air MQD32SA/A i5 1.8GHz (2017)</h3>
            <div class="price">
                <strong>19.990.000₫</strong>
            </div>
            <div class="promo noimage">
                <p>
                    Giảm ngay 1 triệu khi thanh to &#225;n online bằng thẻ Mastercard và <b>1 K.mãi</b>
                    khác
                </p>
            </div>
        </a>
        <!--#endregion -->
    </div>
    <div class="item">
        <!--#region Ngành hàng chính -->
        <a href="https://www.thegioididong.com/laptop/hp-pavilion-x360-ad026tu-i3-7100u" class=laptop>
            <img width="180" height="180" data-original="https://cdn4.tgdd.vn/Products/Images/44/111134/hp-pavilion-x360-ad026tu-i3-7100u-pen-450-450x300-230x153.png" class="lazy">
            <h3>HP Pavilion X360 ad026TU i3 7100U (2GV32PA)</h3>
            <div class="price">
                <strong>13.490.000₫</strong>
            </div>
            <div class="promo noimage">
                <p>
                    Giảm ngay 300.000đ khi mua online (từ 08 - 10/06) và <b>4 K.mãi</b>
                    khác
                </p>
            </div>
            <label class="installment">Trả góp 0%</label>
        </a>
        <!--#endregion -->
    </div>
    <div class="item">
        #region Ngành hàng chính 
        <a href="https://www.thegioididong.com/may-tinh-bang/ipad-wifi-32gb-2018">
            <img width="180" height="180" data-original="https://cdn.tgdd.vn/Products/Images/522/163645/ipad-6th-wifi-32gb-2-400x400.jpg" class="lazy">
            <h3>iPad Wifi 32GB (2018)</h3>
            <div class="price">
                <strong>9.990.000₫</strong>
            </div>
            <div class="promo noimage">
                <p>
                    Giảm 400.000đ khi thanh to &#225;n Online Mastercard và <b>4 K.mãi</b>
                    khác
                </p>
            </div>
            <label class="installment">Trả góp 0%</label>
        </a>
        <!--#endregion -->
    </div>
    <div class="item">
        <!--#region Ngành hàng chính -->
        <a href="https://www.thegioididong.com/may-tinh-bang/lenovo-phab2">
            <img width="180" height="180" data-original="https://cdn2.tgdd.vn/Products/Images/522/78122/lenovo-phab2-543-400x400.jpg" class="lazy">
            <h3>Lenovo Phab 2</h3>
            <div class="price">
                <strong>3.990.000₫</strong>
            </div>
            <div class="promo noimage">
                <p>Giảm ngay 300.000đ khi mua online (từ 08 - 10/06)</p>
            </div>
        </a>
        <!--#endregion -->
    </div>
    <div class="item">
        <!--#region Ngành hàng chính -->
        <a href="https://www.thegioididong.com/may-tinh-bang/ipad-wifi-128-gb-2018">
            <img width="180" height="180" data-original="https://cdn1.tgdd.vn/Products/Images/522/163791/ipad-6th-wifi-128-gb-2-400x400.jpg" class="lazy">
            <h3>iPad Wifi 128 GB (2018)</h3>
            <div class="price">
                <strong>11.990.000₫</strong>
            </div>
            <div class="promo noimage">
                <p>
                    Giảm ngay 500.000đ khi thanh to &#225;n online bằng thẻ Mastercard và <b>4 K.mãi</b>
                    khác
                </p>
            </div>
            <label class="installment">Trả góp 0%</label>
        </a>
        <!--#endregion -->
    </div>
    <div class="item">
        <!--#region Phụ kiện -->
        <a href="https://www.thegioididong.com/loa-laptop/loa-bluetooth-awei-y200">
            <img width="180" height="180" data-original="https://cdn2.tgdd.vn/Products/Images/382/82652/loa-bluetooth-awei-y200-avatar-200x200.jpg" class="lazy">
            <h3>Awei Y200</h3>
            <div class="price">
                <strong>360.000₫</strong>
                <span>600.000₫</span>
            </div>
            <div class="promo noimage">
                <p class="time">
                    Kết thúc sau: <b class="t" id="82652_time" data-time="6/9/2018 11:00:00 PM">12 : 55 : 53</b>
                    <text class="c">
                        Chỉ còn <b>96</b>
                        suất

                
                    </text>
                </p>
            </div>
            <label class="per">Giảm 40%</label>
        </a>
        <!--#endregion -->
    </div>
    <div class="item">
        <!--#region Phụ kiện -->
        <a href="https://www.thegioididong.com/cap-dien-thoai/cap-type-c-1m-x-mobile-mu09-1000">
            <img width="180" height="180" data-original="https://cdn2.tgdd.vn/Products/Images/58/89057/cap-type-c-1m-x-mobile-mu09-1000-1-7-1-200x200.jpg" class="lazy">
            <h3>C &#225;p Xmobile MU09-1000</h3>
            <div class="price">
                <strong>51.000₫</strong>
                <span>100.000₫</span>
            </div>
            <div class="promo noimage">
                <p class="time">
                    Kết thúc sau: <b class="t" id="89057_time" data-time="6/9/2018 11:00:00 PM">12 : 55 : 53</b>
                    <text class="c">
                        Chỉ còn <b>146</b>
                        suất

                
                    </text>
                </p>
            </div>
            <label class="per">Giảm 49%</label>
        </a>
        <!--#endregion -->
    </div>
    <div class="item">
        <!--#region Phụ kiện -->
        <a href="https://www.thegioididong.com/cap-dien-thoai/adapter-sac-xmobile-ds130-tb-1-cong">
            <img width="180" height="180" data-original="https://cdn3.tgdd.vn/Products/Images/58/88848/adapter-sac-xmobile-ds130-tb-1-cong-200x200.jpg" class="lazy">
            <h3>Adapter 1A DS130-TB</h3>
            <div class="price">
                <strong>60.000₫</strong>
                <span>100.000₫</span>
            </div>
            <div class="promo noimage">
                <p class="time">
                    Kết thúc sau: <b class="t" id="88848_time" data-time="6/9/2018 11:00:00 PM">12 : 55 : 53</b>
                    <text class="c">
                        Chỉ còn <b>97</b>
                        suất

                
                    </text>
                </p>
            </div>
            <label class="per">Giảm 40%</label>
        </a>
        <!--#endregion -->
    </div>
    <div class="item">
        <!--#region Phụ kiện -->
        <a href="https://www.thegioididong.com/tai-nghe/tai-nghe-chup-tai-kanen-ip-2050">
            <img width="180" height="180" data-original="https://cdn4.tgdd.vn/Products/Images/54/68924/tai-nghe-chup-tai-kanen-ip-2050-330-200x200.png" class="lazy">
            <h3>Tai nghe chụp tai Kanen IP-2050</h3>
            <div class="price">
                <strong>210.000₫</strong>
                <span>300.000₫</span>
            </div>
            <div class="promo noimage">
                <p class="time">
                    Kết thúc sau: <b class="t" id="68924_time" data-time="6/9/2018 11:00:00 PM">12 : 55 : 53</b>
                    <text class="c">
                        Chỉ còn <b>97</b>
                        suất

                
                    </text>
                </p>
            </div>
            <label class="per">Giảm 30%</label>
        </a>
        <!--#endregion -->
    </div>
    <div class="item">
        <!--#region Phụ kiện -->
        <a href="https://www.thegioididong.com/sac-dtdd/pin-sac-du-phong-7500mah-esaver-la-y103s">
            <img width="180" height="180" data-original="https://cdn.tgdd.vn/Products/Images/57/145555/pin-sac-du-phong-7500mah-esaver-la-y103s-200x200.jpg" class="lazy">
            <h3>Pin sạc dự ph &#242;ng 7.500 mAh eSaver LA Y103S</h3>
            <div class="price">
                <strong>260.000₫</strong>
                <span>400.000₫</span>
            </div>
            <div class="promo noimage">
                <p class="time">
                    Kết thúc sau: <b class="t" id="145555_time" data-time="6/9/2018 11:00:00 PM">12 : 55 : 53</b>
                    <text class="c">
                        Chỉ còn <b>100</b>
                        suất

                
                    </text>
                </p>
            </div>
            <label class="per">Giảm 35%</label>
        </a>
        <!--#endregion -->
    </div>
</div>

<div class="navigat">
    <h2>Điện thoại nổi bật nhất</h2>
    <div class="viewallcat">
        <a href="https://www.thegioididong.com/dtdd-apple-iphone">Apple iPhone</a>
        <a href="https://www.thegioididong.com/dtdd-samsung">Samsung</a>
        <a href="http://thegioididong.com/dtdd?f=man-hinh-tran-vien">M &#224;n h &#236;nh tr &#224;n viền</a>
        <a href="https://www.thegioididong.com/dtdd?p=tu-3-5-trieu">Gi &#225;từ 3-5 triệu</a>
        <a href="https://www.thegioididong.com/dtdd?s=tra-gop-0-phan-tram">Trả g &#243;p 0%</a>
        <a href="https://www.thegioididong.com/dtdd" class="mobile">Xem tất cả điện thoại</a>
    </div>
</div>

<ul class="homeproduct ">
    <li class="feature" data-id="157031">
        <a href="https://www.thegioididong.com/dtdd/samsung-galaxy-a6-2018">
            <img width="600" height="275" data-original="https://cdn1.tgdd.vn/Products/Images/42/157031/Feature/samsung-galaxy-a6-2018-16.jpg" class="lazy">
            <h3>Samsung Galaxy A6 (2018)</h3>
            <div class="price">
                <strong>6.990.000₫</strong>
            </div>
            <label class="installment">Trả góp 0%</label>
        </a>
    </li>
    <li data-id="156205">
        <!--#region Ngành hàng chính -->
        <a href="https://www.thegioididong.com/dtdd/vivo-y85">
            <img width="180" height="180" data-original="https://cdn.tgdd.vn/Products/Images/42/156205/vivo-y85-red-docquyen-400x400.jpg" class="lazy">
            <h3>Vivo Y85</h3>
            <div class="price">
                <strong>5.990.000₫</strong>
            </div>
            <div class="promo">
                <img width="30" height="30" data-original="https://cdn2.tgdd.vn/Products/Images/2102/142312/phieu-mua-hang-200000d-1-100x100.jpg" class="lazy">
                <p>Tặng Phiếu mua h &#224;ng 200.000đ</p>
            </div>
            <label class="installment">Trả góp 0%</label>
        </a>
        <!--#endregion -->
    </li>
    <li data-id="103404">
        <!--#region Ngành hàng chính -->
        <a href="https://www.thegioididong.com/dtdd/samsung-galaxy-j7-pro">
            <img width="180" height="180" data-original="https://cdn4.tgdd.vn/Products/Images/42/103404/samsung-galaxy-j7-pro-hh-400x400.jpg" class="lazy">
            <h3>Galaxy J7 Pro</h3>
            <div class="price">
                <strong>6.090.000₫</strong>
            </div>
            <div class="promo">
                <img width="30" height="30" data-original="https://cdn3.tgdd.vn/Products/Images/2102/164543/thung-cocacola-24-lon-khuyen-mai-100x100.jpg" class="lazy">
                <p>
                    Tặng Th &#249;ng Cocacola 24 lon và <b>1 K.mãi</b>
                    khác
                </p>
            </div>
            <label class="installment">Trả góp 0%</label>
        </a>
        <!--#endregion -->
    </li>
    <li data-id="155261">
        <!--#region Ngành hàng chính -->
        <a href="https://www.thegioididong.com/dtdd/oppo-f7">
            <img width="180" height="180" data-original="https://cdn1.tgdd.vn/Products/Images/42/155261/oppo-f7-bac-400x400.jpg" class="lazy">
            <h3>OPPO F7</h3>
            <div class="price">
                <strong>7.990.000₫</strong>
            </div>
            <div class="promo">
                <img width="30" height="30" data-original="https://cdn4.tgdd.vn/Products/Images/2102/118294/phieu-mua-hang-100000d-1-100x100.jpg" class="lazy">
                <p>
                    Tặng Phiếu mua h &#224;ng 100.000đ khi mua online và <b>1 K.mãi</b>
                    khác
                </p>
            </div>
            <label class="installment">Trả góp 0%</label>
        </a>
        <!--#endregion -->
    </li>
    <li class="feature" data-id="114115">
        <a href="https://www.thegioididong.com/dtdd/iphone-x-64gb">
            <img width="600" height="275" data-original="https://cdn.tgdd.vn/Products/Images/42/114115/Feature/iphone-x-64gb-12.jpg" class="lazy">
            <h3>iPhone X 64GB</h3>
            <div class="price">
                <strong>29.990.000₫</strong>
            </div>
            <label class="installment">Trả góp 0%</label>
        </a>
    </li>
    <li data-id="153854">
        <!--#region Ngành hàng chính -->
        <a href="https://www.thegioididong.com/dtdd/nokia-7-plus">
            <img width="180" height="180" data-original="https://cdn4.tgdd.vn/Products/Images/42/153854/nokia-7-plus-2-400x400.jpg" class="lazy">
            <h3>Nokia 7 plus</h3>
            <div class="price">
                <strong>8.990.000₫</strong>
            </div>
            <div class="promo">
                <img width="30" height="30" data-original="https://cdn3.tgdd.vn/Products/Images/2102/164543/thung-cocacola-24-lon-khuyen-mai-100x100.jpg" class="lazy">
                <p>
                    Tặng Th &#249;ng Cocacola 24 lon và <b>2 K.mãi</b>
                    khác
                </p>
            </div>
            <label class="installment">Trả góp 0%</label>
        </a>
        <!--#endregion -->
    </li>
    <li data-id="147939">
        <!--#region Ngành hàng chính -->
        <a href="https://www.thegioididong.com/dtdd/samsung-galaxy-s9-plus">
            <img width="180" height="180" data-original="https://cdn4.tgdd.vn/Products/Images/42/147939/samsung-galaxy-s9-plus-4-400x400.jpg" class="lazy">
            <h3>Samsung Galaxy S9+ 64GB</h3>
            <div class="price">
                <strong>23.490.000₫</strong>
            </div>
            <div class="promo noimage">
                <p>
                    Giảm ngay 2 triệu và <b>1 K.mãi</b>
                    khác
                </p>
            </div>
            <label class="installment">Trả góp 0%</label>
        </a>
        <!--#endregion -->
    </li>
    <li data-id="118143">
        <!--#region Ngành hàng chính -->
        <a href="https://www.thegioididong.com/dtdd/huawei-nova-2i">
            <img width="180" height="180" data-original="https://cdn3.tgdd.vn/Products/Images/42/118143/huawei-nova-2i-hh-600x600-400x400.jpg" class="lazy">
            <h3>Huawei Nova 2i</h3>
            <div class="price">
                <strong>4.990.000₫</strong>
            </div>
            <label class="installment">Trả góp 0%</label>
        </a>
        <!--#endregion -->
    </li>
</ul>
<div class="navigat">
    <h2>Tablet - Laptop nổi bật nhất</h2>
    <div class="viewallcat">
        <a href="https://www.thegioididong.com/may-tinh-bang-samsung">Samsung Tab</a>
        <a href="http://www.thegioididong.com/laptop-apple-macbook">Macbook</a>
        <a href="https://www.thegioididong.com/laptop-dell">Laptop Dell</a>
        <a href="https://thegioididong.com/laptop-asus">Laptop ASUS</a>
        <a href="https://thegioididong.com/laptop-hp-compaq">Laptop HP</a>
        <a href="https://www.thegioididong.com/may-tinh-bang" class="tablet">Xem tất cả máy tính bảng</a>
        <a href="https://www.thegioididong.com/laptop" class="laptop">Xem tất cả laptop</a>
    </div>
</div>

<ul class="homeproduct ">
    <li class="feature" data-id="160296">
        <a href="https://www.thegioididong.com/laptop/acer-aspire-e5-476-i3-8130u-nxgwtsv002">
            <img width="600" height="275" data-original="https://cdn1.tgdd.vn/Products/Images/44/160296/Feature/acer-aspire-e5-476-i3-8130u-nxgwtsv002-ft3.jpg" class="lazy">
            <h3>Acer Aspire E5 476 i3 8130U (NX.GWTSV.002) X &#225;m</h3>
            <div class="price">
                <strong>9.990.000₫</strong>
            </div>
            <label class="installment">Trả góp 0%</label>
        </a>
    </li>
    <li data-id="135668">
        <!--#region Ngành hàng chính -->
        <a href="https://www.thegioididong.com/laptop/asus-s510ua-i5-8250u-bq414t" class=laptop>
            <img width="180" height="180" data-original="https://cdn3.tgdd.vn/Products/Images/44/135668/asus-s510ua-i5-8250u-bq414t-dai-dien-450-400x400.jpg" class="lazy">
            <h3>Asus S510UA i5 8250U (BQ414T)</h3>
            <div class="price">
                <strong>16.290.000₫</strong>
            </div>
            <div class="promo noimage">
                <p>
                    Tặng 1 th &#249;ng Cocacola 24 lon và <b>3 K.mãi</b>
                    khác
                </p>
            </div>
        </a>
        <!--#endregion -->
    </li>
    <li data-id="111137">
        <!--#region Ngành hàng chính -->
        <a href="https://www.thegioididong.com/laptop/hp-pavilion-x360-ba063tu" class=laptop>
            <img width="180" height="180" data-original="https://cdn2.tgdd.vn/Products/Images/44/111137/hp-pavilion-x360-ba063tu-450-400x400.jpg" class="lazy">
            <h3>HP Pavilion x360 ba063TU (2GV25PA)</h3>
            <div class="price">
                <strong>13.490.000₫</strong>
            </div>
            <div class="promo">
                <img width="30" height="30" data-original="https://cdn.tgdd.vn/Products/Images/54/70820/tai-nghe-chup-tai-kanen-ip-952-11-1-100x100.jpg" class="lazy">
                <p>
                    Tặng Tai nghe chụp tai Kanen IP-952 và <b>2 K.mãi</b>
                    khác
                </p>
            </div>
            <label class="installment">Trả góp 0%</label>
        </a>
        <!--#endregion -->
    </li>
    <li data-id="73280">
        <!--#region Ngành hàng chính -->
        <a href="https://www.thegioididong.com/may-tinh-bang/samsung-galaxy-tab-e-96-sm-t561">
            <img width="180" height="180" data-original="https://cdn.tgdd.vn/Products/Images/522/73280/samsung-galaxy-tab-e-96-sm-t561-75-400x400.jpg" class="lazy">
            <h3>Galaxy Tab E 9.6</h3>
            <div class="price">
                <strong>4.490.000₫</strong>
            </div>
        </a>
        <!--#endregion -->
    </li>
</ul>
<div class="navigat">
    <h2>Phụ kiện giá rẻ</h2>
    <div class="viewallcat">
        <a href="https://www.thegioididong.com/sac-dtdd">Pin sạc dự ph &#242;ng</a>
        <a href="https://www.thegioididong.com/cap-dien-thoai">C &#225;p sạc</a>
        <a href="https://www.thegioididong.com/the-nho-dien-thoai">Thẻ nhớ</a>
        <a href="https://www.thegioididong.com/tai-nghe">Tai nghe</a>
        <a href="https://www.thegioididong.com/loa-laptop">Loa</a>
        <a href="https://www.thegioididong.com/phu-kien/chinh-hang">Phụ kiện ch &#237;nh h &#227;ng</a>
        <a href="https://www.thegioididong.com/gay-tu-suong">Gậy tự sướng</a>
        <a href="https://www.thegioididong.com/phu-kien" class="accessory">Xem tất cả phụ kiện</a>
    </div>
</div>

<ul class="homeproduct phukien">
    <li>
        <!--#region Phụ kiện -->
        <a href="https://www.thegioididong.com/sac-dtdd/pin-sac-du-phong-7500-mah-esaver-yd13">
            <img width="180" height="180" data-original="https://cdn3.tgdd.vn/Products/Images/57/80748/pin-sac-du-phong-7500-mah-esaver-yd13-xanh-la-5-200x200.jpg" class="lazy">
            <h3>eSaver YD13</h3>
            <div class="price">
                <strong>175.000₫</strong>
                <span>250.000₫</span>
            </div>
            <label class="per">Giảm 30%</label>
        </a>
        <!--#endregion -->
    </li>
    <li>
        <!--#region Phụ kiện -->
        <a href="https://www.thegioididong.com/loa-laptop/loa-bluetooth-mifa-bv150">
            <img width="180" height="180" data-original="https://cdn3.tgdd.vn/Products/Images/382/91588/loa-bluetooth-mifa-bv150-avatar-200x200.jpg" class="lazy">
            <h3>Mifa BV150</h3>
            <div class="price">
                <strong>260.000₫</strong>
                <span>400.000₫</span>
            </div>
            <label class="per">Giảm 35%</label>
        </a>
        <!--#endregion -->
    </li>
    <li>
        <!--#region Phụ kiện -->
        <a href="https://www.thegioididong.com/cap-dien-thoai/cap-micro-usb-20cm-x-mobile-mu03">
            <img width="180" height="180" data-original="https://cdn2.tgdd.vn/Products/Images/58/80637/cap-micro-usb-20cm-x-mobile-mu03-avatar-1-200x200.jpg" class="lazy">
            <h3>C &#225;p Xmobile MU03</h3>
            <div class="price">
                <strong>24.000₫</strong>
                <span>40.000₫</span>
            </div>
            <label class="per">Giảm 40%</label>
        </a>
        <!--#endregion -->
    </li>
    <li>
        <!--#region Phụ kiện -->
        <a href="https://www.thegioididong.com/the-nho-dien-thoai/the-nho-microsd-8gb-class-4">
            <img width="180" height="180" data-original="https://cdn2.tgdd.vn/Products/Images/55/69972/the-nho-microsd-8gb-class-4-8-200x200.jpg" class="lazy">
            <h3>Micro SD 8 GB class 4</h3>
            <div class="price">
                <strong>150.000₫</strong>
                <span>200.000₫</span>
            </div>
        </a>
        <!--#endregion -->
    </li>
    <li>
        <!--#region Phụ kiện -->
        <a href="https://www.thegioididong.com/tai-nghe/tai-nghe-chup-tai-kanen-ip-892">
            <img width="180" height="180" data-original="https://cdn4.tgdd.vn/Products/Images/54/86504/tai-nghe-chup-tai-kanen-ip-892-avatar-2-400x400.jpg" class="lazy">
            <h3>Tai nghe chụp tai Kanen IP-892</h3>
            <div class="price">
                <strong>210.000₫</strong>
                <span>300.000₫</span>
            </div>
            <label class="per">Giảm 30%</label>
        </a>
        <!--#endregion -->
    </li>
</ul>