<?php
namespace frontend\models;

use Aabc;
use aabc\base\Model;
use frontend\models\User;

use common\components\Tuyen;
use backend\models\Cauhinh;

class SignupForm extends Model
{
  

    public $taikhoan;
    public $hoten;
    public $dienthoai;
    public $matkhau;
    public $nhaplaimatkhau;
    public $email;
    public $diachi;
    public $socmnd;
    public $ngaysinh;
    public $gioitinh;

    public $tinh;
    public $huyen;
    public $xa;
    
    public $fb;
    public $skype;

    public function rules()
    {
        $batbuoc = [];
        
        if(Tuyen::_dulieu('cauhinh',Cauhinh::dangky_email) == 3 ) $batbuoc[] = 'email';
        if(Tuyen::_dulieu('cauhinh',Cauhinh::dangky_tendangnhap) == 3 ) $batbuoc[] = 'taikhoan';
        if(Tuyen::_dulieu('cauhinh',Cauhinh::dangky_hoten) == 3 ) $batbuoc[] = 'hoten';
        if(Tuyen::_dulieu('cauhinh',Cauhinh::dangky_dienthoai) == 3 ) $batbuoc[] = 'dienthoai';
        if(Tuyen::_dulieu('cauhinh',Cauhinh::dangky_socmnd) == 3 ) $batbuoc[] = 'socmnd';
        if(Tuyen::_dulieu('cauhinh',Cauhinh::dangky_ngaysinh) == 3 ) $batbuoc[] = 'ngaysinh';
        if(Tuyen::_dulieu('cauhinh',Cauhinh::dangky_gioitinh) == 3 ) $batbuoc[] = 'gioitinh';
        if(Tuyen::_dulieu('cauhinh',Cauhinh::dangky_diachi) == 3 ){
            $batbuoc[] = 'diachi';
            $batbuoc[] = 'tinh';
            $batbuoc[] = 'huyen';
            $batbuoc[] = 'xa';
        }
        if(Tuyen::_dulieu('cauhinh',Cauhinh::dangky_matkhau) == 3 ) $batbuoc[] = 'matkhau';
        if(Tuyen::_dulieu('cauhinh',Cauhinh::dangky_matkhau_nhaplai) == 3 ) $batbuoc[] = 'nhaplaimatkhau';

        return [           

            [['taikhoan'], 'match', 'pattern' => '/^[a-z0-9]+$/','message' => 'Chỉ nhập chữ thường và số'],

            ['taikhoan', 'unique', 'targetClass' => '\frontend\models\User', 'message' => 'Tài khoản này đã được sử dụng'],

            [['email','taikhoan','matkhau'], 'trim'],  

            [$batbuoc, 'required', 'message' => 'Vui lòng nhập {attribute}'],

            ['matkhau', 'string', 'min' => 6],

            ['nhaplaimatkhau', 'compare', 'compareAttribute'=>'matkhau', 'message'=>"Mật khẩu không trùng khớp" ],

            ['email', 'email', 'message' => 'Không đúng định dạng email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\frontend\models\User', 'message' => 'Email này đã được sử dụng'],


            [['gioitinh'], 'integer'],
            [['ngaysinh'], 'safe'],
            [['diachi', 'fb'], 'string', 'max' => 255],            
            [['hoten', 'skype'], 'string', 'max' => 50],
            [['dienthoai', 'socmnd'], 'string', 'max' => 20],  

            [['tinh','huyen'], 'integer'],
            [['xa'], 'string', 'max' => 255],          

        ];
    }

    public function attributeLabels()
    {
        return [
            'taikhoan' => 'Tài khoản',
            'hoten' => 'Họ tên',
            'matkhau' => 'Mật khẩu',
            'nhaplaimatkhau' => 'Nhập lại mật khẩu',
            'diachi' => 'Địa chỉ',
            'dienthoai' => 'Điện thoại',
            'email' => 'Email',
            'ngaysinh' => 'Ngày sinh',
            'gioitinh' => 'Giới tính',
            'socmnd' => 'Số CMND/ Thẻ căn cước',
            'xa' => 'Số nhà',
        ];
    }
    
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }        
        $user = new User();
        $boqua = [
            'nhaplaimatkhau',
            'tinh',
            'huyen',
            'xa',
        ];
        foreach ($this->attributes as $k => $v) {
            if(!in_array($k, $boqua)){
                $user[$k] = $v;
            }
        }

        $user['diachi'] = json_encode([
            'tinh' => $this->tinh,
            'huyen' => $this->huyen,
            'xa' => $this->xa,
        ]);

        $user->taikhoan = $this->taikhoan;

        if(!empty($this->matkhau)){
            $user->setPassword($this->matkhau);
            $user->generateAuthKey();
        }
        
        return $user->save() ? $user : null;        
    }
}
