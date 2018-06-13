<?php
use aabc\helpers\ArrayHelper;
use common\components\Tuyen;
$footer = Tuyen::_dulieu('module', '6');

?>

<footer>
    <div class="rowfoot1">
        <ul class="colfoot">
            <?php $dem = 0; ?>
            <?php foreach ($footer as $k => $v) { ?>
                <?php $dem += 1; ?>
                <li class="<?= ($dem > 5?'hidden':'')?>">
                    <a title="Thông tin trao thưởng"><?= $v['label']?></a>
                </li>
                <?php if($dem == 5){ ?>
                    <li class="showmore">
                        <a href="javascript:ShowMoreFooterSupportLink()" title="Xem thêm">Xem thêm</a>
                    </li>
                <?php } ?>
            <?php } ?>            
        </ul>


        <ul class="colfoot">
            <?php $dem = 0; ?>
            <?php foreach ($footer as $k => $v) { ?>
                <?php $dem += 1; ?>
                <li class="<?= ($dem > 5?'hidden':'')?>">
                    <a title="Thông tin trao thưởng"><?= $v['label']?></a>
                </li>
                <?php if($dem == 5){ ?>
                    <li class="showmore">
                        <a href="javascript:ShowMoreFooterSupportLink()" title="Xem thêm">Xem thêm</a>
                    </li>
                <?php } ?>
            <?php } ?>            
        </ul>


        <ul class="colfoot">
            <li>
                <p>
                    Gọi mua hàng <a href="tel:18001060">1800.1060</a>
                    (7:30 - 22:00)
                </p>
                <p>
                    Gọi khiếu nại <a href="tel:18001062">1800.1062</a>
                    (8:00 - 21:30)
                </p>
                <p>
                    Gọi bảo hành <a href="tel:18001064">1800.1064</a>
                    (8:00 - 21:00)
                </p>
                <p>
                    Hỗ trợ kỹ thuật <a href="tel:18001763">1800.1763</a>
                    (7:30 - 22:00)
                </p>
                <a target="_blank" rel="nofollow" class="bct" href="http://online.gov.vn/HomePage/CustomWebsiteDisplay.aspx?DocId=20098">
                    <i class="icontgdd-bct"></i>
                </a>
                
                
            </li>
        </ul>
        <ul class="colfoot collast">
            <li>
                <a target="_blank" href="https://facebook.com/thegioididongcom" class="linkfb">
                    <i class="icontgdd-share1"></i>
                    3.2tr
        
                </a>
                <a target="_blank" href="https://www.youtube.com/user/TGDDVideoReviews?sub_confirmation=1" class="linkyt">
                    <i class="icontgdd-share3"></i>
                    380.2k
        
                </a>
                <div class="group">
                    <label>Website cùng tập đoàn:</label>
                    <a href="http://www.dienmayxanh.com/" target="_blank" rel="noopener" class="dm">
                        <i class="iconlogo-dmx"></i>
                    </a>
                    <a href="http://www.bachhoaxanh.com/" target="_blank" rel="noopener" class="bhx">
                        <i class="iconlogo-bhx"></i>
                    </a>
                    <a href="http://www.vuivui.com/" target="_blank" rel="noopener" class="vv">
                        <i class="iconlogo-vv"></i>
                    </a>
                    <a href="http://www.trananh.vn/" target="_blank" rel="noopener" class="vv">
                        <i class="iconlogo-ta"></i>
                    </a>
                </div>
            </li>
        </ul>
    </div>
    <div class="rowfoot2">
        <p>
            © 2017. Công ty cổ phần Thế Giới Di Động. GPDKKD: 0303217354 do sở KH &ĐT TP.HCM cấp ngày 02/01/2007. GP số 22/GP-ICP-STTTT do Sở TTTT TP HCM cấp ngày 20/03/2012.
Địa chỉ: 128 Trần Quang Khải, P. Tân Định, Q.1, TP.Hồ Chí Minh. Điện thoại: 18001060. Email: cskh@thegioididong.com. Chịu trách nhiệm nội dung: Huỳnh Văn Tốt. <a href="https://www.thegioididong.com/tos">Xem chính sách sử dụng web</a>
        </p>
    </div>
</footer>