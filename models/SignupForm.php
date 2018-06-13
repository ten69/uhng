<?php
namespace frontend\models;

use Aabc;
use aabc\base\Model;
use common\models\User;


class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;


    
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
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