<?php
namespace frontend\models;

use Aabc;
use aabc\base\NotSupportedException;
use aabc\behaviors\TimestampBehavior;
use aabc\db\ActiveRecord;
use aabc\web\IdentityInterface;


class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    
    public static function tableName()
    {
        return 'db_user_frontend';
    }

    
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    
    public function rules()
    {
        return [           
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
    }

    
    public static function findIdentity($id)
    {        
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    
    public static function findByUsername($taikhoan)
    {
        return static::findOne(['taikhoan' => $taikhoan, 'status' => self::STATUS_ACTIVE]);
    }

    
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Aabc::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    
    public function getId()
    {
        // return $this->id;
        return $this->getPrimaryKey();
    }

    
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    
    public function validatePassword($password)
    {
        return Aabc::$app->security->validatePassword($password, $this->matkhau);
    }

    
    public function setPassword($password)
    {
        $this->matkhau = Aabc::$app->security->generatePasswordHash($password);
    }

    
    public function generateAuthKey()
    {
        $this->auth_key = Aabc::$app->security->generateRandomString();
    }

    
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Aabc::$app->security->generateRandomString() . '_' . time();
    }

    
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
}
