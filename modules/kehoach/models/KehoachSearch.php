<?php

namespace backend\modules\kehoach\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\kehoach\models\Kehoach;

/**
 * KehoachSearch represents the model behind the search form about `backend\models\Kehoach`.
 */
class KehoachSearch extends Kehoach
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kehoach_id', 'muc_do', 'id_don_hang', 'id_san_pham', 'trang_thai'], 'integer'],
            [['code', 'ngay_san_xuat', 'ngay_giao_hang'], 'safe'],
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
        $query = Kehoach::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if(!empty($this->ngay_san_xuat)){
            $ngaysanxuat = explode(' - ',$this->ngay_san_xuat);

            if(!empty($ngaysanxuat[0])) $query->andWhere(['>=','ngay_san_xuat',$ngaysanxuat[0].' 00:00:00']);
            if(!empty($ngaysanxuat[1])) $query->andWhere(['<=','ngay_san_xuat',$ngaysanxuat[1].' 23:59:59']);
        }

        if(!empty($this->ngay_giao_hang)){
            $ngaygiaohang = explode(' - ',$this->ngay_giao_hang);
            if(!empty($ngaygiaohang[0])) $query->andWhere(['>=','ngay_giao_hang',$ngaygiaohang[0].' 00:00:00']);
            if(!empty($ngaygiaohang[1])) $query->andWhere(['<=','ngay_giao_hang',$ngaygiaohang[1].' 23:59:59']);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'kehoach_id' => $this->kehoach_id,
            'muc_do' => $this->muc_do,
            'id_don_hang' => $this->id_don_hang,
            'id_san_pham' => $this->id_san_pham,
            // 'ngay_san_xuat' => $this->ngay_san_xuat,
            // 'ngay_giao_hang' => $this->ngay_giao_hang,
            'trang_thai' => $this->trang_thai,
        ]);

        $query->andFilterWhere(['like', 'code', $this->code]);

        return $dataProvider;
    }
}
