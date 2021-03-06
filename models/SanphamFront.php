<?php

namespace frontend\models;

use Aabc;
use aabc\base\Model;
use common\components\Tuyen;

class SanphamFront extends Model
{
    public $sp_id;
    public $sp_tensp;
    public $sp_conhang;
    public $sp_conhang_label;

    public $sp_images;
    public $sp_images_ts;

    public $sp_gia;
    public $sp_gia_sort;
    public $sp_gia_label;

    public $sp_status;
    public $sp_recycle;
    public $sp_linkseo;

    public $sp_vat;
    public $sp_vat_value;
    public $sp_vat_label;

    public $sp_album;
    public $sp_phienban;
    public $sp_listdm;

    public $sp_danhmuc;
    public $sp_khuyenmai;
    public $sp_chinhsach;
    public $sp_thongso;
    public $sp_thongso_full;
    public $sp_baiviet;

    public $sp_gioithieu;
    public $sp_noidung;
    public $sp_tieudeseo;
    public $sp_motaseo;

    public function rules()
    {
        return [            
            [['sp_id','sp_tensp','sp_conhang','sp_images','sp_images_ts','sp_gia','sp_gia_label','sp_status','sp_recycle','sp_linkseo','sp_album','sp_listdm','sp_phienban','sp_thongso','sp_khuyenmai','sp_danhmuc','sp_chinhsach','sp_baiviet','sp_thongso_full', 'sp_gia_sort','sp_vat','sp_vat_value','sp_vat_label'],'safe'],   

        ];
    }
    
    public function sp_images_cover($size){
        return Tuyen::_dulieu('image',$this->sp_images,$size); 
    }

    public function sp_images_cover_ts($size){ //Thông số kỹ thuật
        return Tuyen::_dulieu('image',$this->sp_images_ts,$size); 
    }

    public function update(){
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        $this->sp_conhang_label = Tuyen::_show_conhang($this->sp_conhang);
        $this->sp_gia_label = Tuyen::_show_gia($this->sp_gia);
        $this->sp_album = json_decode($this->sp_album,true);
        $this->sp_phienban = json_decode($this->sp_phienban,true);

        $this->sp_vat_label = (!empty($this->sp_vat)?'Đã':'Chưa').' bao gồm VAT '.$this->sp_vat_value.'%';

        $this->sp_thongso = $this->sp_listdm[5]; //Array
        $this->sp_danhmuc = !empty($this->sp_listdm[1][0])?$this->sp_listdm[1][0]:''; //1 id

        $ts_full = [];
        foreach ($this->sp_thongso as $idts => $arr) {
            $ts = Tuyen::_dulieu('danhmuc', $idts);
            $ts_full[$ts['dm_idcha']][$idts] = $arr;            
        }
        $this->sp_thongso_full =  $ts_full;


        //Start khuyenmai, chinhsach
        if(empty($this->sp_khuyenmai)) $this->sp_khuyenmai = [];
        $khuyenmai = Tuyen::_dulieu('cs','allkhuyenmai');

        if(empty($this->sp_chinhsach)) $this->sp_chinhsach = [];
        $chinhsach = Tuyen::_dulieu('cs','allchinhsach');

        $this->sp_baiviet = [];

        
        $dm = Tuyen::_dulieu('danhmuc',$this->sp_danhmuc);

        if(!empty($dm['dm_listbv'])) if(is_array($dm['dm_listbv'])){
            $this->sp_baiviet = array_merge($this->sp_baiviet, $dm['dm_listbv']);
        }
        if(!empty($dm['dm_khuyenmai'])) if(is_array($dm['dm_khuyenmai'])){
            $khuyenmai = array_merge($khuyenmai, $dm['dm_khuyenmai']);
        }
        if(!empty($dm['dm_chinhsach'])) if(is_array($dm['dm_chinhsach'])){
            $chinhsach = array_merge($chinhsach, $dm['dm_chinhsach']);
        }
        
        $khuyenmai = array_merge($khuyenmai, $this->sp_khuyenmai);

        //Lọc khuyenmai hết hạn
        $khuyenmai_filter = [];
        foreach ($khuyenmai as $k_km => $id_km) {
            $km = Tuyen::_dulieu('khuyenmai',$id_km);
            if($km){
                if(empty($km['cs_ngayketthuc'])){
                    $khuyenmai_filter[] = $id_km;
                }
                elseif(strtotime($km['cs_ngayketthuc']) > time()){
                    $khuyenmai_filter[] = $id_km;
                }
            }
        }
        $this->sp_khuyenmai = $khuyenmai_filter;
        

        $chinhsach = array_merge($chinhsach, $this->sp_chinhsach);  
        $this->sp_chinhsach = $chinhsach;
        //End khuyenmai, chinhsach
        

        $spnn = Tuyen::_dulieu('spnn', $this->sp_id);
        if($spnn){
            $this->sp_noidung = $spnn['spnn_noidung'];
            $this->sp_gioithieu = $spnn['spnn_gioithieu'];
            $this->sp_tieudeseo = $spnn['spnn_tieudeseo'];
            $this->sp_motaseo = $spnn['spnn_motaseo'];
        }

    }
}
