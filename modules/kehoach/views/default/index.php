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
    $tinhtrang = 'da_nhan';
}else{
    $tinhtrang = $_GET['tinhtrang'];
}


$list_loc = [
    'chua_nhan' => 'Đơn hàng chưa nhận',
    'da_nhan' => 'Đơn hàng đã nhận',
];

if(!empty($role) && $role->acl_desc == 'ALL_PRIVILEGES') {
    $this->title = 'Bảng cập nhật tiến độ sản xuất';
}else{
    $this->title = 'Danh sách '.$list_loc[$tinhtrang];
}



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

</script>

<style type="text/css">
    .cd-ht-cn {
        border: 3px solid #e4e4e4 !important;
    }

    td a span.btn {
    width: 22px;
}

    .btn-xs, .btn-group-xs > .btn {
        padding: 1px 3px;
        font-size: 13px;
        font-weight: bold;
    }
    .panel.panel-info{
        /*margin-bottom: 200px;*/
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
        /*top: calc(100% + 5px);*/
        /*right: 0;*/

        right: 100%;
        top: -50px;

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
        padding: 8px 0 0 0;
        color: #FFF;
    }
    .tiendo{
        padding: 11px 0 0 0;
        font-size: 20px;
    }
    .gach-ngang{
        float: left;
        width: 60px;
        height: 3px;
        background: #e4e4e4;
        display: block;
        padding: 0;
        margin: 18px 0 0 0;
    }

    .cd-ht.item-cd .tencd {
        bottom: -28px;
    }

    .item-cd .tencd{
        clear: both;
        position: absolute;
        color: #484848;
        bottom: -25px;
        width: 100px;
        text-align: center;
        left: -30px;
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
        'headerOptions' => ['class' => 'checkboxcolumn'],
        'contentOptions' => ['class' => 'checkboxcolumn'],
    ];


    $gridColumns[] = [
        'header' => Html::a('<span class="btn btn-danger btn-xs" style=""><i class="fa fa-trash" aria-hidden="true"></i> Xóa chọn</span>', 'javascript:;', [
                            'title' => 'Xóa lwacj chọn',
                            'class' => 'apply',
                            'data-method' => 'post',
                            'data-pjax' => '0',
                            'onclick' => "
                                var r = confirm(\"Bạn có chắc muốn các lựa chọn này?\");
                                if (r == true) {

                                    var parent = $('div.grid-view')
                                    var select = parent.yiiGridView('getSelectedRows')

                                    $.ajax('".Url::toRoute(['delete-multi'])."', {
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
                        ]),
        'class' => 'common\components\xPActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'template' => ' {export} {update} {delete}',
        'options' => ['style' => 'width: 100px'],
        'contentOptions' => ['class' => 'custom-w'],
        'headerOptions' => ['class' => 'text-center'],
        'buttons' => [

            'export' => function ($url, $model) {
                return Html::a('<span class="btn btn-success btn-xs" style="margin: 1px 1px 0 0;"><i class="glyphicon glyphicon-indent-left"></i></span>', Url::toRoute(['export', 'id' => $model->kehoach_id]), [
                    'title' => 'Xuất kế hoạch',
                    'class' => 'export',
                    'data-pjax' => '0',
                ]);
            },
            'update' => function ($url, $model) {
                if($model->trang_thai == Kehoach::DAHOANTHANH){
                    return '<span class="btn btn-primary btn-xs disabled" style="margin: 1px 1px 0 0;"><i class="glyphicon glyphicon-pencil"></i></span>';
                }else{                    
                    return Html::a('<span class="btn btn-primary btn-xs" style="margin: 1px 1px 0 0;"><i class="glyphicon glyphicon-pencil"></i></span>', Url::toRoute(['update', 'id' => $model->kehoach_id]), [
                        'title' => 'Cập nhật',
                        'class' => 'update'
                    ]);
                }
            },

            'delete' => function ($url, $model) {
                $url = Url::toRoute(['delete', 'id' => $model->kehoach_id]);
                 if($model->trang_thai == Kehoach::DAHOANTHANH){
                    return '<span class="btn btn-danger btn-xs disabled"  style="margin: 1px 1px 0 0;"><i class="glyphicon glyphicon-trash"></i></span>';
                }else{ 
                    return Html::a('<span class="btn btn-danger btn-xs" style="margin: 1px 1px 0 0;"><i class="glyphicon glyphicon-trash"></i></span>', 'javascript:;', [
                        'title' => 'Xóa',
                        'class' => 'delete',
                        'data-method' => 'post',
                        'data-pjax' => '0',
                        'onclick' => "
                            var r = confirm(\"Bạn có chắc muốn xóa mục này?\");
                            if (r == true) {
                                $.ajax('$url', {
                                    type: 'POST'
                                }).done(function(data) {
                                    if(data === 'delete_success'){
                                        jQuery.pjax.reload({container:'#order-lists'});
                                    }else {
                                        alert('Có lỗi xảy ra trong quá trình xử lý. Vui lòng thử lại sau');
                                    }
                                });
                            } 
                        "
                    ]);
                }
            }
        ],
    ];
        


    $gridColumns[] = [
            'attribute' => 'code',            
            'width' => '120px',
            'vAlign' => 'middle',
            'format'=>'raw',
            'contentOptions' => function($model){ 
                $class = [];                
                if(empty($model->ngay_giao_hang)){
                    $class['class'] = '';
                }else{                   
                    $dukienhoanthanh = strtotime($model->ngay_giao_hang);                        
                    if(time() > $dukienhoanthanh && $model->trang_thai != Kehoach::DAHOANTHANH){
                        $class['class'] = 'tt_quahan';
                    }elseif(time() > ($dukienhoanthanh - (24 * 60 * 60))  && $model->trang_thai != Kehoach::DAHOANTHANH){
                        $class['class'] = 'tt_tronghan';
                    }else{
                        $class['class'] = 'tt_truochan';
                    }                    
                }                
                return $class;
            },            
            'value' => function ($model) {
                return $model->code;
            },
        ];

    

    $gridColumns[] = [
            'attribute' => 'id_don_hang',
            'width' => '120px',
            'vAlign' => 'middle',
            'format'=>'raw',            
            'headerOptions' => ['class' => ''],
            'value' => function ($model) {
                if(!empty($model->id_don_hang)){
                    $return = '';  
                    if($model->donhang->KieuIn == 'offset'){
                        $return .=  Html::a($model->donhang->info->infoCode, Url::toRoute(['orders/view', 'id' => $model->donhang->order_id]), [
                            'title' => 'Xem',
                            'class' => 'view',
                            'target' => '_blank',
                        ]).'<br/>';
                    }else{
                        $return .=  Html::a($model->donhang->info->infoCode, Url::toRoute(['update', 'id' => $model->donhang->order_id]), [
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
            // 'label' => 'Sản phẩm',
            'attribute' => 'id_san_pham',
            'format' => 'html',
            'vAlign' => 'middle',
                                  
            'filter' => '<input placeholder="" class="form-control" name="s_sanpham" value="'.(empty($_GET['s_sanpham'])?'':$_GET['s_sanpham']).'" type="text">',    

            'value' => function ($model) {
                $_sanpham = $model->sanpham;
                

                if(empty($_sanpham)) return '';

                // echo '<pre>';
                // print_r($_sanpham);
                // echo '</pre>';

                // $arr_return['so_trang'] = $orderinfo->inner_page_amount;
                // $arr_return['loai_giay'] = $orderinfo->paper->GiayBiaChatLieu;
                // $arr_return['kho_san_pham'] = $orderinfo->length . ' x ' . $orderinfo->width;

                $return = !empty($_sanpham->product_name) ? '<strong>'.$_sanpham->product_name.'</strong>' : '';

                $return .= !empty($_sanpham->product->title) ? (!empty($return) ? ' - ' : '').'<strong>'.$_sanpham->product->title.'</strong>' : '';

                $return .= (!empty($return) ? '<br>' : '').'Khổ '.$_sanpham->length .' x '.$_sanpham->width;
                $return .= !empty($return) && !empty($_sanpham->inner_page_amount) ? '<br>' . $_sanpham->inner_page_amount .' trang '. Kehoach::getLoaiGiay($_sanpham->paper->GiayBiaChatLieu) : '';
                return $return;
            },
            'contentOptions' => ['style' => 'width:200px;'],            
        ];



    $gridColumns[] =     [            
            'attribute' => 'ngay_san_xuat',
            'filterType' => GridView::FILTER_DATE_RANGE,
            // 'visible' => (!empty($role) && $role->acl_desc == 'ALL_PRIVILEGES'),
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
            'contentOptions' => ['class' => ''],
            'headerOptions' => ['class' => 'plan_ngaysanxuat'],
            'value' => function ($model) {
                $return = '';
                if(!empty($model->ngay_san_xuat)){
                    $return = $model->ngay_san_xuat;
                }                
                return $return;
            },
        ];


    $gridColumns[] =     [       
            'width' => '130px',     
            'attribute' => 'ngay_giao_hang',

            'filterType' => GridView::FILTER_DATE_RANGE,
            'filterWidgetOptions' => [
                'convertFormat'=>true,
                'pluginOptions'=>[
                    'opens'=>'left',
                    'locale'=>['format' => 'Y-m-d'],
                ],
                'options' => ['style' => 'font-size: 12px'],
            ],
            'filterOptions'=>[
                'style' => 'position: relative; width: 120px;',
            ],

            'filterOptions' => ['class' => 'plan_ngaygiaohang'],
            'contentOptions' => ['class' => 'plan_ngaygiaohang'],
            'headerOptions' => ['class' => 'plan_ngaygiaohang'],
            'format'=>'raw',
            'vAlign' => 'middle',            
            'hAlign' => 'center',            
            'value' => function ($model) {   
                $return = '';             
                if(!empty($model->ngay_giao_hang)){
                    $return = $model->ngay_giao_hang;
                }
                return $return;
            },
        ];
        


     $gridColumns[] =     [       
            'width' => '180px',     
            'attribute' => 'trang_thai',            
            'format'=>'raw',
            'vAlign' => 'middle',            
            'hAlign' => 'center', 

            'filterType' => GridView::FILTER_SELECT2,
            'filterWidgetOptions' => [
                'options' => ['placeholder'=>'-- Chọn --'],
                'pluginOptions' => ['allowClear' => true],
            ],            
            'filter'=> Kehoach::getTrangthaiOptions(),

            'value' => function ($model) {                   
                return Kehoach::getTrangthaiLabel($model->trang_thai);
            },
        ];  


    $gridColumns[] =     [            
            'label' => 'Tiến độ',
            'format' => 'raw',
            'visible' => !empty($role) && $role->acl_desc == 'ALL_PRIVILEGES' ,
            // 'attribute' => 'muc_do_uu_tien',
            // 'filterOptions' => ['class' => 'hide plan_ngaygiaohang'],
            'contentOptions' => ['class' => 'text-center'],
            // 'headerOptions' => ['class' => 'hide plan_mucdouutien'],
            'vAlign' => 'middle',
            'hAlign' => 'center',
            'value' => function ($model) use ($listCongDoanSX, $settings, $role) {
                $return = '';
                $i = -1;
                // foreach($model->congdoan as $k_ => $congDoan){
                //     echo $congDoan->ten;
                // }
                // die;
                $dem = 0;
                $i_max = count($model->congdoan);
                foreach($model->congdoan as $k_ => $congDoan){

                    $giaoViec = $congDoan->giaoviec;

                    // echo '<pre>';
                    // print_r($congDoan);
                    // echo '</pre>';

                    // $bg = $settings->get('cong_doan_' . $k_ .'_color');
                    $i++;           
                    // $list_cdsx = @unserialize($model->data_cong_doan_san_xuat);
                    $list_cdsx = [];
                    $_listUser = '';
                    
                    $danhanviec = 0; //Đã có người nhận việc chưa?

                    if($giaoViec){
                        foreach ($giaoViec as $_item) {                            
                            $_listUser .= '<tr><td>'.$_item->tennhanvien.'</td><td>';

                            if(!empty($_item->thoi_gian_nhan)){
                                $_listUser .= '<i>Nhận việc lúc: '.$_item->thoi_gian_nhan.'</i>';
                                if(empty($_item->thoi_gian_hoan_thanh) && $_item->trang_thai == KehoachGiaoviec::DANGLAM ){
                                    $danhanviec = 1;
                                    $_listUser .= '<br/><i>Đang thực hiện</i>';
                                }
                                elseif($_item->trang_thai == KehoachGiaoviec::TRALAI ){
                                    $_listUser .= '<br/><i>Bị trả lại lúc: '.$_item->thoi_gian_tra_lai.'</i>';
                                }
                                else{
                                    $_listUser .= '<br/><i>Hoàn thành lúc: '.$_item->thoi_gian_hoan_thanh.'</i>';
                                } 
                            }

                            $_listUser .= '</td></tr>';                                                           
                        }
                    }                       
                                       

                    // $tinhtrang_congdoan = null;
                    // $style = '';
                    // if(isset($list_cdsx[$k_]['finish'])){
                    //     $tinhtrang_congdoan = $list_cdsx[$k_]['finish'];
                    //     if($tinhtrang_congdoan == 1) $style = " background-color: ".(!empty($bg) ? $bg : '#065ab7');
                    // }

                    $congdoanhientai = '';
                    if($model->trang_thai != Kehoach::DAHOANTHANH && $congDoan->trang_thai != KehoachCongdoan::HOANTHANH && $dem  == 0){
                        $dem = 1;
                        if($danhanviec){
                            $congdoanhientai = 'cd-ht';    
                        }else{
                            $congdoanhientai = 'cd-ht cd-ht-cn'; //Chưa nhận
                        }
                        
                    }
                    
                    $congdoandahoanthanh = '';
                    if($congDoan->trang_thai == KehoachCongdoan::HOANTHANH ){
                        $congdoandahoanthanh = 'bg-green';
                    }else{
                        $congdoandahoanthanh = '';
                    }
                    
                    //'.($i + 1).' $congDoan->tencongdoan
                    $return .= '<div class="'.$congdoanhientai.' '.$congdoandahoanthanh.' item-cd item-cd-'.$k_.'"><span class="tiendo fa fa-check text-while"></span><span class="tencd">'.($i + 1).'/'.$i_max.'</span>';

                    // if($tinhtrang_congdoan == 2) $return .= '<span class="fa fa-2x fa-exclamation-triangle mailbox-read-message text-orange"></span>';
                    


                    // if($congdoandangthuchien == $k_ && $role->acl_desc != 'ALL_PRIVILEGES'){
                    //     $btn = '';
                    //     $planApply = PlanApply::find()->andWhere(['kehoach_id' => $model->kehoach_id, 'plan_status' => $model->trang_thai, 'user_id' => Yii::$app->user->identity->user_id])->count();
                    //     if($planApply > 0){
                    //         echo '<style>.cd-ht{border-color: #ffac63;}</style>';
                    //         $url = Url::toRoute(['kehoach/finish']);
                    //         $btn = Html::a('Hoàn thành', 'javascript:;', [                                
                    //             'class' => 'finish text-orange',
                    //             'data-method' => 'post',
                    //             'data-pjax' => '0',
                    //             'onclick' => "
                    //                 var r = confirm(\"Bạn có chắc công đoạn này đã hoàn thành?\");
                    //                 if (r == true) {
                    //                     $.ajax('$url', {
                    //                         type: 'POST',
                    //                         data: {id:{$model->kehoach_id}}
                    //                     }).done(function(data) {
                    //                         if(data.status === 'success'){
                    //                             unloading();
                    //                             $('#kehoach-lists').html(data.element)
                    //                         }else {
                    //                             alert('Có lỗi xảy ra trong quá trình xử lý. Vui lòng thử lại sau');
                    //                         }
                    //                     });
                    //                 } 
                    //             "
                    //         ]);
                    //     }else {
                    //         $url = Url::toRoute(['kehoach/apply']);
                    //         $btn = Html::a('Nhận lệnh', 'javascript:;', [
                    //             'class' => 'apply',
                    //             'data-method' => 'post',
                    //             'data-pjax' => '0',
                    //             'onclick' => "
                    //                 var r = confirm(\"Bạn có chắc muốn nhận công đoạn này?\");
                    //                 if (r == true) {
                    //                     $.ajax('$url', {
                    //                         type: 'POST',
                    //                         data: {id:{$model->kehoach_id}}
                    //                     }).done(function(data) {
                    //                         if(data.status === 'success'){
                    //                             unloading();
                    //                             $('#kehoach-lists').html(data.element)                                            
                    //                         }else {
                    //                             alert('Có lỗi xảy ra trong quá trình xử lý. Vui lòng thử lại sau');
                    //                         }
                    //                     });
                    //                 } 
                    //             "
                    //         ]);
                    //     }

                    //     if(!empty($list_cdsx[$i]['time']) || !empty($list_cdsx[$i]['time_done'])){ //Nếu có kế hoạch thì mới nhận lệnh
                    //         $return .= $btn;
                    //     }
                    // }


                    $return .= "<div class='userList' >";
                    $return .= "<div class='".(($congDoan->trang_thai == Kehoach::DAHOANTHANH)?'bg-green-active':'bg-gray')." panel-heading'>Công đoạn: <b>".$congDoan->tencongdoan.'</b></div><br/>';


                   
                    if(!empty($congDoan->thoi_gian_bat_dau) || !empty($congDoan->thoi_gian_hoan_thanh)){
                        $return .= '<div><b>Kế hoạch thực hiện</b></div><table>';                    
                        if(!empty($congDoan->thoi_gian_bat_dau)){
                            $return .= "<tr><td>Bắt đầu:</td><td>".$congDoan->thoi_gian_bat_dau.'</td>';
                        }
                        if(!empty($congDoan->thoi_gian_hoan_thanh)){
                            $return .= "<tr><td>Hoàn thành:</td><td>".$congDoan->thoi_gian_hoan_thanh.'</td>';
                        }
                        $return .= '</table><br/>';
                    }else{
                        $return .= '<div><i>Chưa có Kế hoạch thực hiện</i></div><br/>';
                    }


                    if(!empty($_listUser)){
                        $return .= '<div><b>Nhân viên được giao việc</b><div><table class="table table-bordered table-condensed table-striped">'.$_listUser.'</table></div></div>';
                    }else {
                        $return .= '<div><i>Chưa giao việc</i></div><br/>';
                    }


                    if(!empty($congDoan->ghi_chu)){
                        $return .= '<div><b>Ghi chú</b></div><div>'.$congDoan->ghi_chu.'</div>';
                    }


                    $lydo = isset($list_cdsx[$k_]['lydo']) && !empty($list_cdsx[$k_]['lydo']) ? $list_cdsx[$k_]['lydo'] : '';
                    if(!empty($lydo)){
                        $return .= '<br/><div><b>Lý do làm lại</b></div><div>'.$lydo.'</div>';
                    }


                    $return .= '</div>';

                    $return .= '</div>';

                    $return .= '<div class="gach-ngang '.(empty($congdoandahoanthanh)?'hide':$congdoandahoanthanh).'"></div>';

                    $return .= '</div>';                       
                }
                return $return;
                return '';
            },
        ];





    $gridColumns = array_filter($gridColumns);

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumns,
        'emptyText' => 'No results found.<style>.panel-heading {display: none;}</style>',
        'summary' => "Hiển thị <b>{begin}-{end}</b> trong số <b>{totalCount}</b> mục",
        'toolbar' => [
            ['content' =>
                // !empty($role) && $role->acl_desc == 'ALL_PRIVILEGES' ?
                '<a href="'.Url::toRoute(['export']).'" class="btn btn-primary"><i class="glyphicon glyphicon-indent-left"></i> Xuất báo cáo </a>' 
            ],


            ['content' =>
                !empty($role) && $role->acl_desc == 'ALL_PRIVILEGES'? Html::a('<i class="glyphicon glyphicon-plus"></i> Thêm mới', ['create'], ['class' => 'btn btn-success', 'title' => Yii::t('app', 'Add New')]) : null
            ],
           


           ['content' =>
                !empty($role) && $role->acl_desc == 'ALL_PRIVILEGES'? null : Html::dropDownList("view", $tinhtrang, $list_loc , ['class' => 'v_tinhtrang form-control', 'prompt' => '-- Lọc danh sách --'])
            ],

            ['content' =>
                Html::a('<i class="glyphicon glyphicon-repeat"></i> Tải lại & Xóa lọc trang', ['index'], ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => Yii::t('app', 'Reset Grid')])
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
        // border-bottom-left-radius: 0; border-top-left-radius: 0;padding: 7px;
    }
    .item-user.unfinished{
        background-color: #f3f1f1;
        color: #333 !important;
        border-right: #e8e3e3 1px solid;
    }
    .plan-index .popover{color:#333; word-break: break-all;}

XP;
$this->registerCss($css);
$urlExport = Url::toRoute(['export-ajax']);
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




// $(document).on('change', '#_ghichu__content', function(){
//     var content = $('#_ghichu__content').val()
//     var plan = $('#_ghichu__kehoach_id').text()
//     var cd = $('#_ghichu__cd').text()
//     $.ajax({
//         url: '/acp/kehoach/unote',
//         type: 'POST',
//         data: {
//             'content': content,
//             'plan': plan,
//             'cd': cd,
//         },
//         success: function (data) {
                
//         }
//     });
// });


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