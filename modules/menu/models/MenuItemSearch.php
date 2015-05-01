<?php

namespace app\modules\menu\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\menu\models\MenuItem;

/**
 * MenuItemSearch represents the model behind the search form about `app\modules\menu\models\MenuItem`.
 */
class MenuItemSearch extends MenuItem
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'parent_id', 'menu_id', 'regular_link', 'condition_denial', 'sort', 'status'], 'integer'],
            [['title', 'href', 'class', 'title_attr', 'before_link', 'after_link', 'target', 'rel', 'condition_name'], 'safe'],
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
        $query = MenuItem::find();

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
            'menu_id' => $this->menu_id,
            'regular_link' => $this->regular_link,
            'condition_denial' => $this->condition_denial,
            'sort' => $this->sort,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'href', $this->href])
            ->andFilterWhere(['like', 'class', $this->class])
            ->andFilterWhere(['like', 'title_attr', $this->title_attr])
            ->andFilterWhere(['like', 'before_link', $this->before_link])
            ->andFilterWhere(['like', 'after_link', $this->after_link])
            ->andFilterWhere(['like', 'target', $this->target])
            ->andFilterWhere(['like', 'rel', $this->rel])
            ->andFilterWhere(['like', 'condition_name', $this->condition_name]);

        return $dataProvider;
    }
}
