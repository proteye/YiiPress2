<?php

namespace app\modules\page\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\page\models\Page;

/**
 * PageSearch represents the model behind the search form about `app\modules\page\models\Page`.
 */
class PageSearch extends Page
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'parent_id', 'category_id', 'create_user_id', 'update_user_id', 'created_at', 'updated_at', 'sort', 'access_type', 'status'], 'integer'],
            [['lang', 'url', 'alias', 'title', 'content', 'layout', 'view', 'meta_title', 'meta_keywords', 'meta_description'], 'safe'],
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
        $query = Page::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'parent_id' => $this->parent_id,
            'category_id' => $this->category_id,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'sort' => $this->sort,
            'access_type' => $this->access_type,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'lang', $this->lang])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'alias', $this->alias])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'layout', $this->layout])
            ->andFilterWhere(['like', 'view', $this->view])
            ->andFilterWhere(['like', 'meta_title', $this->meta_title])
            ->andFilterWhere(['like', 'meta_keywords', $this->meta_keywords])
            ->andFilterWhere(['like', 'meta_description', $this->meta_description]);

        return $dataProvider;
    }
}
