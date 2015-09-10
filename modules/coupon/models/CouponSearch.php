<?php

namespace app\modules\coupon\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\coupon\models\Coupon;

/**
 * CouponSearch represents the model behind the search form about `app\modules\coupon\models\Coupon`.
 */
class CouponSearch extends Coupon
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'brand_id', 'adv_id', 'type_id', 'begin_dt', 'end_dt', 'created_by', 'updated_by', 'created_at', 'updated_at', 'view_count', 'recommended', 'status'], 'integer'],
            [['slug', 'name', 'short_name', 'description', 'promocode', 'promolink', 'gotolink', 'discount', 'meta_title', 'meta_keywords', 'meta_description', 'user_ip'], 'safe'],
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
        $query = Coupon::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 50,
            ],
            'sort' => [
                'defaultOrder' => ['updated_at' => SORT_DESC],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'brand_id' => $this->brand_id,
            'adv_id' => $this->adv_id,
            'type_id' => $this->type_id,
            'begin_dt' => $this->begin_dt,
            'end_dt' => $this->end_dt,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'recommended' => $this->recommended,
            'view_count' => $this->view_count,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'slug', $this->slug])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'short_name', $this->short_name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'promocode', $this->promocode])
            ->andFilterWhere(['like', 'promolink', $this->promolink])
            ->andFilterWhere(['like', 'gotolink', $this->gotolink])
            ->andFilterWhere(['like', 'discount', $this->discount])
            ->andFilterWhere(['like', 'meta_title', $this->meta_title])
            ->andFilterWhere(['like', 'meta_keywords', $this->meta_keywords])
            ->andFilterWhere(['like', 'meta_description', $this->meta_description])
            ->andFilterWhere(['like', 'user_ip', $this->user_ip]);

        return $dataProvider;
    }
}
