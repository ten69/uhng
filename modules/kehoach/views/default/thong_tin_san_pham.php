<?php

use yii\helpers\Html;
use kartik\dialog\Dialog;
use kartik\form\ActiveForm;
use kartik\date\DatePicker;
use kartik\widgets\DateTimePicker;
use backend\assets\CustomAsset;
use yii\helpers\Url;
use yii\web\View;
use backend\helpers\AcpHelper;
use backend\models\OrderInfo;
use backend\models\Content;
use backend\models\Orders;
use backend\models\Supplier;
use backend\models\Giacong;
use backend\models\OtherCost;
use backend\models\Giakhomayin;
use backend\models\Orderdata;
use backend\models\User;
use backend\models\Hoahongnv;
use backend\models\Role;
use backend\models\Congno;
use backend\models\Schedule;
use backend\models\Products;
use backend\models\ThanhphamImport;

$settings = Yii::$app->settings;
/* @var $this yii\web\View */
/* @var $model backend\models\Orders */
?>

<ul class="list-group">    
    <li class="list-group-item header_box">
        <div class="col-md-12">
            Thông tin chung
        </div>
        <div class="clearfix"></div>
    </li>

    <li class="list-group-item">
        <div class="col-md-3">
            <strong>Sản phẩm</strong>
        </div>
        <div class="col-md-9">
            <?= $info->product->title ?>
        </div>
        <div class="clearfix"></div>
    </li>
    <li class="list-group-item">
        <div class="col-md-3">
            <strong>Số lượng</strong>
        </div>
        <div class="col-md-9">
            <?= $info->amount ?>
        </div>
        <div class="clearfix"></div>
    </li>
    <?php if ($info->has_inner_page == 1): ?>
        <li class="list-group-item">
            <div class="col-md-3">
                <strong>Số trang ruột</strong>
            </div>
            <div class="col-md-9">
                <?= $info->inner_page_amount ?>
            </div>
            <div class="clearfix"></div>
        </li>
    <?php endif; ?>
    <?php
    $trang_ruot_them = !empty($info->ext_inner_page_amount) ? json_decode($info->ext_inner_page_amount) : array();
    if (!empty($trang_ruot_them)):
        $i_trang_ruot_them = 2;
        foreach ($trang_ruot_them as $v):
            ?>
            <li class="list-group-item">
                <div class="col-md-3">
                    <strong>Số trang ruột <?= $i_trang_ruot_them ?></strong>
                </div>
                <div class="col-md-9">
                    <?= number_format($v); ?>
                </div>
                <div class="clearfix"></div>
            </li>
            <?php
        endforeach;endif;
    ?>
    <li class="list-group-item">
        <div class="col-md-3">
            <strong>Dữ liệu thiết kế</strong>
        </div>
        <div class="col-md-9">
            <?php if (!empty($info->file_design) && !empty($info->customer_id)): ?>
                <!--                                            $attach = Attach-->
            <?php endif; ?>
        </div>
        <div class="clearfix"></div>
    </li>
    <li class="list-group-item">
        <div class="col-md-3">
            <strong>Kích thước</strong>
        </div>
        <div class="col-md-9">
            <b>Dài:</b> <?php echo $info->length; ?>cm -
            <b>Rộng:</b> <?php echo $info->width; ?>cm
        </div>
        <div class="clearfix"></div>
    </li>
  
    



    <?php if ($info->is_photo != 1): ?>
        <li class="list-group-item header_box">
            <div class="col-md-12">
                Xuất ra
            </div>
            <div class="clearfix"></div>
        </li>
    <?php endif; ?>
    <li class="list-group-item">
        <div class="col-md-12">
            <?php if (!empty($info->XuatRaMoRong)): ?>
                <table style="width: 100%" class="adminlist ">
                    <tr>
                        <th>STT</th>
                        <th>Loại xuất ra</th>
                        <th>Ra phim/kẽm</th>
                        <th>Nhà cung cấp</th>
                        <th>Kích thước (cm)</th>                        
                        <th style="text-align: center">Ra phim khuôn</th>                        
                    </tr>
                    <?php
                    $xuatramorong = json_decode($info->XuatRaMoRong);
                    $i_xrmr = 0;
                    foreach ($xuatramorong as $xuatra):
                        $ncc = Supplier::find()->where(['supplierid' => $xuatra->ExportNhaCungCap])->one();
                        if ($xuatra->ExportBiaRuot == 'bia') {
                            $loai_giay = 'Bìa';
                        } else {
                            $stt = str_replace('ruot', '', $xuatra->ExportBiaRuot);
                            if ($stt > 0)
                                $loai_giay = 'Ruột ' . $stt;
                            else
                                $loai_giay = 'Ruột';
                            if (isset($xuatra->ExportAddColor) && $xuatra->ExportAddColor == 1)
                                $ra_phim_khuon = 'Có';
                            else
                                $ra_phim_khuon = 'Không';
                        }
                        $i_xrmr++;
                        ?>
                        <tr>
                            <td style="text-align: center"><?= $i_xrmr ?></td>
                            <td><?= $loai_giay ?></td>
                            <td><?= $xuatra->ExportType == 1 ? "Ra phim" : "Ra kẽm" ?></td>
                            <td><?= $ncc != '' ? $ncc->name : "" ?></td>
                            <td><?= 'Dài: ' . $xuatra->ExportLength . ' - Rộng: ' . $xuatra->ExportWidth ?></td>
                            <td style="text-align: center"><?= isset($xuatra->ExportAddColor) && $xuatra->ExportAddColor == 1 ? "Có" : "Không" ?></td>                            
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </div>
        <div class="clearfix"></div>
    </li>

    <li class="list-group-item header_box">
        <div class="col-md-12">
            Gia công
        </div>
        <div class="clearfix"></div>
    </li>

    <li class="list-group-item">
        <div class="col-md-12">
            <?php $list_gia_cong = Giacong::find()->where(['order_info_id' => $info->info_id])->all();
            if (!empty($list_gia_cong)):
                ?>
                <table style="width: 100%" class="adminlist ">
                    <tr>
                        <th>#</th>
                        <th>Loại giấy</th>
                        <th>Nhà cung cấp</th>
                        <th>Loại gia công</th>
                        <th style="text-align: center">Số mặt</th>
                        <th>Khổ can</th>
                        <th style="text-align: right">Đơn giá (đ)</th>
                        <th style="text-align: right">Chi phí (đ)</th>
                    </tr>
                    <?php $i_gc = 1;
                    foreach ($list_gia_cong as $gc): ?>
                        <tr>
                            <td>Gia công <?= $i_gc ?></td>
                            <td><?= $gc->getBiaOrRuot($gc->Bia_Ruot); ?></td>
                            <td><?php
                                $supplier_gc = Supplier::getById($gc->NhaCungCapId);

                                print_r($supplier_gc['name']);
                                ?></td>
                            <td><?php
                                $loai_gc = Content::find()->where(['content_id' => $gc->LoaiGiaCongId])->one();
                                echo $loai_gc['title'];

                                ?></td>
                            <td style="text-align: center">
                                <?= $gc->SoMat ?>
                            </td>
                            <td><b>Dài:</b> <?php echo $gc->Length; ?>cm -
                                <b>Rộng:</b> <?php echo $gc->Width; ?>cm
                            </td>
                            <td style="text-align: right"><?php echo $gc->DonGia . ' ' . $loai_gc['donViTinh']; ?></td>
                            <td style="text-align: right"><?= number_format($gc->ChiPhiGiaCong) ?></td>
                        </tr>
                        <?php $i_gc++; endforeach; ?>
                </table>
            <?php endif; ?>
        </div>
        <div class="clearfix"></div>
    </li>

   

</ul>

<table cellpadding="10" cellspacing="0" border="0" class="adminlist">
    <tbody>
    <tr>
        <th class="th" colspan="10">Giấy in</th>
    </tr>
    <tr>
        <td class="">
            <table cellpadding="10" cellspacing="0" border="0"
                   class="adminlist border">
                <thead>
                <tr>
                    <th class="">Giấy</th>
                    <th class="">Nhà cung cấp</th>
                    <th class="">Chất liệu</th>
                    <th class="">Định lượng</th>
                    <th colspan="2" style="text-align: center" class="">Khổ giấy
                        (dài-rộng)
                    </th>                    
                    <th class="">Thành phẩm / tờ</th>
                    <th>Số tờ</th>
                    <th>Số tờ bù hao</th>                    
                </tr>
                </thead>
                <tbody>
                <?php $giayIn = $info->paper;
                if ($giayIn->GiayRuotMoRong) {
                    $arr_giayruotmorong = json_decode($giayIn->GiayRuotMoRong, true);
                    $count_grmr = count($arr_giayruotmorong);
                } else {
                    $count_grmr = 0;
                }
                ?>
                <tr>
                    <td style="font-weight: bold; color: #080">Giấy bìa</td>
                    <td>
                        <?php
                        echo Supplier::getById($giayIn->GiayBiaNhaCungCap)->name;
                        ?>
                    </td>
                    <td>
                        <?php
                        $chat_lieu = Content::find()->where(['content_id' => $giayIn->GiayBiaChatLieu])->one();
                        echo $chat_lieu->title;
                        ?>
                    </td>
                    <td>
                        <?php
                        echo $giayIn->GiayBiaDinhLuong;
                        ?> gsm
                    </td>
                    <td>
                        <?php
                        echo $giayIn->GiayBiaLength;
                        ?> cm
                    </td>
                    <td>
                        <?php
                        echo $giayIn->GiayBiaWidth;
                        ?> cm
                    </td>                    
                    <td>
                        <?php
                        echo $giayIn->GiayBiaThanhPham;
                        ?>
                    </td>
                    <td>
                        <?php
                        echo $giayIn->GiayBiaSoTo;
                        ?>
                    </td>
                    <td>
                        <?php
                        echo $giayIn->GiayBiaSoToBuHao;
                        ?>
                    </td>                    
                </tr>

                <?php
                if ($info->hasInnerPage):
                    ?>
                    <tr>
                        <td style="font-weight: bold; color: #080">Giấy ruột</td>
                        <td>
                            <?php
                            echo Supplier::getById($giayIn->GiayRuotNhaCungCap)->name;
                            ?>
                        </td>
                        <td>
                            <?php
                            $chat_lieu = Content::find()->where(['content_id' => $giayIn->GiayRuotChatLieu])->one();
                            echo $chat_lieu->title;
                            ?>
                        </td>


                        <td>
                            <?php
                            echo $giayIn->GiayRuotDinhLuong;
                            ?> gsm
                        </td>
                        <td>
                            <?php
                            echo $giayIn->GiayRuotLength;
                            ?> cm
                        </td>
                        <td>
                            <?php
                            echo $giayIn->GiayRuotWidth;
                            ?> cm
                        </td>
                        
                        <td>
                            <?php
                            echo $giayIn->GiayRuotThanhPham;
                            ?>
                        </td>
                        <td>
                            <?php
                            echo $giayIn->GiayRuotSoTo;
                            ?>
                        </td>
                        <td>
                            <?php
                            echo $giayIn->GiayRuotSoToBuHao;
                            ?>
                        </td>
                       
                    </tr>
                    <?php
                endif;
                ?>

                <?php
                if ($giayIn->GiayRuotMoRong):
                    $arr_giayruotmorong = json_decode($giayIn->GiayRuotMoRong);
                    foreach ($arr_giayruotmorong as $key => $giay_ruot_mo_rong):
                        ?>
                        <tr>
                            <td style="font-weight: bold; color: #080">Giấy
                                ruột <?= $key ?></td>
                            <td>
                                <?php
                                echo Supplier::getById($giay_ruot_mo_rong->GiayRuotNhaCungCap)->name;
                                ?>
                            </td>
                            <td>
                                <?php
                                $chat_lieu = Content::find()->where(['content_id' => $giay_ruot_mo_rong->GiayRuotChatLieu])->one();
                                echo $chat_lieu->title;
                                ?>
                            </td>

                            <td>
                                <?php
                                echo $giay_ruot_mo_rong->GiayRuotDinhLuong;
                                ?> gsm
                            </td>
                            <td>
                                <?php
                                echo $giay_ruot_mo_rong->GiayRuotLength;
                                ?> cm
                            </td>
                            <td>
                                <?php
                                echo $giay_ruot_mo_rong->GiayRuotWidth;
                                ?> cm
                            </td>
                           
                            <td>
                                <?php
                                echo $giay_ruot_mo_rong->GiayRuotThanhPham;
                                ?>
                            </td>
                            <td>
                                <?php
                                echo $giay_ruot_mo_rong->GiayRuotSoTo;
                                ?>
                            </td>
                            <td>
                                <?php
                                echo $giay_ruot_mo_rong->GiayRuotSoToBuHao;
                                ?>
                            </td>
                           
                        </tr>
                        <?php
                    endforeach;
                endif;
                ?>

                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>

<table cellpadding="10" cellspacing="0" border="0" class="adminlist">
    <tr>
        <th class="th" colspan="10">Kiểu in</th>
    </tr>
    <tr>
        <td>
            <table cellpadding="10" cellspacing="0" border="0"
                   class="adminlist border">
                <thead>
                <tr>
                    <th></th>
                    <th>Kỹ thuật in</th>
                    <th>Nhà cung cấp</th>
                    <th>Khổ máy in</th>                    
                    <th style="text-align: center">Số màu in</th>
                    <th style="text-align: center">Số mặt in</th>
                    <th style="text-align: center">Quy cách in</th>
                </tr>
                </thead>

                <?php
                $primaryInfo = $info;
                if (!empty($primaryInfo->KieuInMoRong)) {
                    $arr_kieuinmorong = json_decode($primaryInfo->KieuInMoRong, true);
                    $count_kimr = count($arr_kieuinmorong);
                } else {
                    $count_kimr = 0;
                }

                $code_rowspan = 2 + $count_kimr;
                if (!$info->hasInnerPage) {
                    $code_rowspan = 1;
                }
                ?>
                <tbody>
                <tr>

                    <td style="font-weight: bold; color: #080">In bìa</td>
                    <td>
                        <?php
                        echo $info->KieuIn_;
                        ?>
                    </td>
                    <td>
                        <?= Supplier::getById($info->NhaCungCapIn) != false ? Supplier::getById($info->NhaCungCapIn)->name : "" ?>
                    </td>
                    <td>
                        <?php
                        if ($info->checkbox_hasnt_formula_kieu_in != 1)
                            echo Giakhomayin::getTitleKhoMay($info->KhoMayInBia)->name;
                        ?>
                    </td>
                   
                    <td style="text-align: center">
                        <?php
                        echo $info->SoMauInBia;
                        ?>
                    </td>
                    <td style="text-align: center">
                        <?php
                        echo $info->SoMatInBia;
                        ?>
                    </td>
                    <td style="text-align: center">
                        <?php
                        echo $info->QuyCachIn;
                        ?>
                    </td>
                    
                </tr>
                <?php
                if ($info->hasInnerPage):
                    ?>
                    <tr>
                        <td style="font-weight: bold; color: #080">In ruột</td>
                        <td>
                            <?php
                            echo $info->KieuIn_;
                            ?>
                        </td>
                        <td>
                            <?= Supplier::getById($info->NhaCungCapIn) != false ? Supplier::getById($info->NhaCungCapIn)->name : "" ?>
                        </td>
                        <td>
                            <?php
                            if ($info->checkbox_hasnt_formula_kieu_in != 1)
                                echo Giakhomayin::getTitleKhoMay($info->KhoMayInRuot)->name;
                            ?>
                        </td>
                        
                        <td style="text-align: center">
                            <?php echo $info->SoMauInRuot ?>
                        </td>
                        <td style="text-align: center">
                            <?php echo $info->SoMatInRuot ?>
                        </td>
                        <td style="text-align: center">
                            <?php echo $info->QuyCachInRuot ?>
                        </td>
                        
                    </tr>
                    <?php
                endif;
                ?>
                <?php
                if ($primaryInfo->KieuInMoRong):
                    $arr_kimr = json_decode($primaryInfo->KieuInMoRong);
                    foreach ($arr_kimr as $key => $kieu_in_mo_rong):
                        ?>
                        <tr>
                            <td style="font-weight: bold; color: #080">In
                                ruột <?= $key ?></td>
                            <td>
                                <?php
                                echo $info->KieuIn_;
                                ?>
                            </td>
                            <td>
                                <?= Supplier::getById($info->NhaCungCapIn) != false ? Supplier::getById($info->NhaCungCapIn)->name : "" ?>
                            </td>
                            <td>
                                <?php
                                if ($info->checkbox_hasnt_formula_kieu_in != 1)
                                    echo Giakhomayin::getTitleKhoMay($kieu_in_mo_rong->KhoMayInRuot)->name;
                                ?>
                            </td>
                            
                            <td style="text-align: center">
                                <?php echo $kieu_in_mo_rong->SoMauInRuot ?>
                            </td>
                            <td style="text-align: center">
                                <?php echo $kieu_in_mo_rong->SoMatInRuot ?>
                            </td>
                            <td style="text-align: center">
                                <?php echo $kieu_in_mo_rong->QuyCachInRuot ?>
                            </td>
                            
                        </tr>
                        <?php
                    endforeach;
                endif;
                ?>
                </tbody>
                <?php

                ?>

            </table>
        </td>
    </tr>
</table>
<table cellpadding="10" cellspacing="0" border="0" class="adminlist">
    <tr>
        <th class="th">In test</th>
    </tr>
    <tr>
        <td>

            <table cellpadding="10" cellspacing="0" border="0" class="adminlist border">
                <thead>
                <tr>
                    <th>Nhà cung cấp</th>
                    <th>Số tờ</th>
                </tr>
                </thead>
                <?php
                if ($info->orderdata->InTest == 1):
                    ?>

                    <tbody>
                    <tr>

                        <td>
                            <?php
                            echo Supplier::getById($info->orderdata->NhaCungCapInTest)->name;
                            ?>
                        </td>

                        <td>
                            <?php
                            echo($info->orderdata->SoToInTest);
                            ?>
                        </td>
                        
                    </tr>
                    </tbody>
                    <?php
                endif;
                ?>
            </table>

        </td>
    </tr>
</table>