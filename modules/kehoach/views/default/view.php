<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use yii\helpers\Url;
use kartik\widgets\DatePicker;
use kartik\select2\Select2;
use backend\models\Content;

use backend\modules\kehoach\models\Kehoach;

// use backend\models\Plan;
use backend\models\Role;
use backend\models\Schedule;
use backend\models\Devices;
// use backend\models\PlanDelivery;
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
    }
    // else{
    //     Yii::$app->response->redirect(['index']);
    // }
}
?>

<div class="plan-form">
    <h1>Xem chi tiết kế hoạch sản xuất</h1>
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
        $form = ActiveForm::begin(['enableAjaxValidation' => true]);

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
                        <?php // $form->field($model, "san_pham_ghep", ['options' => ['tag' => false]])->hiddenInput()->label(false) ?>
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
    
    
                <?php if(1 > 2){ //Ẩn phần này theo task #25083 ?>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-3">
                            <?php //echo $form->field($model, "ten_san_pham")->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-sm-3">
                            <?php //echo $form->field($model, "so_trang")->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-sm-3">
                            <?php //echo $form->field($model, "kho_san_pham")->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-sm-3">
                            <?php //echo $form->field($model, "loai_giay")->dropDownList(Content::getListOptionsByType(Content::TYPE_CHAT_LIEU_GIAY)) ?>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>

        </div>
        <div id="" class="panel panel-success panel-item" style="pointer-events: auto;">
             <div class="panel-heading panel-heading-item">
                <h3 class="panel-title">Thông tin sản xuất</h3>
                <span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
            </div>
            <div class="panel-body"">
                <div class="col-md-12 thong_tin_san_pham">  
                    <?= $ttsp?>                      
                </div>
            </div>                
        </div>

      

        <div class="fix-bottom text-right">
            <div class="col-md-12">                
                <button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Đóng</button>
            </div>
            <div class="clearfix"></div>
        </div>
        <?php ActiveForm::end(); ?>

    </div>

    <style>
        .glyphicon-chevron-up {
            display: none;
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
$link_get_to_in = Url::toRoute('/plan/get-toin');
$link_get_contract = Url::toRoute('/plan/get-order');
$link_get_order = Url::toRoute('/plan/get-order-product');
$js = <<< XP
   


    $('input[name*="Schedule"][type="checkbox"]').on('change', function(i, v){        
        var current = $(this).attr('data-status'),
            length = $('input[name*="Schedule"][type="checkbox"]').length,
            checked_length, status;

        // alert(current)

        if($(this).is(':checked')){
            $('input[name*="Schedule"][type="checkbox"]').each(function(i){  
                // alert($(this).val()+current+'A')
                if($(this).attr('data-status') <= current)
                    $(this).prop('checked', true);
            });
        }else{
            $('input[name*="Schedule"][type="checkbox"]').each(function(i){                
                // alert($(this).val()+current+'B')
                if($(this).attr('data-status') > current)
                    $(this).prop('checked', false);
            });
        }
        checked_length = $('input[name*="Schedule"][type="checkbox"]:checked').length;

        if(checked_length === length){
            status = 'OK';
        }else if(checked_length === 0){
            status = 'NOTHING';
        }else{
            status = $('input[name*="Schedule"][type="checkbox"]').eq(checked_length).attr('data-status');
        }
        $('#plan-status').val(status);
    });
    
    $('#plan-order_info_id').change(function () {
        $.ajax({
            'type': 'get',
            'url': '{$link_get_contract}',
            'data': {'id': $(this).val()},
            success: function (data) {
                if(data){
                    var result = $.parseJSON(data);
                    $("#plan-kho_san_pham").val(result.kho_san_pham);
                    $("#plan-ten_san_pham").val(result.ten_san_pham);

                    $("#plan-ngay_giao_hang").val(result.thoi_gian_giao_hang);

                    $("#plan-so_trang").val(result.so_trang);
                    $("#plan-loai_giay").val(result.loai_giay);
                    $("#plan-info_id").html(result.san_pham);
                    $('#plan-info_id').trigger('change');
                }
            }
        });
    });
    
    $('#plan-info_id').change(function () {
        if($(this).val() > 0){
            var ghep = $(this).find('option:selected').data('ghep'),
                ngay_san_xuat = $('#plan-ngay_san_xuat').val();
            $("#plan-san_pham_ghep").val(ghep);
            $.ajax({
                'type': 'get',
                'url': '{$link_get_order}',
                'data': {'id': $(this).val(), 'ghep': ghep, 'date':ngay_san_xuat},
                success: function (data) {
                    if(data){
                        var result = $.parseJSON(data);
                        // $("#plan-kho_san_pham").val(result.kho_san_pham);
                        // $("#plan-ten_san_pham").val(result.ten_san_pham);
                        // $("#plan-so_trang").val(result.so_trang);
                        // $("#plan-loai_giay").val(result.loai_giay);
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