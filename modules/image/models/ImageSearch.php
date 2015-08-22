<?php

namespace app\modules\image\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\image\models\Image;

/**
 * ImageSearch represents the model behind the search form about `app\modules\image\models\Image`.
 */
class ImageSearch extends Image
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'category_id', 'parent_id', 'created_at', 'updated_at', 'user_id', 'sort', 'type', 'status'], 'integer'],
            [['name', 'file', 'alt', 'description'], 'safe'],
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
        $query = Image::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['created_at' => SORT_DESC],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'category_id' => $this->category_id,
            'parent_id' => $this->parent_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user_id' => $this->user_id,
            'sort' => $this->sort,
            'type' => $this->type,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'file', $this->file])
            ->andFilterWhere(['like', 'alt', $this->alt])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
