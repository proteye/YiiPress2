<?php

namespace app\modules\comment\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\comment\models\Comment;

/**
 * CommentSearch represents the model behind the search form about `app\modules\comment\models\Comment`.
 */
class CommentSearch extends Comment
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'parent_id', 'user_id', 'model_id', 'created_at', 'status', 'tree', 'lft', 'rgt', 'depth'], 'integer'],
            [['model', 'name', 'email', 'text', 'user_ip'], 'safe'],
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
        $query = Comment::find();

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
            'user_id' => $this->user_id,
            'model_id' => $this->model_id,
            'created_at' => $this->created_at,
            'status' => $this->status,
            'tree' => $this->tree,
            'lft' => $this->lft,
            'rgt' => $this->rgt,
            'depth' => $this->depth,
        ]);

        $query->andFilterWhere(['like', 'model', $this->model])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'user_ip', $this->user_ip]);

        return $dataProvider;
    }
}
