<?php

namespace app\modules\blog\models;

use Yii;

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
}
