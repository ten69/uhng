<?php

namespace backend\modules\kehoach\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\kehoach\models\KehoachCongdoan;

/**
 * KehoachCongdoanSearch represents the model behind the search form about `backend\models\KehoachCongdoan`.
 */
class KehoachCongdoanSearch extends KehoachCongdoan
{
    /**
     * @inheritdoc
     */
    public $idsanpham;
    public $iddonhang;
    public $makehoach;
    public $nhanviengiao;
    public $ghichucongdoantruoc;

    public $thoigiannhan;
    public $thoigianhoanthanh;

    public function rules()
    {
        return [
            [['id_ke_hoach', 'id_cong_doan', 'trang_thai'], 'integer'],
            [['thoi_gian_bat_dau', 'thoi_gian_hoan_thanh', 'ghi_chu'], 'safe'],

            
            [['iddonhang','idsanpham','makehoach','ghichucongdoantruoc'], 'safe'],
            [['nhanviengiao'], 'integer'],

            [['thoigiannhan','thoigianhoanthanh'], 'safe'],
        ];
    }

   
    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $return = [];
        $this->load($params);

        if(empty($_GET['tinhtrang'])){
            $tinhtrang_loc = 'dang_lam';
        }else{
            $tinhtrang_loc = $_GET['tinhtrang'];
        }
        $query = KehoachCongdoan::find();

        $query->select(['qli_kehoach_congdoan.*', 'id_don_hang','id_san_pham','code','qli_kehoach_giaoviec.thoi_gian_nhan','qli_kehoach_giaoviec.thoi_gian_hoan_thanh as thoigianhoanthanh']);
        $query->joinWith('donhang');
        $query->joinWith('giaoviec');

        if(!empty($this->makehoach)) $query->andWhere(['like','code',$this->makehoach]);  
        if(!empty($this->iddonhang)) $query->andWhere(['like','id_don_hang',$this->iddonhang]);  
        if(!empty($this->idsanpham)) $query->andWhere(['like','id_san_pham',$this->idsanpham]);  
        

        if(!empty($this->thoi_gian_bat_dau)){
            $ngaybatdau = explode(' - ',$this->thoi_gian_bat_dau);
            if(!empty($ngaybatdau[0])) $query->andWhere(['>=','thoi_gian_bat_dau',$ngaybatdau[0].' 00:00:00']);
            if(!empty($ngaybatdau[1])) $query->andWhere(['<=','thoi_gian_bat_dau',$ngaybatdau[1].' 23:59:59']);
        }

        if(!empty($this->thoi_gian_hoan_thanh)){
            $ngayhoanthanh = explode(' - ',$this->thoi_gian_hoan_thanh);
            if(!empty($ngayhoanthanh[0])) $query->andWhere(['>=','thoi_gian_hoan_thanh',$ngayhoanthanh[0].' 00:00:00']);
            if(!empty($ngayhoanthanh[1])) $query->andWhere(['<=','thoi_gian_hoan_thanh',$ngayhoanthanh[1].' 23:59:59']);
        }
      

        if(!empty($this->thoigiannhan)){
            $thoigiannhan = explode(' - ',$this->thoigiannhan);
            if(!empty($thoigiannhan[0])) $query->andWhere(['>=','qli_kehoach_giaoviec.thoi_gian_nhan',$thoigiannhan[0].' 00:00:00']);
            if(!empty($thoigiannhan[1])) $query->andWhere(['<=','qli_kehoach_giaoviec.thoi_gian_nhan',$thoigiannhan[1].' 23:59:59']);
        }

        if(!empty($this->thoigianhoanthanh)){
            $thoigianhoanthanh = explode(' - ',$this->thoigianhoanthanh);
            if(!empty($thoigianhoanthanh[0])) $query->andWhere(['>=','qli_kehoach_giaoviec.thoi_gian_hoan_thanh',$thoigianhoanthanh[0].' 00:00:00']);
            if(!empty($thoigianhoanthanh[1])) $query->andWhere(['<=','qli_kehoach_giaoviec.thoi_gian_hoan_thanh',$thoigianhoanthanh[1].' 23:59:59']);
        }


        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            $return['dataProvider'] = $dataProvider;
            return $return;
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'id_ke_hoach' => $this->id_ke_hoach,
            'qli_kehoach_congdoan.id_cong_doan' => $this->id_cong_doan,
            // 'thoi_gian_bat_dau' => $this->thoi_gian_bat_dau,
            // 'thoi_gian_hoan_thanh' => $this->thoi_gian_hoan_thanh,
            // 'qli_kehoach_congdoan.trang_thai' => $this->trang_thai,
        ]);
        $query->andFilterWhere(['like', 'ghi_chu', $this->ghi_chu]);

        // if($this->trang_thai == KehoachCongdoan::CHUANHAN){




        //DEM CHUA NHAN
        $query_chuanhan = clone $query;
        $query_chuanhan_danglam = clone $query;
        $query_chuanhan_danglam->andWhere(['IN','qli_kehoach_congdoan.trang_thai',[KehoachCongdoan::DANGLAM,KehoachCongdoan::SUALOI]]);
        $query_chuanhan_danglam->select(['qli_kehoach_congdoan.id_ke_hoach'])->groupBy(['qli_kehoach_congdoan.id_ke_hoach']);
        $query_chuanhan->andWhere(['not in','kehoach_id',$query_chuanhan_danglam->column()]); //Loại bỏ những cái DANGLAM

        $query_chuanhan->andWhere(['qli_kehoach_congdoan.trang_thai' => KehoachCongdoan::CHUANHAN]);
        $query_chuanhan->groupBy(['id_ke_hoach']); 

        $query_chuanhan->andWhere(['qli_kehoach_giaoviec.id_nhan_vien' => Yii::$app->user->identity->user_id]);
        $so_chuanhan = $query_chuanhan->count();

        $return['so_chuanhan'] = $so_chuanhan;


        //DEM DANG LAM
        $query_danhan = clone $query;
        $query_danhan->andWhere(['IN','qli_kehoach_congdoan.trang_thai',[KehoachCongdoan::DANGLAM,KehoachCongdoan::SUALOI] ]);

        $query_danhan->andWhere(['qli_kehoach_giaoviec.id_nhan_vien' => Yii::$app->user->identity->user_id]);
        $so_danglam = $query_danhan->count();

        $return['so_danglam'] = $so_danglam;



        if($tinhtrang_loc == 'chua_nhan'){
            $danglam = clone $query;
            $danglam->andWhere(['IN','qli_kehoach_congdoan.trang_thai',[KehoachCongdoan::DANGLAM,KehoachCongdoan::SUALOI]]);
            $danglam->select(['qli_kehoach_congdoan.id_ke_hoach'])->groupBy(['qli_kehoach_congdoan.id_ke_hoach']);
            $query->andWhere(['not in','kehoach_id',$danglam->column()]); //Loại bỏ những cái DANGLAM


            $query->andWhere(['qli_kehoach_congdoan.trang_thai' => KehoachCongdoan::CHUANHAN]);
            $query->groupBy(['id_ke_hoach']); 
            
        }
        elseif($tinhtrang_loc == 'dang_lam'){
            $query->andWhere(['IN','qli_kehoach_congdoan.trang_thai',[KehoachCongdoan::DANGLAM,KehoachCongdoan::SUALOI] ]);
        }

        elseif($tinhtrang_loc == 'da_hoan_thanh'){
            $query->andWhere(['qli_kehoach_congdoan.trang_thai' => KehoachCongdoan::HOANTHANH]);
        }



        $query->andWhere(['qli_kehoach_giaoviec.id_nhan_vien' => Yii::$app->user->identity->user_id]);

        // if($tinhtrang_loc == 'da_nhan'){
        //     $query->andWhere(['!=','qli_kehoach_congdoan.trang_thai',KehoachCongdoan::CHUANHAN]);
        //     $query->orderBy(['id_cong_doan' => SORT_DESC]);    
        //     // $query->max('id_cong_doan');
        //     // $query->having('id_cong_doan >= tuyen');
        //     // $query->groupBy(['id_ke_hoach']);
        // }else{
        //     $query->andWhere(['qli_kehoach_congdoan.trang_thai' => KehoachCongdoan::CHUANHAN]);
        //     $query->orderBy(['id_cong_doan' => SORT_ASC]);
        //     // $query->min('id_cong_doan');
        //     // $query->having('id_cong_doan');
        //     // $query->having('id_ke_hoach');
        //     // $query->groupBy(['id_ke_hoach']);
        // }
        
        // echo '<pre>';
        // print_r($query->all());
        // echo '</pre>';
        // die;
        $return['dataProvider'] = $dataProvider;
        return $return;        
    }
}
