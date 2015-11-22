<?php

namespace app\modules\blog\models;

use Yii;
use app\modules\core\components\behaviors\SluggableBehavior;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%tag}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $slug
 *
 * @property PostTag[] $postTags
 * @property Post[] $posts
 */
class Tag extends \app\modules\core\models\CoreModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tag}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'slug'], 'required'],
            [['title'], 'string', 'max' => 255],
            [['slug'], 'string', 'max' => 160],
            [['slug'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Тег',
            'slug' => 'URL',
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'slug' => [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
                'slugAttribute' => 'slug',
                'forceUpdate' => false,
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostTags()
    {
        return $this->hasMany(PostTag::className(), ['tag_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(Post::className(), ['id' => 'post_id'])->viaTable('{{%post_tag}}', ['tag_id' => 'id']);
    }

    /**
     * @return array
     */
    public static function getItemsList()
    {
        $model = self::find()->all();

        return ArrayHelper::map($model, 'id', 'title');
    }

    public function getUrl()
    {
        $tagUrl = Yii::$app->getModule('blog')->tagUrl;

        return Yii::$app->request->baseUrl . '/' . $tagUrl . '/' . $this->slug;
    }

    public function getCount()
    {
        $count = (new Query())->from('{{%post_tag}}')->where(['tag_id' => $this->id])->count();
        return $count;
    }

    public function getFontSize()
    {
        $query = (new Query())->select('(select count(*) from {{%post_tag}} pt1 where pt1.tag_id = pt.tag_id) count')
            ->from('{{%post_tag}} pt')
            ->orderBy('count DESC')
            ->limit(1)
            ->one();
        $max = $query['count'];
        $percent = ($this->count / $max) * 100;
        $ratio = (22 - 8) / 100;
        $size = round($percent * $ratio + 8, 2);
        return $size;
    }
}
