<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\date\DatePicker;
use backend\helpers\AcpHelper;
use backend\models\Schedule;
use backend\models\Plan;
use backend\models\Role;
use backend\models\User;
use backend\models\PlanApply;

use backend\modules\kehoach\models\Kehoach;
use backend\modules\kehoach\models\KehoachCongdoan;
use backend\modules\kehoach\models\KehoachGiaoviec;

date_default_timezone_set('asia/ho_chi_minh');
/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$role = Role::find()->where(['role_id' => Yii::$app->user->identity->role_id])->one();

if(empty($_GET['tinhtrang'])){
    $tinhtrang = 'dang_lam';
}else{
    $tinhtrang = $_GET['tinhtrang'];
}

if(!empty($so_chuanhan)){
    $so_chuanhan = '<span class="solg">'.$so_chuanhan.'</span>';
}else{
    $so_chuanhan = '';
}
if(!empty($so_danglam)){
    $so_danglam = '<span class="solg">'.$so_danglam.'</span>';
}else{
    $so_danglam = '';
}


$list_loc = [
    'chua_nhan' => 'Công việc chưa nhận',
    'dang_lam' => 'Công việc đang làm',
    'da_hoan_thanh' => 'Công việc đã hoàn thành',
];


$this->title = 'Danh sách '.$list_loc[$tinhtrang];



$this->params['breadcrumbs'][] = $this->title;
$listCongDoanSX = Schedule::congDoanSanXuat();
$settings = Yii::$app->settings;


?>
<!-- <div class="plan-index" style="width: 100%;"> -->
<div class="plan-index" style="width: 100%; overflow-x: auto; max-width: 100%">

<script type="text/javascript">
    function lydotralai() {    
        var a = prompt("Lý do bạn muốn TRẢ LẠI?", "");
        if (a == null) {        
            return 'false';
        } else {
            while(a == ""){
                a = lydotralai();
            }        
            return a;        
        }    
    }
    function ghichucongdoan(){
        var a = prompt("Ghi chú cho công đoạn này?", "");
        if (a == null) {        
            return 'false';
        } else {
            while(a == ""){
                a = ghichucongdoan();
            }        
            return a;        
        } 
    }

</script>

<style type="text/css">

    td a span.btn {
        width: 22px;
    }

    .panel.panel-info{
        /*margin-bottom: 200px;*/
    }

    .btn-xs, .btn-group-xs > .btn {
        padding: 1px 3px;
        font-size: 13px;
        font-weight: bold;
    }

    .solg {
        background: #F00;
        padding: 3px 3px 0 3px;
        font-size: 10px;
        position: absolute;
        top: -11px;
        right: 3px;
        box-shadow: 0 0 3px 0px #6f6868;
        color: #FFF;
    }


    table.kv-grid-table>tbody>tr>td{
        height: 100px;
    }


    .cd-ht {
        border: 3px solid #00a65a;
    }
    .panel-info>.panel-heading{
        margin: 10px 0 0 10px;
        float: left;
        /*display: none;*/
    }
    th>a {
        display: flex;
    }
    td.item-user {
        font-size: 12px;
    }
    .item-cd>div {
        color: #333;
        width: max-content;
        min-width: 260px;
        display: none;
        background: #FFF;
        position: absolute;
        text-align: left;
        padding: 5px 10px;
        top: calc(100% + 5px);
        right: 0;
        z-index: 9;
        border: 1px solid #ccc;
        box-shadow: 0 0 2px 0px #ccc;
    }
    .item-cd:hover >div {
        display: block;
    }
    .bg-green.item-cd,
    .cd-ht.item-cd {
        display: block;
    }

    .item-cd {
        display: none;
        background: #e4e4e4;
        min-width: 40px;
        height: 40px;
        float: left;
        margin: 0;
        position: relative;
        border-radius: 40px;
    }
    .item-cd div {
        line-height: normal;
    }
    .checkboxcolumn {
        width: 20px !important;
    }
    .cd-ht .tiendo {
        padding: 5px 0 0 0;
    }
    .tiendo{
        padding: 8px 0 0 0;
        font-size: 25px;
    }
    .gach-ngang{
                float: left;
    width: 92px;
    height: 3px;
    background: #e4e4e4;
    display: block;
    padding: 0;
    margin: 18px 0 0 0;
    }
    .item-cd .tencd{
        clear: both;
    position: absolute;
    color: #484848;    
    bottom: -25px;
    width: 200px;
    text-align: center;
    left: -85px;
    }
    .gach-ngang:last-child{
    display: none;  
}

</style>


    <?php
    
    if(!empty($role) && $role->acl_desc != 'ALL_PRIVILEGES') {
        if (!empty($role) && !empty($role->allow_cdsx)) {
            $cdsx = json_decode($role->allow_cdsx);
        }
    }

    $gridColumns =   [];


    $gridColumns[] = [
        'class' => 'kartik\grid\CheckboxColumn',
        'visible' => ($tinhtrang != 'da_hoan_thanh'),
        'headerOptions' => ['class' => 'checkboxcolumn'],
        'contentOptions' => ['class' => 'checkboxcolumn'],
    ];


    $gridColumns[] = 
        [
            'header' => ($tinhtrang == 'chua_nhan' ? Html::a('<span class="btn btn-success btn-xs bg-green" style=""><i class="fa fa-check-circle" aria-hidden="true"></i> Nhận tất cả</span>', 'javascript:;', [
                            'title' => 'Nhận lệnh',
                            'class' => 'apply',
                            'data-method' => 'post',
                            'data-pjax' => '0',
                            'onclick' => "
                                var r = confirm(\"Bạn có chắc muốn nhận tất cả các công đoạn này?\");
                                if (r == true) {

                                    var parent = $('div.grid-view')
                                    var select = parent.yiiGridView('getSelectedRows')

                                    $.ajax('".Url::toRoute(['apply-multi'])."', {
                                        type: 'POST',
                                        data: {
                                            all: select,
                                        }
                                    }).done(function(data) {
                                        if(data.status == 1){
                                            unloading();
                                            $('#kehoach-lists').html(data.element)                                            
                                        }else {
                                            alert('Có lỗi xảy ra trong quá trình xử lý. Vui lòng thử lại sau');
                                        }
                                    });
                                } 
                            "
                        ]) :
                //Hoàn thành tất cả, trả lại tất cả
                ($tinhtrang == 'dang_lam'?Html::a('<span class="btn btn-success btn-xs bg-green" style=""><i class="fa fa-check-circle" aria-hidden="true"></i> Hoàn thành</span>', 'javascript:;', [
                            'title' => 'Hoàn thành',
                            'class' => '',
                            'data-method' => 'post',
                            'data-pjax' => '0',
                            'onclick' => "
                                var r = confirm(\"Bạn có chắc đã hoàn thành các công đoạn này?\");
                                if (r == true) {
                                    var parent = $('div.grid-view')
                                    var select = parent.yiiGridView('getSelectedRows')
                                    $.ajax('".Url::toRoute(['finish-multi'])."', {
                                        type: 'POST',
                                        data: {
                                            all: select,
                                        }
                                    }).done(function(data) {
                                        if(data.status == 1){
                                            unloading();
                                            $('#kehoach-lists').html(data.element)                                            
                                        }else {
                                            alert('Có lỗi xảy ra trong quá trình xử lý. Vui lòng thử lại sau');
                                        }
                                    });
                                } 
                            "
                        ]) . Html::a('<span class="btn btn-danger btn-xs" style="margin: 5px 0 0 0"><i class="fa fa-check-circle" aria-hidden="true"></i> Trả lại</span>', 'javascript:;', [
                            'title' => 'Trả lại',
                            'class' => '',
                            'data-method' => 'post',
                            'data-pjax' => '0',
                            'onclick' => "
                                var r = confirm(\"Bạn có chắc trả lại các công đoạn này?\");
                                if (r == true) {
                                    lydo = lydotralai();
                                    if(lydo != 'false'){
                                        var parent = $('div.grid-view')
                                        var select = parent.yiiGridView('getSelectedRows')
                                        $.ajax('".Url::toRoute(['callback-multi'])."', {
                                            type: 'POST',
                                            data: {
                                                all: select,
                                                lydo: lydo,
                                            }
                                        }).done(function(data) {
                                            if(data.status == 1){
                                                unloading();
                                                $('#kehoach-lists').html(data.element)                                            
                                            }else {
                                                alert('Có lỗi xảy ra trong quá trình xử lý. Vui lòng thử lại sau');
                                            }
                                        });
                                    }
                                } 

                            "
                        ]):'')
                    ),
            'class' => 'common\components\xPActionColumn',
            'dropdown' => false,
            'vAlign' => 'middle',
            'template' => '{comment} {view} {apply}',
            'width' => '80px',
            // 'options' => ['style' => 'width: 50px'],
            // 'format' => 'raw', 
            // 'filterType' => GridView::FILTER_SELECT2,
            // 'filterWidgetOptions' => [
            //     'options' => ['placeholder'=>'-- Chọn --'],
            //     'pluginOptions' => ['allowClear' => true],
            // ],            
            // 'filter'=> $listCongDoanSX,


            'buttons' => [
                'comment' => function ($url, $model) use($tinhtrang) {
                    $url_ghichu = Url::toRoute(['ghi-chu']);
                    return Html::a('<span class="btn btn-success btn-xs" style="margin: 1px 0 0 0;"><i class="fa fa-comment" aria-hidden="true"></i></span>', 'javascript:;', [
                            'title' => 'Ghi chú',
                            'class' => 'ghichu',
                            'data-method' => 'post',
                            'data-pjax' => '0',
                            'onclick' => "                                
                                ghichu = ghichucongdoan();
                                if(ghichu != 'false'){
                                    $.ajax('$url_ghichu', {
                                        type: 'POST',                                            
                                        data: {
                                            kh:".$model->id_ke_hoach.",
                                            cd:".$model->id_cong_doan.",
                                            gc: ghichu,
                                            tt: '".$tinhtrang."',
                                        },
                                    }).done(function(data) {
                                        if(data.status == 1){
                                            unloading();
                                            $('#kehoach-lists').html(data.element)                                            
                                        }else {
                                            alert('Có lỗi xảy ra trong quá trình xử lý. Vui lòng thử lại sau');
                                        }
                                    });
                                }
                                 
                            "
                        ]);
                },


                'view' => function ($url, $model) {
                    return Html::a('<span class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-search"></i></span>','javascript:;', [
                        'title' => 'Xem',
                        'class' => 'view',
                        'onclick' => "openmodal('".Url::toRoute(['view'])."?id=".$model->id_ke_hoach."');return false;",
                    ]);
                },
                'apply' => function ($url, $model) {

                    if(in_array($model->trang_thai,[KehoachCongdoan::DANGLAM,KehoachCongdoan::SUALOI])){
                        $url = Url::toRoute(['finish']);
                        $return = Html::a('<span class="btn btn-success btn-xs" style="margin: 1px 4px 0 0;"><i class="fa fa-check-square-o" aria-hidden="true"></i></span>', 'javascript:;', [
                            'title' => 'Hoàn thành',
                            'class' => 'finish',
                            'data-method' => 'post',
                            'data-pjax' => '0',
                            'onclick' => "
                                var r = confirm(\"Xác nhận công đoạn này đã hoàn thành?\");
                                if (r == true) {
                                    $.ajax('$url', {
                                        type: 'POST',
                                        data: {
                                            kh:".$model->id_ke_hoach.",
                                            cd:".$model->id_cong_doan.",
                                        }
                                    }).done(function(data) {
                                        if(data.status == 1){
                                            unloading();
                                            $('#kehoach-lists').html(data.element)
                                        }else {
                                            alert('Có lỗi xảy ra trong quá trình xử lý. Vui lòng thử lại sau');
                                        }
                                    });
                                } 
                            "
                        ]);

                        $url_callback = Url::toRoute(['callback']);
                        $return .= Html::a('<span class="btn btn-danger btn-xs" style="margin: 1px 0 0 0;"><i class="fa fa-undo" aria-hidden="true"></i></span>', 'javascript:;', [
                            'title' => 'Trả lại',
                            'class' => 'apply',
                            'data-method' => 'post',
                            'data-pjax' => '0',
                            'onclick' => "
                                var r = confirm(\"Bạn có chắc muốn trả lại công đoạn này?\");
                                if (r == true) {
                                    lydo = lydotralai();
                                    if(lydo != 'false'){
                                        $.ajax('$url_callback', {
                                            type: 'POST',                                            
                                            data: {
                                                kh:".$model->id_ke_hoach.",
                                                cd:".$model->id_cong_doan.",
                                                lydo: lydo,
                                            },
                                        }).done(function(data) {
                                            if(data.status == 1){
                                                unloading();
                                                $('#kehoach-lists').html(data.element)                                            
                                            }else {
                                                alert('Có lỗi xảy ra trong quá trình xử lý. Vui lòng thử lại sau');
                                            }
                                        });
                                    }
                                } 
                            "
                        ]);
                        if($model->trang_thai == 'OK'){
                            return '';
                        }else{
                            return $return;
                        }
                    }

                    if($model->trang_thai == KehoachCongdoan::CHUANHAN){
                        $url = Url::toRoute(['apply']);
                        $return = Html::a('<span class="btn btn-success btn-xs" style="margin: 1px 1px 0 0;"><i class="fa fa-check-circle" aria-hidden="true"></i></span>', 'javascript:;', [
                            'title' => 'Nhận lệnh',
                            'class' => 'apply',
                            'data-method' => 'post',
                            'data-pjax' => '0',
                            'onclick' => "
                                var r = confirm(\"Bạn có chắc muốn nhận công đoạn này?\");
                                if (r == true) {
                                    $.ajax('$url', {
                                        type: 'POST',
                                        data: {
                                            kh:".$model->id_ke_hoach.",
                                            cd:".$model->id_cong_doan.",
                                        }
                                    }).done(function(data) {
                                        if(data.status == 1){
                                            unloading();
                                            $('#kehoach-lists').html(data.element)                                            
                                        }else {
                                            alert('Có lỗi xảy ra trong quá trình xử lý. Vui lòng thử lại sau');
                                        }
                                    });
                                } 
                            "
                        ]);                       
                        return $return;
                    }
                }
            ],
        ];


    $gridColumns[] = [
            'attribute' => 'makehoach',
            'width' => '120px',
            'vAlign' => 'middle',
            'format'=>'raw',
            'contentOptions' => function($model) use ($tinhtrang) {                
                $class = [];                
                if(empty($model->thoi_gian_bat_dau)){
                    $class['class'] = '';
                }else{
                    if($tinhtrang != 'da_hoan_thanh'){
                        $time_batdau = strtotime($model->thoi_gian_bat_dau);                        
                        if(time() > $time_batdau){
                            $class['class'] = 'tt_quahan';
                        }elseif(time() < ($time_batdau - (30 * 60))){
                            $class['class'] = 'tt_truochan';
                        }else{
                            $class['class'] = 'tt_tronghan';
                        }                        
                    }
                }                
                return $class;
            },            
            'value' => function ($model) {
                return $model->kehoach->code;
            },
        ];

    

    $gridColumns[] = [
            'attribute' => 'iddonhang',
            'width' => '120px',
            'vAlign' => 'middle',
            'format'=>'raw',            
            'headerOptions' => ['class' => ''],
            'value' => function ($model) {
                if(!empty($model->kehoach->id_don_hang)){
                    $return = '';  
                    if($model->kehoach->donhang->KieuIn == 'offset'){
                        $return .=  Html::a($model->kehoach->donhang->info->infoCode, Url::toRoute(['orders/view', 'id' => $model->kehoach->donhang->order_id]), [
                            'title' => 'Xem',
                            'class' => 'view',
                            'target' => '_blank',
                        ]).'<br/>';
                    }else{
                        $return .=  Html::a($model->kehoach->donhang->info->infoCode, Url::toRoute(['kehoach/update', 'id' => $model->kehoach->donhang->order_id]), [
                            'title' => 'Xem',
                            'class' => 'view',
                            'target' => '_blank',
                        ]).'<br/>';
                    }

                    if(!empty($model->muc_do)) $return .= '<span class="btn btn-default '.Kehoach::getMucdouutienColor($model->muc_do).' btn-xs">'.Kehoach::getMucdouutienLabel($model->muc_do).'</span>';
                    return $return ;
                }else{
                    return '';
                }
            },
        ];


        $gridColumns[] =     [
            'attribute' => 'idsanpham',
            'format' => 'html',
            'vAlign' => 'middle',

            'value' => function ($model) {
                return $model->kehoach->tensanpham;

                $_sanpham = $model->kehoach->sanpham;
              
                $return = !empty($_sanpham->product_name) ? '<strong>'.$_sanpham->product_name.'</strong>' : '';

                $return .= !empty($_sanpham->product->title) ? (!empty($return) ? ' - ' : '').'<strong>'.$_sanpham->product->title.'</strong>' : '';

                $return .= (!empty($return) ? '<br>' : '').'Khổ '.$_sanpham->length .' x '.$_sanpham->width;
                $return .= !empty($return) && !empty($_sanpham->inner_page_amount) ? '<br>' . $_sanpham->inner_page_amount .' trang '. Kehoach::getLoaiGiay($_sanpham->paper->GiayBiaChatLieu) : '';
                return $return;
            },
            'contentOptions' => ['style' => 'width:200px;'],            
        ];


        $gridColumns[] =     [            
            'attribute' => 'id_cong_doan',            
            'format'=>'raw',
            'width' => '200px',
            'vAlign' => 'middle',            
            'hAlign' => '', 

            'filterType' => GridView::FILTER_SELECT2,
            'filterWidgetOptions' => [
                'options' => ['placeholder'=>'-- Chọn --'],
                'pluginOptions' => ['allowClear' => true],
            ],            
            'filter'=> Kehoach::getCongdoanOption(),


            'value' => function ($model) {                              
                return Kehoach::getCongdoanLabel($model->id_cong_doan);
            },
        ];


        $gridColumns[] =     [
            'attribute' => 'nhanviengiao',
            'visible' => (!empty($_GET['tinhtrang'])) && ($_GET['tinhtrang'] == 'chua_nhan'),
            'format' => 'html',
            'vAlign' => 'middle',

            // 'filterType' => GridView::FILTER_SELECT2,
            // 'filterWidgetOptions' => [
            //     'options' => ['placeholder'=>'-- Chọn --'],
            //     'pluginOptions' => ['allowClear' => true],
            // ],            
            // 'filter'=> User::getAllStaff(),

            'value' => function ($model) {
                $_giaoviec = $model->giaoviec;
                $return = '';
                if(is_array($_giaoviec)) foreach ($_giaoviec as $k => $v) {
                    $return .= '<div>'.$v->tennhanvien.'</div>';
                }
                return $return;
            },
            'contentOptions' => ['style' => 'width:200px;'],            
        ];


    $gridColumns[] =     [            
            'attribute' => 'thoi_gian_bat_dau',
            'label' => 'Dự kiến bắt đầu',
            'filterType' => GridView::FILTER_DATE_RANGE,
            'visible' => ($tinhtrang == 'chua_nhan'),
            'format'=>'raw',
            'width' => '130px',
            'vAlign' => 'middle',
            'filterWidgetOptions' => [
                'convertFormat'=>true,
                'pluginOptions'=>[                    
                    'opens'=>'left',
                    'locale'=>['format' => 'Y-m-d'],
                ],
                'pluginEvents'=>[
                    'cancel.daterangepicker'=>"function(ev, picker){}"
                ],
                'options' => ['style' => 'font-size: 12px'],
            ],
            'hAlign' => 'center',
            'filterOptions'=>[
                'style' => 'position: relative; width: 120px;',
            ],            
            'value' => function ($model) {  
                return $model->thoi_gian_bat_dau;
            },
        ];


    

    $gridColumns[] =     [            
            'attribute' => 'thoigiannhan',
            'header' => 'Thời gian nhận',
            'filterType' => GridView::FILTER_DATE_RANGE,
            'visible' => ($tinhtrang != 'chua_nhan'),
            'format'=>'raw',
            'width' => '130px',
            'vAlign' => 'middle',
            'filterWidgetOptions' => [
                'convertFormat'=>true,
                'pluginOptions'=>[                    
                    'opens'=>'left',
                    'locale'=>['format' => 'Y-m-d'],
                ],
                'pluginEvents'=>[
                    'cancel.daterangepicker'=>"function(ev, picker){}"
                ],
                'options' => ['style' => 'font-size: 12px'],
            ],
            'hAlign' => 'center',
            'filterOptions'=>[
                'style' => 'position: relative; width: 120px;',
            ],            
            'value' => function ($model) use ($tinhtrang) {  
                if($tinhtrang == 'dang_lam'){
                    return $model->giaoviec_danglam->thoi_gian_nhan;
                }
                if($tinhtrang == 'da_hoan_thanh'){
                    return $model->giaoviec_dahoanthanh->thoi_gian_nhan;                
                }
            },
        ];


         $gridColumns[] =     [            
            'attribute' => 'thoi_gian_hoan_thanh',
            'label' => 'Dự kiến hoàn thành',
            'filterType' => GridView::FILTER_DATE_RANGE,
            'visible' => ($tinhtrang != 'da_hoan_thanh'),
            'format'=>'raw',
            'width' => '130px',
            'vAlign' => 'middle',
            'filterWidgetOptions' => [
                'convertFormat'=>true,
                'pluginOptions'=>[
                    'opens'=>'left',
                    'locale'=>['format' => 'Y-m-d'],
                ],
                'options' => ['style' => 'font-size: 12px'],
            ],
            'hAlign' => 'center',
            'filterOptions'=>[
                'style' => 'position: relative; width: 120px;',
            ],            
            'value' => function ($model) {                               
                return $model->thoi_gian_hoan_thanh;
            },
        ];



     $gridColumns[] =     [            
            'attribute' => 'thoigianhoanthanh',
            'label' => 'Thời gian hoàn thành',
            'filterType' => GridView::FILTER_DATE_RANGE,
            'visible' => ($tinhtrang == 'da_hoan_thanh'),
            'format'=>'raw',
            'width' => '130px',
            'vAlign' => 'middle',
            'filterWidgetOptions' => [
                'convertFormat'=>true,
                'pluginOptions'=>[
                    'opens'=>'left',
                    'locale'=>['format' => 'Y-m-d'],
                ],
                'options' => ['style' => 'font-size: 12px'],
            ],
            'hAlign' => 'center',
            'filterOptions'=>[
                'style' => 'position: relative; width: 120px;',
            ],            
            'value' => function ($model) {                               
                return $model->giaoviec_dahoanthanh->thoi_gian_hoan_thanh;  
            },
        ];    



    $gridColumns[] =      [            
        'attribute' => 'ghichucongdoantruoc',
        'format' => 'raw',                                   
        'contentOptions' => ['class' => ''],            
        'vAlign' => 'middle',            
        'hAlign' => '', 
        'value' => function ($model){   
            if($model->congdoantruoc) $ghichu_congdoantruoc = $model->congdoantruoc->ghi_chu;
            if(!empty($ghichu_congdoantruoc)){
                return $ghichu_congdoantruoc;
            }
            return '';
        }
    ];


     $gridColumns[] =    [            
            'attribute' => 'ghi_chu',
            'format' => 'raw',            
            'visible' => !(!empty($role) && $role->acl_desc == 'ALL_PRIVILEGES'),            
            'contentOptions' => ['class' => ''],            
            'vAlign' => 'middle',            
            'hAlign' => '', 
            'value' => function ($model)  {   
                return $model->ghi_chu;
            }
        ];


    $gridColumns[] =    [            
        // 'attribute' => 'trang_thai',
        'header' => 'Trạng thái',
        'format' => 'raw',
        'visible' => $tinhtrang == 'chua_nhan',
        'contentOptions' => ['class' => 'text-center'],
        'vAlign' => 'middle',
        'hAlign' => 'center', 
        //  'filterType' => GridView::FILTER_SELECT2,
        // 'filterWidgetOptions' => [
        //     'options' => ['placeholder'=>'-- Chọn --'],
        //     'pluginOptions' => ['allowClear' => true],
        // ],            
        // 'filter'=> KehoachCongdoan::getTrangthaiOptions(),
        'value' => function ($model) {
            return KehoachCongdoan::getTrangthaiLabel($model->trang_thai);
        }
    ];

    $gridColumns = array_filter($gridColumns);

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumns,
        'summary' => "Hiển thị <b>{begin}-{end}</b> trong số <b>{totalCount}</b> mục",
        'emptyText' => 'No results found.<style>.panel-heading {display: none;}</style>',
        'toolbar' => [
            // ['content' =>
            //     !empty($role) && $role->acl_desc == 'ALL_PRIVILEGES'? null : Html::dropDownList("view", $tinhtrang, $list_loc , ['class' => 'v_tinhtrang form-control', 'prompt' => '-- Lọc công việc --'])
            // ],

            ['content' =>
                '<a class="btn '.(($tinhtrang == 'chua_nhan')?'btn-openid':'btn-default').'" href="/acp/kehoach?tinhtrang=chua_nhan"><span class="fa fa-play"></span> Công việc chưa nhận'.$so_chuanhan.'</a>

                <a class="btn '.(($tinhtrang == 'dang_lam')?'btn-linkedin':'btn-default').'" href="/acp/kehoach?tinhtrang=dang_lam"><span class="fa fa-sign-in"></span> Công việc đang làm'.$so_danglam.'</a>

                <a class="btn '.(($tinhtrang == 'da_hoan_thanh')?'btn-success':'btn-default').'" href="/acp/kehoach?tinhtrang=da_hoan_thanh"><span class="fa fa-check"></span> Công việc đã hoàn thành</a>',
            ],

            ['content' =>
                // ($tinhtrang != 'chua_nhan')?:
                '<a href="'.Url::toRoute(['export-mem']).'" class="btn btn-primary"><i class="glyphicon glyphicon-indent-left"></i> Xuất báo cáo </a>',
            ],


            ['content' =>
                !empty($role) && $role->acl_desc == 'ALL_PRIVILEGES'? Html::a('<i class="glyphicon glyphicon-plus"></i> Thêm mới', ['create'], ['class' => 'btn btn-success', 'title' => Yii::t('app', 'Add New')]) : null
            ],
            ['content' =>
                !empty($role) && $role->acl_desc == 'ALL_PRIVILEGES'? Html::button('<i class="glyphicon glyphicon-remove-circle"></i> Xóa chọn', ['data-pjax' => 0, 'type' => 'button', 'title' => Yii::t('app', 'Add New'), 'class' => 'btn btn-danger delete-checked', 'onclick' => 'krajeeDialog.confirm("Bạn có chắc muốn xóa mục này?", function (result) {
                if(result) {
                    jQuery.post(
                        "' . Url::toRoute('kehoach/delete-multiple') . '",
                        {
                            pk : jQuery("#kehoach-lists").yiiGridView("getSelectedRows")
                        },
                        function () {
                            jQuery.pjax.reload({container:"#kehoach-lists"});
                        }
                    );
                }
            });']) : null
            ],


           
            ['content' =>
                Html::a('<i class="glyphicon glyphicon-repeat"></i> Reload', ['index'], ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => Yii::t('app', 'Reset Grid')])
            ],           
        ],
        'id' => 'kehoach-lists',
        'pjax' => false,
        'bordered' => false,
        'striped' => false,
        'condensed' => false,
        'responsive' => false,
        'responsiveWrap' => false,
        'hover' => true,
        'showPageSummary' => false,
        'resizableColumns' => false,
        'persistResize' => false,
        'panel' => [
            'type' => GridView::TYPE_INFO
        ],
        // 'tableOptions' => [
        //     'class' => 'admintablelist table-striped'
        // ],
    ]);

    $css = <<<XP

.plan-index .table > tbody > tr > td, .plan-index .table > tfoot > tr > td{padding: 4px}
    .plan-index .panel.panel-info{width: max-content;}

    
    .plan-index table{background: #f7f7f7}
//    .plan-index table *{color: #055296}
//    .plan-index table{width:max-content; max-width: inherit;}
    .plan-index .table>thead>tr>th{border-bottom: 2px solid #e6e6e6;}
    .plan-index .table>tbody>tr>td{border-bottom: 1px solid #e6e6e6;}
    .item-user {color:white !important; padding: 6px !important;}
    .item-user div.userList{cursor: pointer; position: relative;}
    .item-user div.comment:after{position: absolute; right: 0; top: 0; content:"\\f0e5"; font: normal normal normal 14px/1 FontAwesome;}
    .plan-index .table > tbody > tr > th, .plan-index .table > tfoot > tr > th, .plan-index .table > thead > tr > td, 
//    .kv-panel-before .pull-right, .panel-heading .pull-right{float: left !important;}
    .kv-panel-after{display:none}
    .fancybox-content {
        width: 350px !important;
        height: 320px !important;
    }
    .kv-panel-before{height:55px; position: relative;}
    .kv-panel-before .pull-right{
        position: sticky;
        right: 0;
        top: 0;
    }
    .exportPlan{
        border-bottom-left-radius: 0; border-top-left-radius: 0;padding: 7px;
    }
    .item-user.unfinished{
        background-color: #f3f1f1;
        color: #333 !important;
        border-right: #e8e3e3 1px solid;
    }
    .plan-index .popover{color:#333; word-break: break-all;}

XP;
$this->registerCss($css);
$urlExport = Url::toRoute(['export-mem']);
$js = <<<XP



$(document).on('change','.v_tinhtrang', function (e) {
    tinhtrang = this.value;
    window.location.href = '/acp/kehoach?tinhtrang='+tinhtrang;    
});

$('.userList').popover();
$(document).on('click', function (e) {
    $('[data-toggle="popover"],[data-original-title]').each(function () {
        //the 'is' for buttons that trigger popups
        //the 'has' for icons within a button that triggers a popup
        if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {                
            (($(this).popover('hide').data('bs.popover')||{}).inState||{}).click = false  // fix for BS 3.3.6
        }

    });
});
$(document).on('click', '.exportPlan', function(){
    var month = $('input[name="plan_month"]').val(),
        url = '{$urlExport}?month='+month;
    if(month){
        $.ajax({
            url: url,
            type: 'get',
            success: function (data) {
                if(data)
                    window.location.href = url;
                else
                    krajeeDialog.alert('Không có dữ liệu để xuất ra');
            }
        });
    }
});

$(document).ajaxStart(function () {
    $(".loading-indicator-wrapper").addClass('loader-visible').removeClass('loader-hidden');
}).ajaxStop(function () {
    $(".loading-indicator-wrapper").removeClass('loader-visible').addClass('loader-hidden');
});




$(document).on('change', '#_ghichu__content', function(){
    var content = $('#_ghichu__content').val()
    var plan = $('#_ghichu__kehoach_id').text()
    var cd = $('#_ghichu__cd').text()
    $.ajax({
        url: '/acp/kehoach/unote',
        type: 'POST',
        data: {
            'content': content,
            'plan': plan,
            'cd': cd,
        },
        success: function (data) {
                
        }
    });
});




$(document).ready(function(){
    // var html_ngaygiaohang = $('.plan_ngaygiaohang').html()
    // $('.plan_ngaysanxuat ').append(html_ngaygiaohang)

    // var html_mucdo = $('.plan_mucdouutien').html()
    // $('.plan_donhang ').append(html_mucdo)
    
    // $('.plan_ngaygiaohang').remove()
    // $('.plan_mucdouutien').remove()

    $('.plan-index').css('min-height',$(document).height() - 175);
})



XP;
$this->registerJs($js);
    ?>
</div>