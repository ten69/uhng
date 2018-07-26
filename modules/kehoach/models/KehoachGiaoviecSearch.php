<?php

namespace backend\modules\kehoach\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\kehoach\models\KehoachGiaoviec;

/**
 * KehoachGiaoviecSearch represents the model behind the search form about `backend\models\KehoachGiaoviec`.
 */
class KehoachGiaoviecSearch extends KehoachGiaoviec
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_ke_hoach', 'id_cong_doan', 'id_nhan_vien', 'trang_thai'], 'integer'],
            [['ghi_chu','thoi_gian_nhan', 'thoi_gian_hoan_thanh'], 'safe'],
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
        $query = KehoachGiaoviec::find();

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

        // grid filtering conditions
        $query->andFilterWhere([
            'id_ke_hoach' => $this->id_ke_hoach,
            'id_cong_doan' => $this->id_cong_doan,
            'id_nhan_vien' => $this->id_nhan_vien,            
            'trang_thai' => $this->trang_thai,
        ]);

        $query->andFilterWhere(['like', 'ghi_chu', $this->ghi_chu]);
        $query->andFilterWhere(['like', 'thoi_gian_nhan', $this->thoi_gian_nhan]);
        $query->andFilterWhere(['like', 'thoi_gian_hoan_thanh', $this->thoi_gian_hoan_thanh]);


        return $dataProvider;
    }
}
