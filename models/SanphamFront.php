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

    public $sp_gia;
    public $sp_gia_label;

    public $sp_status;
    public $sp_recycle;
    public $sp_linkseo;

    public $sp_album;
    public $sp_phienban;
    public $sp_listdm;

    public $sp_list_thongso;


    public $sp_gioithieu;
    public $sp_noidung;
    public $sp_tieudeseo;
    public $sp_motaseo;

    public function rules()
    {
        return [            
            [['sp_id','sp_tensp','sp_conhang','sp_images','sp_gia','sp_gia_label','sp_status','sp_recycle','sp_linkseo','sp_album','sp_listdm','sp_phienban','sp_list_thongso'],'safe'],   

        ];
    }
    
    public function sp_images_cover($size){
        return Tuyen::_dulieu('image',$this->sp_images,$size); 
    }

    public function update(){
        $this->sp_conhang_label = Tuyen::_show_conhang($this->sp_conhang);
        $this->sp_gia_label = Tuyen::_show_gia($this->sp_gia);
        $this->sp_album = json_decode($this->sp_album,true);
        $this->sp_phienban = json_decode($this->sp_phienban,true);

        $this->sp_list_thongso = $this->sp_listdm[5];

        $spnn = Tuyen::_dulieu('spnn', $this->sp_id);
        $this->sp_noidung = $spnn['spnn_noidung'];
        $this->sp_gioithieu = $spnn['spnn_gioithieu'];
        $this->sp_tieudeseo = $spnn['spnn_tieudeseo'];
        $this->sp_motaseo = $spnn['spnn_motaseo'];

    }
}
