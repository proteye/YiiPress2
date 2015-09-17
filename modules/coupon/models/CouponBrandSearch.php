<?php

namespace app\modules\coupon\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\coupon\models\CouponBrand;

/**
 * CouponBrandSearch represents the model behind the search form about `app\modules\coupon\models\CouponBrand`.
 */
class CouponBrandSearch extends CouponBrand
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'advcampaign_id', 'created_by', 'updated_by', 'created_at', 'updated_at', 'view_count', 'status'], 'integer'],
            [['slug', 'name', 'sec_name', 'short_description', 'description', 'image', 'image_alt', 'title', 'meta_title', 'meta_keywords', 'meta_description', 'site', 'advlink'], 'safe'],
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
        $query = CouponBrand::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 50,
            ],
            'sort' => [
                'defaultOrder' => ['name' => SORT_ASC],
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
            'advcampaign_id' => $this->advcampaign_id,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'view_count' => $this->view_count,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'slug', $this->slug])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'sec_name', $this->sec_name])
            ->andFilterWhere(['like', 'short_description', $this->short_description])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'image_alt', $this->image_alt])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'meta_title', $this->meta_title])
            ->andFilterWhere(['like', 'meta_keywords', $this->meta_keywords])
            ->andFilterWhere(['like', 'meta_description', $this->meta_description])
            ->andFilterWhere(['like', 'site', $this->site])
            ->andFilterWhere(['like', 'advlink', $this->advlink]);

        return $dataProvider;
    }
}
