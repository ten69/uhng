<?php

namespace backend\modules\kehoach\models;

use backend\models\Orders;
use backend\models\OrderInfo;
use backend\models\Schedule;

use Yii;

/**
 * This is the model class for table "qli_kehoach_congdoan".
 *
 * @property string $id_ke_hoach
 * @property string $id_cong_doan
 * @property string $thoi_gian_bat_dau
 * @property string $thoi_gian_hoan_thanh
 * @property string $ghi_chu
 * @property integer $trang_thai
 *
 * @property Kehoach $idKeHoach
 */
class KehoachCongdoan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'qli_kehoach_congdoan';
    }

    const CHUANHAN = 1;
    const DANGLAM = 2;
    const HOANTHANH = 3;
    const SUALOI = 4;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_ke_hoach', 'id_cong_doan'], 'required'],
            [['id_ke_hoach', 'id_cong_doan', 'trang_thai'], 'integer'],
            [['thoi_gian_bat_dau', 'thoi_gian_hoan_thanh'], 'safe'],
            [['ghi_chu'], 'string', 'max' => 255],
            [['id_ke_hoach'], 'exist', 'skipOnError' => true, 'targetClass' => Kehoach::className(), 'targetAttribute' => ['id_ke_hoach' => 'kehoach_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_ke_hoach' => 'Kế hoạch',
            'id_cong_doan' => 'Công đoạn',
            'thoi_gian_bat_dau' => 'Thời gian bắt đầu',
            'thoi_gian_hoan_thanh' => 'Thời gian hoàn thành',
            'ghi_chu' => 'Ghi chú',
            'trang_thai' => 'Trạng thái',

            'idsanpham' => 'Sản phẩm',
            'iddonhang' => 'Đơn hàng',
            'makehoach' => 'Mã kế hoạch',
            'nhanviengiao' => 'Nhân viên được giao',
            'ghichucongdoantruoc' => 'Ghi chú công đoạn trước',
        ];
    }



    public function beforeSave($insert)
    {        
        if(empty($this->trang_thai)) $this->trang_thai = self::CHUANHAN;

        if(!empty($this->thoi_gian_bat_dau)) $this->thoi_gian_bat_dau = date('Y-m-d H:i:s',strtotime($this->thoi_gian_bat_dau));
        if(!empty($this->thoi_gian_hoan_thanh)) $this->thoi_gian_hoan_thanh = date('Y-m-d H:i:s',strtotime($this->thoi_gian_hoan_thanh));
        
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }


    public function afterFind()
    {   
        if(!empty($this->thoi_gian_bat_dau)) $this->thoi_gian_bat_dau = date('H:i d-m-Y',strtotime($this->thoi_gian_bat_dau));
        if(!empty($this->thoi_gian_hoan_thanh)) $this->thoi_gian_hoan_thanh = date('H:i d-m-Y',strtotime($this->thoi_gian_hoan_thanh));
        parent::afterFind();
    }


    public static function getTrangthaiOptions()
    {
        return [
            self::CHUANHAN => 'Chưa nhận',
            self::DANGLAM => 'Đang làm',
            self::HOANTHANH => 'Hoàn thành',
            self::SUALOI => 'Sửa lỗi',
        ];
    }

    public static function getTrangthaiLabel($value = '')
    {
        $array = self::getTrangthaiOptions();
        if ($value === null || !array_key_exists($value, $array))
            return '';
        return $array[$value];
    }


    public function getTencongdoan()
    {
        $value = $this->id_cong_doan;
        $array = Schedule::congDoanSanXuat();
        if ($value === null || !array_key_exists($value, $array))
            return '';
        return $array[$value];
    }


    public function getCongdoantruoc()
    {
        $congdoantruoc = KehoachCongdoan::find()
                            ->andWhere(['id_ke_hoach' => $this->id_ke_hoach])
                            ->andWhere(['<','id_cong_doan', $this->id_cong_doan])
                            ->orderBy(['id_cong_doan' => SORT_DESC])
                            ->one();
        if($congdoantruoc){
            return $congdoantruoc;
        }
        return '';
    }

    public function getCongdoansau()
    {
        $congdoansau = KehoachCongdoan::find()
                            ->andWhere(['id_ke_hoach' => $this->id_ke_hoach])
                            ->andWhere(['>','id_cong_doan', $this->id_cong_doan])
                            ->orderBy(['id_cong_doan' => SORT_ASC])
                            ->one();
        if($congdoansau){
            return $congdoansau;
        }
        return '';
    }   


    public function getGiaoviec()
    {
        return $this->hasMany(KehoachGiaoviec::className(), ['id_ke_hoach' => 'id_ke_hoach', 'id_cong_doan' => 'id_cong_doan']);       
    }

    public function getGiaoviec_danglam()
    {
        return $this->hasOne(KehoachGiaoviec::className(), ['id_ke_hoach' => 'id_ke_hoach', 'id_cong_doan' => 'id_cong_doan'])->andWhere(['qli_kehoach_giaoviec.trang_thai' => self::DANGLAM]);        
    }

    public function getGiaoviec_dahoanthanh()
    {
        return $this->hasOne(KehoachGiaoviec::className(), ['id_ke_hoach' => 'id_ke_hoach', 'id_cong_doan' => 'id_cong_doan'])->andWhere(['qli_kehoach_giaoviec.trang_thai' => self::HOANTHANH]);        
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKehoach()
    {
        return $this->hasOne(Kehoach::className(), ['kehoach_id' => 'id_ke_hoach']);
    }

    public function getDonhang()
    {
        // return $this->hasOne(Kehoach::className(), ['kehoach_id' => 'id_ke_hoach'])->viaTable('qli_orders', ['order_id' => 'id_don_hang']);
        // return $this->hasOne(Orders::className(), ['order_id' => 'id_don_hang'])->viaTable('qli_kehoach', ['kehoach_id' => 'id_ke_hoach','cong_doan_hien_tai' => 'id_cong_doan']);
        return $this->hasOne(Orders::className(), ['order_id' => 'id_don_hang'])->viaTable('qli_kehoach', ['kehoach_id' => 'id_ke_hoach']);
    }

}
