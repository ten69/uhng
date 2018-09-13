<?php
namespace frontend\models;

use Aabc;
use aabc\base\Model;
use common\models\User;

use common\components\Tuyen;
use backend\models\Cauhinh;

class CartForm extends Model
{
  
    public $hoten;
    public $dienthoai;    
    public $email;
    
    public $gioitinh;
    public $ghichu;
    
    public $diachi;
    public $tinh;
    public $huyen;
    public $xa;

    public $phuongthuc; //Phương thức thanh toán

    
    public function rules()
    {
        $batbuoc = [];
              
        if(Tuyen::_dulieu('cauhinh',Cauhinh::cart_hoten) == 3 ) $batbuoc[] = 'email';
        if(Tuyen::_dulieu('cauhinh',Cauhinh::cart_gioitinh) == 3 ) $batbuoc[] = 'taikhoan';
        if(Tuyen::_dulieu('cauhinh',Cauhinh::cart_email) == 3 ) $batbuoc[] = 'hoten';
        if(Tuyen::_dulieu('cauhinh',Cauhinh::cart_dienthoai) == 3 ) $batbuoc[] = 'dienthoai';
        if(Tuyen::_dulieu('cauhinh',Cauhinh::cart_diachi) == 3 ){
            $batbuoc[] = 'diachi';
            $batbuoc[] = 'tinh';
            $batbuoc[] = 'huyen';
            $batbuoc[] = 'xa';
        }
        if(Tuyen::_dulieu('cauhinh',Cauhinh::cart_ghichu) == 3 ) $batbuoc[] = 'ngaysinh';
        
        return [
            [$batbuoc, 'required', 'message' => 'Vui lòng nhập {attribute}'],

            ['email', 'trim'],            

            ['email', 'email', 'message' => 'Không đúng định dạng email'],
            ['email', 'string', 'max' => 255],            

            [['gioitinh'], 'integer'],
            [['ngaysinh'], 'safe'],
            [['diachi'], 'string', 'max' => 255],            
            [['hoten'], 'string', 'max' => 50],
            [['dienthoai'], 'string', 'max' => 20],   

            [['tinh','huyen'], 'integer'],
            [['xa'], 'string', 'max' => 255],

            [['phuongthuc'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [            
            'hoten' => 'Họ tên',            
            'diachi' => 'Địa chỉ',
            'dienthoai' => 'Điện thoại',
            'email' => 'Email',
            'ngaysinh' => 'Ngày sinh',
            'gioitinh' => 'Giới tính',
            'ghichu' => 'Ghi chú',
            'xa' => 'Số nhà',
        ];
    }
    
   
}
