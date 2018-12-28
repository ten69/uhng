<?php
namespace frontend\models;

use Aabc;
use aabc\base\Model;

use common\components\Tuyen;
use backend\models\Cauhinh;

class CartForm extends Model
{
    public $xungho;
    public $hoten;
    public $dienthoai;    
    public $email;
    public $matkhau;
    
    public $gioitinh;
    public $ghichu;
    
    public $giaohang;

    public $diachi;
    public $tinh;
    public $huyen;
    public $xa;

    public $thanhtoan; //Phương thức thanh toán

    
    public function rules()
    {
        $batbuoc = [];
        
        if(Tuyen::_dulieu('cauhinh',Cauhinh::cart_xungho) == 3 ) $batbuoc[] = 'xungho';      
        if(Tuyen::_dulieu('cauhinh',Cauhinh::cart_hoten) == 3 ) $batbuoc[] = 'hoten';
        if(Tuyen::_dulieu('cauhinh',Cauhinh::cart_gioitinh) == 3 ) $batbuoc[] = 'gioitinh';
        if(Tuyen::_dulieu('cauhinh',Cauhinh::cart_email) == 3 ) $batbuoc[] = 'email';
        if(Tuyen::_dulieu('cauhinh',Cauhinh::cart_dienthoai) == 3 ) $batbuoc[] = 'dienthoai';
        if(Tuyen::_dulieu('cauhinh',Cauhinh::cart_giaohang) == 3 ) $batbuoc[] = 'giaohang';
        if(Tuyen::_dulieu('cauhinh',Cauhinh::cart_thanhtoan) == 3 ) $batbuoc[] = 'thanhtoan';

        if(Tuyen::_dulieu('cauhinh',Cauhinh::cart_diachi) == 3 ){
            $batbuoc[] = 'diachi';
            $batbuoc[] = 'tinh';
            $batbuoc[] = 'huyen';
            $batbuoc[] = 'xa';            
        }
        if(Tuyen::_dulieu('cauhinh',Cauhinh::cart_ghichu) == 3 ) $batbuoc[] = 'ghichu';
        
        return [
            [$batbuoc, 'required', 'message' => 'Vui lòng nhập {attribute}'],

            ['email', 'trim'],            

            ['email', 'email', 'message' => 'Không đúng định dạng email'],
            ['email', 'string', 'max' => 255],     

            ['matkhau', 'string'],

            [['gioitinh'], 'integer'],
            [['xungho'], 'integer'],
            [['giaohang'], 'integer'],
            
            [['diachi'], 'string', 'max' => 255],            
            [['hoten'], 'string', 'max' => 50],
            [['dienthoai'], 'string', 'max' => 20],   

            // [['tinh'], 'integer'],
            [['tinh','xa','huyen'], 'string', 'max' => 255],

            [['thanhtoan'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [            
            'xungho' => 'Xưng hô',
            'hoten' => 'Họ tên',
            'diachi' => 'Địa chỉ',
            'dienthoai' => 'Số điện thoại',
            'giaohang' => 'Phương thức giao hàng',
            'email' => 'Email',
            'matkhau' => 'Mật khẩu',
            'ngaysinh' => 'Ngày sinh',
            'gioitinh' => 'Giới tính',
            'ghichu' => 'Ghi chú đơn hàng',
            'tinh' => 'Tỉnh/thành',
            'huyen' => 'Quận/huyện',
            'xa' => 'Xã/Phường, Số nhà',
            'thanhtoan' => 'Phương thức thanh toán',
        ];
    }
    
   
}
