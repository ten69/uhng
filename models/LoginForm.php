<?php
namespace frontend\models;

use Aabc;
use aabc\base\Model;
use frontend\models\User;


class LoginForm extends Model
{
    public $taikhoan;
    public $matkhau;
    public $rememberMe = true;

    private $_user;


    
    public function rules()
    {
        return [
            // username and password are both required
            [['taikhoan', 'matkhau'], 'required', 'message' => 'Vui lòng nhập {attribute}'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['matkhau', 'validatePassword1'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'taikhoan' => 'Tài khoản',            
            'matkhau' => 'Mật khẩu',            
        ];
    }

    
    public function validatePassword1($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->matkhau)) {
            // if (!$user) {
                $this->addError($attribute, 'Incorrect taikhoan or matkhau.');
            }
        }
    }

    
    public function login()
    {
        if ($this->validate()) {
            return Aabc::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }

    
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->taikhoan);
        }
        return $this->_user;
    }
}
