<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use yii\helpers\Url;
use kartik\widgets\DatePicker;
use kartik\select2\Select2;
use backend\models\Content;

use backend\modules\kehoach\models\Kehoach;

use backend\models\Role;
use backend\models\Schedule;
use backend\models\Devices;

use kartik\dialog\Dialog;
use kartik\widgets\DateTimePicker;
use unclead\multipleinput\MultipleInput;
use unclead\multipleinput\MultipleInputColumn;

if(empty($ttsp)) $ttsp = '';
/* @var $this yii\web\View */
/* @var $model backend\models\Plan */
/* @var $form yii\widgets\ActiveForm */
echo Dialog::widget();

$role = Role::find()->where(['role_id' => Yii::$app->user->identity->role_id])->one();
if(!empty($role) && $role->acl_desc != 'ALL_PRIVILEGES') {
    if (!empty($role) && !empty($role->allow_cdsx)) {
        $cdsx = json_decode($role->allow_cdsx);
        if(!empty($cdsx)){
            $css = <<<XP
    .plan-form .panel{pointer-events: none;}
XP;
            foreach($cdsx as $v){
                $css .= ".note-{$v}{pointer-events: auto;}";
            }
        $this->registerCss($css);
        }
    }else{
        Yii::$app->response->redirect(['index']);
    }
}
?>

<div class="plan-form">

    <style type="text/css">
        .form-group.has-error .help-block {
            position: absolute;
            top: -5px;
            right: 15px;
        }
        li.list-group-item.header_box {
            background: #e4e4e4;
            font-size: 16px;
        }
    </style>

        <?php
        $form = ActiveForm::begin(['enableAjaxValidation' => false]);

        if(empty($model->trang_thai)) $model->trang_thai = 0;

        echo $form->field($model, 'trang_thai', ['options' => ['tag' => false]])->hiddenInput()->label(false);
        ?>
        <div id="" class="panel panel-success panel-item">
            <div class="panel-heading panel-heading-item">
                <h3 class="panel-title">Thông tin chung</h3>
                <span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
            </div>
            <div class="panel-body">
                <div class="row">                    
                    <div class="col-sm-6">
                        <?= $form->field($model, "code")->textInput(['maxlength' => true]) ?>
                        <?= $form->field($model, "san_pham_ghep", ['options' => ['class' => 'hide', 'tag' => false]])->hiddenInput()->label(false) ?>
                    </div>

                     <div class="col-sm-6">
                        <?php                            
                        echo $form->field($model, "muc_do")->widget(Select2::classname(), [
                            'data' => Kehoach::getMucdouutienOptions(),
                            'options' => [                                    
                                'placeholder' => 'Chọn',
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,                                              
                            ],
                        ]) ?>
                    </div>

                     <div class="col-sm-6">
                        <?= $form->field($model, "id_don_hang")->widget(Select2::classname(), [
                            'data' => Kehoach::getOrdersOptions(),
                            'options' => [
                                'placeholder' => 'Chọn đơn hàng',
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,                                              
                            ],
                        ]) ?>
                    </div>
                    <div class="col-sm-6">
                        <?php
                        $optionDataAttributes = Kehoach::getProductOptions($model->id_don_hang, true);
                        echo $form->field($model, "id_san_pham")->widget(Select2::classname(), [
                            'data' => Kehoach::getProductOptions($model->id_don_hang),
                            'options' => [
                                'options' => $optionDataAttributes,
                                'placeholder' => 'Chọn sản phẩm',
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,                                              
                            ],
                        ]) ?>
                    </div>

                     <div class="col-sm-6">
                        
                        <?= $form->field($model, "ngay_san_xuat")->widget(DateTimePicker::classname(), [
                            'type' => DateTimePicker::TYPE_COMPONENT_PREPEND,                                
                            'options' => ['placeholder' => 'Ngày sản xuất', 'class' => 'dateInput'],
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format' => 'hh:ii dd-mm-yyyy',                                    
                                'todayHighlight' => true,                                    
                            ]
                        ]) ?>

                       
                    </div>

                    <div class="col-sm-6">
                        
                        <?= $form->field($model, "ngay_giao_hang")->widget(DateTimePicker::classname(), [
                            'type' => DateTimePicker::TYPE_COMPONENT_PREPEND,
                            'options' => ['placeholder' => 'Ngày giao hàng', 'class' => 'dateInput'],
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format' => 'hh:ii dd-mm-yyyy',
                                'todayHighlight' => true,  
                            ]
                        ]) ?>
                    </div>
                                         
                </div>

                <div class="row">
                    <div class="col-sm-12 thong_bao text-right text-warning"></div>
                </div>
        
               
            </div>

        </div>
        <div id="" class="panel panel-success panel-item" style="pointer-events: auto;">
             <div class="panel-heading panel-heading-item">
                <h3 class="panel-title">Thông tin sản phẩm</h3>
                <span class="pull-right clickable panel-collapsed"><i class="glyphicon glyphicon-chevron-down"></i></span>
            </div>
            <div class="panel-body" style="display: none;">
                <div class="col-md-12 thong_tin_san_pham">  
                    <?= $ttsp?>                      
                </div>
            </div>                
        </div>

        <div id="" class="panel panel-success panel-item">
            <div class="panel-heading panel-heading-item">
                <h3 class="panel-title">Công đoạn sản xuất</h3>
                <span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
            </div>
            <div class="panel-body">
                <table class="table table-striped table-bordered table-hover cdsx">
                    <tr>
                        <th style="width: 100px;">Công đoạn</th>
                        <th>Nhân viên / Tên máy</th>
                        <!-- <th>Công đoạn</th> -->
                        <th>Thời gian bắt đầu</th>
                        <th>Thời gian hoàn thành</th>
                        <th style="white-space: nowrap; width: 100px;">Hoàn thành</th>
                    </tr>
                    <?php
                    $list_cdsx = [];                    

                    if(empty($Congdoans)) $Congdoans = [];
                    if(empty($Nhanviens)) $Nhanviens = [];
        
                    $definedCongDoan = Schedule::congDoanSanXuat();
                    if($model->isNewRecord) $Congdoans = $definedCongDoan;

                    if ($definedCongDoan) {
                        $i = -1;
                        foreach ($Congdoans as $index => $congDoan) {
                            $i++;
                            $selectedNv = isset($list_cdsx[$index]['user_id']) ? $list_cdsx[$index]['user_id'] : '';
                            ?>
                            <tr class="item-<?= $index?>">
                                <td rowspan="2" class="col-md-3" style="vertical-align: middle; font-weight: 700"><?= ($i + 1) .': '.  $definedCongDoan[$index]; ?></td>
                                <td rowspan="2">
                                    <?php
                                    echo Select2::widget([
                                        'name' => "Giaoviec[$index]",
                                        'data' => Schedule::getListNhanVien(),
                                        'value' => empty($Nhanviens[$index])?'':$Nhanviens[$index],
                                        'options' => [
                                            'placeholder' => 'Chọn nhân viên',
                                            'multiple' => true,
                                        ],
                                        'pluginOptions' => [
                                            'allowClear' => true,                                              
                                        ],
                                    ]);
                                    echo '<br>';
                                    echo Select2::widget([
                                        'name' => "Congdoan[$index][id_may_in]",
                                        'data' => Devices::getListDevices(),
                                        'value' => isset($congDoan['id_may_in']) ? $congDoan['id_may_in'] : '',
                                        'options' => [
                                            'placeholder' => 'Chọn máy in',
                                        ],
                                        'pluginOptions' => [
                                            'allowClear' => true,                                              
                                        ],
                                    ]);
                                    ?>
                                </td>
                                <!-- <td width="20%" style="vertical-align: middle"> -->
                                    <?php                                    
                                        // echo $definedCongDoan[$index];                                        
                                        // echo Html::dropDownList("Schedule[$i][title]", $index, $definedCongDoan, ['class' => 'form-control'])
                                        // echo Html::textInput("Congdoan[$index][title]", $index,['class' => 'form-control hide']);
                                    ?>
                                <!-- </td> -->
                                <td width="20%" style="vertical-align: middle">
                                    <?php
                                    echo DateTimePicker::widget([
                                        'name' => "Congdoan[$index][thoi_gian_bat_dau]",
                                        'id' => "schedule-$index-starttime",
                                        // 'type' => DateTimePicker::TYPE_COMPONENT_PREPEND,
                                        'options' => ['placeholder' => 'Thời gian bắt đầu'],
                                        'value' => !empty($congDoan['thoi_gian_bat_dau']) ? $congDoan['thoi_gian_bat_dau'] : '',
                                        'pluginOptions' => [
                                            // 'startDate' => date('d-m-Y H:i',time()),
                                            // 'endDate' => date('12-12-Y H:i',time()),
                                            'autoclose' => true,
                                            'format' => 'hh:ii dd-mm-yyyy'
                                        ],                                        
                                        // 'pluginEvents' => [                   
                                        //     'changeDate' => 'function(e) {    
                                        //     //     var starttime = $("#schedule-'.$index.'-starttime").val();
                                        //     //     console.log(starttime);
                                        //     //     $("#schedule-'.($index).'-endtime").datetimepicker("setStartDate", starttime)
                                        //     }'
                                        // ]
                                    ]);
                                    ?>
                                </td>
                                <td width="20%" style="vertical-align: middle">
                                    <?php
                                    echo DateTimePicker::widget([
                                        'name' => "Congdoan[$index][thoi_gian_hoan_thanh]",
                                        'id' => "schedule-$index-endtime",
                                        // 'type' => DateTimePicker::TYPE_COMPONENT_PREPEND,
                                        'options' => ['placeholder' => 'Thời gian hoàn thành'],
                                        'value' => !empty($congDoan['thoi_gian_hoan_thanh']) ? $congDoan['thoi_gian_hoan_thanh'] : '',
                                        'pluginOptions' => [
                                            // 'startDate' => date('d-m-Y H:i',time()),
                                            'autoclose' => true,
                                            'format' => 'hh:ii dd-mm-yyyy'
                                        ]
                                    ]);
                                    ?>
                                </td>
                                <td style="text-align: center; vertical-align: middle">
                                    <?php
                                    // echo $list_cdsx[$index]['finish'];
                                    // echo Html::checkbox("Schedule[$index][finish]", isset($list_cdsx[$index]['finish']) ? $list_cdsx[$index]['finish'] : '')

                                    echo '<input type="checkbox" name="Congdoan['.$index.'][trang_thai]" data-status="'.$index.'" value="'.( isset($congDoan['trang_thai'] )? $congDoan['trang_thai'] :'').'" '.((isset($congDoan['trang_thai']) && $congDoan['trang_thai'] == Kehoach::DAHOANTHANH) ? 'checked' : '').' >';
                                    ?>
                                </td>
                            </tr>
                            <tr class="item-<?= $index?>">
                                <td colspan="4" class="note-<?=$index?>">
                                    <?php
                                    $disabled = false;
                                    $user_id = Yii::$app->user->identity->getId();
                                    if (is_array($selectedNv) && !in_array($user_id, $selectedNv) && ($role->acl_desc != 'ALL_PRIVILEGES')) {
                                        $disabled = true;
                                    }
                                    echo Html::textarea("Congdoan[$index][ghi_chu]", isset($congDoan['ghi_chu']) ? $congDoan['ghi_chu'] : '', ['class' => 'form-control', 'placeholder' => 'Ghi chú...', 'disabled' => $disabled])
                                    ?>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </table>
            </div>
        </div>

        <div id="" class="panel panel-success panel-item">
            <div class="panel-heading panel-heading-item">
                <h3 class="panel-title">Giao hàng</h3>
                <span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
            </div>
            <div class="panel-body">
                <?php
                // $delivery = new PlanDelivery();
                if(!$model->isNewRecord)
                //     $delivery->items = $model->delivery;
                // echo $form->field($delivery, 'items')->widget(MultipleInput::className(), [
                //     'id' => 'delivery-list',
                //     'allowEmptyList'    => true,
                //     'columns' => [
                //         [
                //             'name' => 'plan_id',
                //             'title' => 'ID',
                //             'enableError' => true,
                //             'type' => MultipleInputColumn::TYPE_HIDDEN_INPUT,
                //             'value' => function($data) use ($model) {
                //                 return isset($data['plan_id']) ? $data['plan_id'] : (isset($model->plan_id) ? $model->plan_id : '');
                //             },
                //         ],
                //         [
                //             'name' => 'delivery_id',
                //             'title' => 'ID',
                //             'enableError' => true,
                //             'type' => MultipleInputColumn::TYPE_HIDDEN_INPUT,
                //         ],
                //         [
                //             'name'  => 'user_id',
                //             'title' => 'Nhân viên',
                //             'enableError' => true,
                //             'type' => MultipleInputColumn::TYPE_DROPDOWN,
                //             'value' => function($data) {
                //                 return isset($data['user_id']) ? $data['user_id'] : '';
                //             },
                //             'items' => Schedule::getListNhanVien()
                //         ],
                //         [
                //             'name'  => 'delivery_date',
                //             'title' => 'Ngày giao',
                //             'enableError' => true,
                //             'type'  => \kartik\datetime\DateTimePicker::className(),
                //             'value' => function($data) {
                //                 return isset($data['delivery_date']) ? $data['delivery_date'] : '';
                //             },
                //             'options' => [
                //                 'pluginOptions' => [
                //                     'format' => 'yyyy-mm-dd hh:ii',
                //                     'todayHighlight' => true
                //                 ]
                //             ]
                //         ],
                //         [
                //             'name'  => 'amount',
                //             'title' => 'Số lượng',
                //             'enableError' => true,
                //             'value' => function($data) {
                //                 return isset($data['amount']) ? $data['amount'] : '';
                //             },
                //             'options' => [
                //                     'class' => 'numberOnly'
                //             ]
                //         ],
                //     ],
                // ])
                //     ->label(false);

                ?>
            </div>
        </div>

        <div class="fix-bottom text-right">
            <div class="col-md-12">
                <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn-sm btn btn-success' : 'btn-sm btn btn-primary']) ?>
                <a href="/acp/kehoach" class="btn btn-default btn-sm" style="margin: 0 0 0 10px;">Hủy</a>
            </div>

            <div class="clearfix"></div>
        </div>
        <?php ActiveForm::end(); ?>

    </div>

    <style>
        .glyphicon-chevron-up {
            /*display: none;*/
        }

        .form-group {
            clear: both;
        }

        .group_sp .btn, .group_phan_xuong_in .btn, .group_phan_xuong_thanh_pham .btn {
            margin-bottom: 10px
        }

        .item_sp input, .item_sp select, .item_in input, .item_in select, .item_in textarea, .item_pxtp input, .item_pxtp select, .item_pxtp textarea {
            margin: 10px 0;
        }
    </style>
<?php

$link_get_contract = Url::toRoute('get-order');
$link_get_order = Url::toRoute('get-order-product');
$js = <<< XP
    var doc = $(document)
    doc.on('click', '.panel-heading-item span.clickable, .panel-heading-item h3.panel-title', function () {
        var current = $(this).closest('.panel-heading-item').find('span.clickable');
        if (!current.hasClass('panel-collapsed')) {
            current.parents('.panel-item').find('.panel-body').slideUp();
            current.addClass('panel-collapsed');
            current.find('i').removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
        } else {
            current.parents('.panel-item').find('.panel-body').slideDown();
            current.removeClass('panel-collapsed');
            current.find('i').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
        }
    });



    $('input[name*="Congdoan"][type="checkbox"]').on('change', function(i, v){        
        var current = $(this).attr('data-status'),
            length = $('input[name*="Congdoan"][type="checkbox"]').length,
            checked_length, status;

        // alert(current)

        if($(this).is(':checked')){
            $('input[name*="Congdoan"][type="checkbox"]').each(function(i){  
                // alert($(this).val()+current+'A')
                if($(this).attr('data-status') <= current)
                    $(this).prop('checked', true);
            });
        }else{
            $('input[name*="Congdoan"][type="checkbox"]').each(function(i){                
                // alert($(this).val()+current+'B')
                if($(this).attr('data-status') > current)
                    $(this).prop('checked', false);
            });
        }
        checked_length = $('input[name*="Congdoan"][type="checkbox"]:checked').length;

        // if(checked_length === length){
        //     status = '';
        // }else if(checked_length === 0){
        //     status = 'NOTHING';
        // }else{
        //     status = $('input[name*="Congdoan"][type="checkbox"]').eq(checked_length).attr('data-status');
        // }
        // $('#plan-status').val(status);
    });
    
    $('#kehoach-id_don_hang').change(function () {        
        $.ajax({
            'type': 'get',
            'url': '{$link_get_contract}',
            'data': {'id': $(this).val()},
            success: function (data) {
                if(data){
                    var result = $.parseJSON(data);
                    // $("#kehoach-kho_san_pham").val(result.kho_san_pham);
                    // $("#kehoach-ten_san_pham").val(result.ten_san_pham);

                    $("#kehoach-ngay_giao_hang").val(result.thoi_gian_giao_hang);

                    // $("#kehoach-so_trang").val(result.so_trang);
                    // $("#kehoach-loai_giay").val(result.loai_giay);
                    $("#kehoach-id_san_pham").html(result.san_pham);
                    $('#kehoach-id_san_pham').trigger('change');
                }
            }
        });
    });
    
    $('#kehoach-id_san_pham').change(function () {
        if($(this).val() > 0){
            var ghep = $(this).find('option:selected').data('ghep'),
                ngay_san_xuat = $('#kehoach-ngay_san_xuat').val();
            $("#kehoach-san_pham_ghep").val(ghep);
            $.ajax({
                'type': 'get',
                'url': '{$link_get_order}',
                'data': {'id': $(this).val(), 'ghep': ghep, 'date':ngay_san_xuat},
                success: function (data) {
                    if(data){
                        var result = $.parseJSON(data);                        
                        $(".thong_bao").html(result.thong_bao);
                        $(".thong_tin_san_pham").html(result.ttsp);
                    }
                }
            });
        }else{
            $(".thong_bao").html('');
            $(".thong_tin_san_pham").html('');
        }
    });
    
    $('#plan-ngay_san_xuat').change(function () {
        var ghep = $('#plan-info_id').find('option:selected').data('ghep'),
                ngay_san_xuat = $('#plan-ngay_san_xuat').val(),
                don_hang = $('#plan-info_id').val();
        if($(this).val() && don_hang > 0){
            $("#plan-san_pham_ghep").val(ghep);
            $.ajax({
                'type': 'get',
                'url': '{$link_get_order}',
                'data': {'id': don_hang, 'ghep': ghep, 'date':ngay_san_xuat},
                success: function (data) {
                    if(data){
                        var result = $.parseJSON(data);
                        $(".thong_bao").html(result.thong_bao);
                    }
                }
            });
        }
    });
    
    $(".add_sp").click(function(){    
        var rootFrame = $(this).closest('.group_sp');
        first_tbody = rootFrame.find('.item_sp:first'),
            html = first_tbody.clone(),
            count = $('.item_sp').length;
                  
            var select = html.find('select[name*="[order_info_id]"]');
            select.parent().find('span').remove();
            var select_attributes = $.map(select.get(0).attributes, function(item) {
                return item.name.toString().toLowerCase();
            });
            $.each(select_attributes, function(index, item) {
                if (item == 'name' || item == 'id') {} else {
                    select.removeAttr(item);
                }
    
            });      
            
            var select_clone_name = select.attr('name');
            var select_clone_id = select.attr('id');
            console.log(select_clone_name);
            select.attr('name', select_clone_name.replace('[0]', '[' + (count) + ']'));
            select.attr('id', select_clone_id.replace('-0-', '-' + (count) + '-'));
            select.val('');
            select.select2({
                allowClear: true,
                language: "vi",
                placeholder: "Chọn đơn hàng",
                theme: "krajee",
                width: "100%"
            });
            
            html.find('select, input').each(function () {               
                this.name = this.name.replace(/([0-9]+)/g, count);               
            });        
            html.find('.add_sp').removeClass('add_sp').addClass('remove_sp');
            html.find('.glyphicon-plus').removeClass('glyphicon-plus').addClass('glyphicon-minus');
            html.find('.btn-success').removeClass('btn-success').addClass('btn-danger');
            html.find('select, input').val('');  
            html.find('select[name*="[order_info_id]"]').trigger('change');      
            $(html).insertAfter(rootFrame.find('.item_sp:last'));           
    });
     
    $(document).on('click','.remove_sp', function(){
         $(this).closest('.item_sp').remove();
     });
     
      $(".add_px_in").click(function(){    
        var rootFrame = $(this).closest('.group_phan_xuong_in');
        first_tbody = rootFrame.find('.item_in:first'),
            html = first_tbody.clone(),
            count = $('.item_in').length;
            html.find('select, input, textarea').each(function () {               
                this.name = this.name.replace(/([0-9]+)/g, count);               
            });     
            
            var input = html.find('input[name*="[thoi_gian_hoan_thanh]"]');
            input.datetimepicker({
                autoclose: true,
                format: 'dd-mm-yyyy hh:ii'
            });
                  
            html.find('.add_px_in').removeClass('add_px_in').addClass('remove_px_in');
            html.find('.glyphicon-plus').removeClass('glyphicon-plus').addClass('glyphicon-minus');
            html.find('.btn-success').removeClass('btn-success').addClass('btn-danger');
            html.find('select, input, textarea').val('');  
            $(html).insertAfter(rootFrame.find('.item_in:last'));           
    });   
   
     
    $(document).on('click','.remove_px_in', function(){
         $(this).closest('.item_in').remove();
     });
     
     $(document).on('click','.remove_sp', function(){
         $(this).closest('.item_sp').remove();
     });
     
      $(".add_pxtp").click(function(){    
        var rootFrame = $(this).closest('.group_phan_xuong_thanh_pham');
        first_tbody = rootFrame.find('.item_pxtp:first'),
            html = first_tbody.clone(),
            count = $('.item_pxtp').length;
            html.find('select, input, textarea').each(function () {               
                this.name = this.name.replace(/([0-9]+)/g, count);               
            });       
            var input = html.find('input[name*="[thoi_gian_hoan_thanh]"]');
            input.datetimepicker({
                    autoclose: true,
                    format: 'dd-mm-yyyy hh:ii'
                });
            
            html.find('.add_pxtp').removeClass('add_px_in').addClass('remove_pxtp');
            html.find('.glyphicon-plus').removeClass('glyphicon-plus').addClass('glyphicon-minus');
            html.find('.btn-success').removeClass('btn-success').addClass('btn-danger');
            html.find('select, input, textarea').val('');  
            $(html).insertAfter(rootFrame.find('.item_pxtp:last'));           
    });   
   
     
    $(document).on('click','.remove_pxtp', function(){
         $(this).closest('.item_pxtp').remove();
     });
    
XP;
$this->registerJs($js);
?>