<?php
namespace frontend\models;

use Aabc;
use aabc\base\Model;
use common\models\User;

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
        if(Tuyen::_dulieu('cauhinh',Cauhinh::dangky_diachi) == 3 ) $batbuoc[] = 'diachi';
        if(Tuyen::_dulieu('cauhinh',Cauhinh::dangky_matkhau) == 3 ) $batbuoc[] = 'matkhau';
        if(Tuyen::_dulieu('cauhinh',Cauhinh::dangky_matkhau_nhaplai) == 3 ) $batbuoc[] = 'nhaplaimatkhau';

        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],


            ['email', 'trim'],            
            [$batbuoc, 'required', 'message' => 'Vui lòng nhập'],


            ['nhaplaimatkhau', 'compare', 'compareAttribute'=>'matkhau', 'message'=>"Mật khẩu không trùng khớp" ],


            ['email', 'email', 'message' => 'Không đúng định dạng email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
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
        ];
    }
    
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        //$user->save(false);
        return $user->save() ? $user : null;
        // $auth = \Aabc::$app->authManager;
        // $authorRole = $auth->getRole('user');
        // $auth->assign($authorRole, $user->getId());
        // return $user;
    }
}
