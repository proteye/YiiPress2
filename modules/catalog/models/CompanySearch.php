<?php

namespace app\modules\catalog\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\catalog\models\Company;

/**
 * CompanySearch represents the model behind the search form about `app\modules\catalog\models\Company`.
 */
class CompanySearch extends Company
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_by', 'updated_by', 'created_at', 'updated_at', 'published_at', 'comment_status', 'view_count', 'status'], 'integer'],
            [['slug', 'name', 'email', 'short_descr', 'description', 'logo', 'site', 'skype', 'icq', 'link_vk', 'link_fb', 'link_in', 'user_ip', 'meta_title', 'meta_keywords', 'meta_description'], 'safe'],
            [['rating'], 'number'],
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
        $query = Company::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'rating' => $this->rating,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'published_at' => $this->published_at,
            'comment_status' => $this->comment_status,
            'view_count' => $this->view_count,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'slug', $this->slug])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'short_descr', $this->short_descr])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'logo', $this->logo])
            ->andFilterWhere(['like', 'site', $this->site])
            ->andFilterWhere(['like', 'skype', $this->skype])
            ->andFilterWhere(['like', 'icq', $this->icq])
            ->andFilterWhere(['like', 'link_vk', $this->link_vk])
            ->andFilterWhere(['like', 'link_fb', $this->link_fb])
            ->andFilterWhere(['like', 'link_in', $this->link_in])
            ->andFilterWhere(['like', 'user_ip', $this->user_ip])
            ->andFilterWhere(['like', 'meta_title', $this->meta_title])
            ->andFilterWhere(['like', 'meta_keywords', $this->meta_keywords])
            ->andFilterWhere(['like', 'meta_description', $this->meta_description]);

        return $dataProvider;
    }
}
