<?php

use backend\models\Schedule;
use backend\models\Supplier;
use backend\models\Content;
use backend\models\Giakhomayin;
use backend\models\Giacong;

?>
<div class="ke-hoach-sx">
<div class="progressbar">
    <?php
    $list_cdsx = [];
    if(!empty($model->data_cong_doan_san_xuat))
        $list_cdsx = unserialize($model->data_cong_doan_san_xuat);

    $definedCongDoan = Schedule::congDoanSanXuat();
    if ($list_cdsx) {
        $i = 0;
        $finish = false;
        foreach ($list_cdsx as $congDoan) {
            if(isset($definedCongDoan[$congDoan['title']])) {
                $i++;
                if(isset($congDoan['finish']) && $congDoan['finish'] == 1){
                    $finish = true;
                }else{
                    $finish = false;
                }
                if ($i > 1)
                    echo '<span class="bar '.($finish || $model->status == $congDoan['title'] ? 'done':'').'"></span>';
                ?>
                <div class="circle<?=$finish ? ' done':($model->status == $congDoan['title'] || (empty($model->status) && $i == 1) ? ' active':'')?>">
                    <span class="label"><?= $finish ? '<i class="fa fa-check" aria-hidden="true"></i>' : $i ?></span>
                    <span class="title"><?= $definedCongDoan[$congDoan['title']]?></span>

                    <?php
                    $note = isset($congDoan['notes']) ? $congDoan['notes'] : '';
                    if ($note != '') { ?>
                        <a href="#" data-toggle="tooltip" data-placement="right" data-html="true" title="<?php echo $note?>"><span class="glyphicon glyphicon-pencil"></span></a>
                    <?php } ?>
                </div>
                <?php
            }
        }
    }
    ?>
</div>
<div class="clearfix"></div>
<div class="expland_down_icon">
    <div class="icon">
        <div class="arrow arrow-1"></div>
        <div class="arrow arrow-2"></div>
    </div>
</div>
<div style="display: none" class="cong_doan_chi_tiet">
<ul class="list-group" style="margin-top: 20px">
    <li class="list-group-item">
        <div class="row">
            <div class="col-md-3">
                <strong>Mã đơn hàng</strong>
            </div>
            <div class="col-md-9">
                <span style="color: #c00; font-weight: bold; font-size: 15px"><?= isset($model->orderInfo->infoCode) ? $model->orderInfo->infoCode : '' ?></span>
            </div>
        </div>
    </li>
    <li class="list-group-item header_box">
        <div class="row">
            <div class="col-md-12">
                Thông tin sản phẩm
            </div>
        </div>
    </li>
    <li class="list-group-item">
        <div class="row">
            <div class="col-sm-3">
                Sản phẩm: <?= isset($model->orderInfo->product->title) ? $model->orderInfo->product->title : '' ?>
            </div>
            <div class="col-sm-3">
                Số lượng: <?= isset($model->orderInfo->amount) ? $model->orderInfo->amount : '' ?>
            </div>
            <div class="col-sm-3">
                Số trang
                ruột: <?= isset($model->orderInfo->inner_page_amount) ? $model->orderInfo->inner_page_amount : 2 ?>
            </div>
            <div class="col-sm-3">
                Kích
                thước: <?= isset($model->orderInfo->length) ? "{$model->orderInfo->length} x {$model->orderInfo->width} (Dài x Rộng)" : '' ?>
            </div>
        </div>
    </li>
    <li class="list-group-item header_box">
        <div class="row">
            <div class="col-md-12">
                Giấy in
            </div>
        </div>
    </li>
    <li class="list-group-item">
        <table cellpadding="10" cellspacing="0" border="0"
               class="adminlist border">
            <thead>
            <tr>
                <th class="">Giấy</th>
                <th class="">Nhà cung cấp</th>
                <th class="">Chất liệu</th>
                <th class="">Định lượng</th>
                <th>Khổ giấy (dài-rộng)</th>
                <th class="">Thành phẩm / tờ</th>
                <th>Số tờ</th>
                <th>Số tờ bù hao</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if (!empty($model->orderInfo->paper)) :
                $giayIn = $model->orderInfo->paper;
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
                        $inbia = Supplier::getById($giayIn->GiayBiaNhaCungCap);
                        echo isset($inbia->name) ? $inbia->name : '';
                        ?>
                    </td>
                    <td>
                        <?php
                        $chat_lieu = Content::find()->where(['content_id' => $giayIn->GiayBiaChatLieu])->one();
                        echo isset($chat_lieu->title) ? $chat_lieu->title : '';
                        ?>
                    </td>
                    <td>
                        <?php
                        echo $giayIn->GiayBiaDinhLuong;
                        ?> gsm
                    </td>
                    <td>
                        <?php
                        echo "{$giayIn->GiayBiaLength} x {$giayIn->GiayBiaWidth} cm";
                        ?>
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
                if ($model->orderInfo->hasInnerPage):
                    ?>
                    <tr>
                        <td style="font-weight: bold; color: #080">Giấy ruột</td>
                        <td>
                            <?php
                            $giay_ruot = Supplier::getById($giayIn->GiayRuotNhaCungCap);
                            echo isset($giay_ruot->name) ? $giay_ruot->name : '';
                            ?>
                        </td>
                        <td>
                            <?php
                            $chat_lieu = Content::find()->where(['content_id' => $giayIn->GiayRuotChatLieu])->one();
                            echo isset($chat_lieu->title) ? $chat_lieu->title : '';
                            ?>
                        </td>


                        <td>
                            <?php
                            echo $giayIn->GiayRuotDinhLuong;
                            ?> gsm
                        </td>
                        <td>
                            <?php
                            echo "{$giayIn->GiayRuotLength} x {$giayIn->GiayRuotWidth} cm";
                            ?>
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
                                $ncc_giay = Supplier::getById($giay_ruot_mo_rong->GiayRuotNhaCungCap);
                                echo isset($ncc_giay->name) ? $ncc_giay->name : '';
                                ?>
                            </td>
                            <td>
                                <?php
                                $chat_lieu = Content::find()->where(['content_id' => $giay_ruot_mo_rong->GiayRuotChatLieu])->one();
                                echo isset($chat_lieu->title) ? $chat_lieu->title : '';
                                ?>
                            </td>

                            <td>
                                <?php
                                echo $giay_ruot_mo_rong->GiayRuotDinhLuong;
                                ?> gsm
                            </td>
                            <td>
                                <?php
                                echo "{$giay_ruot_mo_rong->GiayRuotLength} x {$giay_ruot_mo_rong->GiayRuotWidth} cm";
                                ?>
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
            endif;
            ?>
            </tbody>
        </table>
    </li>
    <li class="list-group-item header_box">
        <div class="row">
            <div class="col-md-12">
                Kiểu in
            </div>
        </div>
    </li>
    <li class="list-group-item">
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
            if (!empty($model->orderInfo)){
                $primaryInfo = $model->orderInfo;
            if (!empty($primaryInfo->KieuInMoRong)) {
                $arr_kieuinmorong = json_decode($primaryInfo->KieuInMoRong, true);
                $count_kimr = count($arr_kieuinmorong);
            } else {
                $count_kimr = 0;
            }

            $code_rowspan = 2 + $count_kimr;
            if (!$model->orderInfo->hasInnerPage) {
                $code_rowspan = 1;
            }
            ?>
            <tbody>
            <tr>
                <td style="font-weight: bold; color: #080">In bìa</td>
                <td>
                    <?php
                    echo $model->orderInfo->KieuIn_;
                    ?>
                </td>
                <td>
                    <?php
                    $ncc_in_bia = Supplier::getById($model->orderInfo->NhaCungCapIn);
                    echo !empty($ncc_in_bia) ? $ncc_in_bia->name : '';
                    ?>
                </td>
                <td>
                    <?php
                    $kho_may_in = Giakhomayin::getTitleKhoMay($model->orderInfo->KhoMayInBia);
                    if ($model->orderInfo->checkbox_hasnt_formula_kieu_in != 1 && !empty($kho_may_in))
                        echo $kho_may_in->name;
                    ?>
                </td>
                <td style="text-align: center">
                    <?php
                    echo $model->orderInfo->SoMauInBia;
                    ?>
                </td>
                <td style="text-align: center">
                    <?php
                    echo $model->orderInfo->SoMatInBia;
                    ?>
                </td>
                <td style="text-align: center">
                    <?php
                    echo $model->orderInfo->QuyCachIn;
                    ?>
                </td>
            </tr>
            <?php
            if ($model->orderInfo->hasInnerPage):
                ?>
                <tr>
                    <td style="font-weight: bold; color: #080">In ruột</td>
                    <td>
                        <?php
                        echo $model->orderInfo->KieuIn_;
                        ?>
                    </td>
                    <td>
                        <?php
                        $ncc_in_ruot = Supplier::getById($model->orderInfo->NhaCungCapIn);
                        echo !empty($ncc_in_ruot) ? $ncc_in_ruot->name : '';
                        ?>
                    </td>
                    <td>
                        <?php
                        $kho_may_in_ruot = Giakhomayin::getTitleKhoMay($model->orderInfo->KhoMayInRuot);
                        if ($model->orderInfo->checkbox_hasnt_formula_kieu_in != 1 && !empty($kho_may_in_ruot))
                            echo $kho_may_in_ruot->name;
                        ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo $model->orderInfo->SoMauInRuot ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo $model->orderInfo->SoMatInRuot ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo $model->orderInfo->QuyCachInRuot ?>
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
                            echo $model->orderInfo->KieuIn_;
                            ?>
                        </td>
                        <td>
                            <?php
                            echo $ncc_in_ruot_ = Supplier::getById($model->orderInfo->NhaCungCapIn);
                            echo !empty($ncc_in_ruot_) ? $ncc_in_ruot_->name : '';
                            ?>
                        </td>
                        <td>
                            <?php
                            $kho_may_in_ruot_ = Giakhomayin::getTitleKhoMay($kieu_in_mo_rong->KhoMayInRuot);
                            if ($model->orderInfo->checkbox_hasnt_formula_kieu_in != 1 && !empty($kho_may_in_ruot_))
                                echo $kho_may_in_ruot_->name;
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
            }
            ?>

        </table>
    </li>
    <li class="list-group-item header_box">
        <div class="row">
            <div class="col-md-12">
                Gia công
            </div>
        </div>
    </li>
    <li class="list-group-item">
        <?php $list_gia_cong = Giacong::find()->where(['order_info_id' => $model->orderInfo->info_id])->all();
        if (!empty($list_gia_cong)):
            ?>
            <table style="width: 100%" class="adminlist ">
                <tr>
                    <th></th>
                    <th>Loại giấy</th>
                    <th>Nhà cung cấp</th>
                    <th>Loại gia công</th>
                    <th style="text-align: center">Số mặt</th>
                    <th>Khổ can</th>
                </tr>
                <?php $i_gc = 1;
                foreach ($list_gia_cong as $gc): ?>
                    <tr>
                        <td>Gia công <?= $i_gc ?></td>
                        <td><?= $gc->getBiaOrRuot($gc->Bia_Ruot); ?></td>
                        <td><?php
                            $supplier_gc = Supplier::getById($gc->NhaCungCapId);

                            echo !empty($supplier_gc) && isset($supplier_gc['name']) ? $supplier_gc['name'] : '';
                            ?></td>
                        <td><?php
                            $loai_gc = Content::find()->where(['content_id' => $gc->LoaiGiaCongId])->one();
                            echo !empty($loai_gc) && isset($loai_gc['title']) ? $loai_gc['title'] : '';

                            ?></td>
                        <td style="text-align: center">
                            <?= $gc->SoMat ?>
                        </td>
                        <td><?php echo "$gc->Length x $gc->Width cm (Dài x Rộng)"; ?>
                        </td>
                    </tr>
                    <?php $i_gc++; endforeach; ?>
            </table>
        <?php endif; ?>
    </li>
</ul>
</div>
</div>