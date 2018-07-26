<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\widgets\Select2;
use kartik\time\TimePicker;
use kartik\widgets\DatePicker;
use kartik\builder\Form;
use kartik\builder\FormGrid;
use yii\helpers\Url;
use kartik\dialog\Dialog;


/* @var $this yii\web\View */
/* @var $model backend\models\Process */
/* @var $form yii\widgets\ActiveForm */
?>

    <div class="export_process-form" style="text-align: center">
        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'id' => 'export-process-form-']]); ?>
        <h1 class="title_">Xuất dữ liệu</h1>

        <div class="row" style="    width: 100%; margin: 0 auto;">
            <div class="form-group">

                <div class="col-sm-12">

                    <?php
                    echo DatePicker::widget([
                        'name' => 'tu_ngay',
                        'options' => ['placeholder' => 'Từ ngày', 'class' => 'form-control'],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd',
                        ]
                    ]);
                    ?></div>
                <div class="col-sm-12" style="margin-top: 10px">
                    <?php
                    echo DatePicker::widget([
                        'name' => 'den_ngay',
                        'options' => ['placeholder' => 'Đến ngày', 'class' => 'form-control'],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd',
                        ]
                    ]);
                    ?>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="form-group">

                <div class="col-sm-12" style="text-align:  right">
                    <p style="text-align: left;font-weight: bold; margin-bottom: 40px">Bạn vui lòng chọn khoảng thời gian chính xác để xuất báo cáo đầy đủ.</p>
                    <?= Html::button('Xuất báo cáo', ['class' => 'btn btn-success btn-export']) ?>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>

        <?php ActiveForm::end(); ?>

    </div>
    <style>
        form {
            width: 100%;
            margin-top: 20px;
        }

        form select {
            margin-top: 10px
        }

        form .btn-export {
            margin-top: 20px
        }

        .datepicker table tr td, .datepicker table tr th {
            font-size: 11px;
            width: 15px;
            height: 15px;
        }

        h1.title_ {
            background: #398439;
            color: #fff;
            text-align: center;
            width: 93%;
            margin: 10px auto;
            text-transform: uppercase;
            font-size: 24px;
            padding: 10px 0;
        }
    </style>
<?php
$link_export_process = Url::toRoute('plan/export-ajax');
$link_download = Url::toRoute('plan/download');
$js = <<<XP
    
       $(document).on('click', ".btn-export", function () {    
            var form = $('#export-process-form-')[0];
            var formData = new FormData(form); 
            var tu_ngay = $('input[name*="tu_ngay"]').val();
            var den_ngay = $('input[name*="den_ngay"]').val();
            if( tu_ngay == '' && den_ngay == '')
                ngay_thuc_hien = '';
            else
                ngay_thuc_hien = 'true';
            if(tu_ngay == '' || den_ngay == undefined || den_ngay == '' || tu_ngay == undefined){
                krajeeDialog.alert('Vui lòng chọn thời gian để xuất báo cáo');    
            }else{         
                $.ajax({
                    'url': '{$link_export_process}',     
                    type: 'post',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (html) {     
                        if(html == 'error'){
                            krajeeDialog.alert('Không có kế hoạch sản xuất nào được thực hiện trong ngày này.');  
                        }else{                        
                            window.open('{$link_download}');
                             parent.$.fancybox.close();
                        }
                    }
                });
            }
        });   
XP;
$this->registerJs($js);
?>