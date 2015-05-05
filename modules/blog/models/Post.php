<?php

namespace app\modules\blog\models;

use Yii;
use app\modules\user\models\User;
use app\modules\category\models\Category;

/**
 * This is the model class for table "{{%post}}".
 *
 * @property integer $id
 * @property integer $category_id
 * @property string $lang
 * @property string $url
 * @property string $title
 * @property string $quote
 * @property string $content
 * @property string $image
 * @property integer $create_user_id
 * @property integer $update_user_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $published_at
 * @property string $create_user_ip
 * @property string $link
 * @property string $meta_title
 * @property string $meta_keywords
 * @property string $meta_description
 * @property integer $access_type
 * @property integer $comment_status
 * @property integer $status
 *
 * @property Category $category
 * @property Category $user
 * @property PostTag[] $postTags
 * @property Tag[] $tags
 */
class Post extends \app\modules\core\models\CoreModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%post}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'create_user_id', 'update_user_id', 'created_at', 'updated_at', 'published_at', 'access_type', 'comment_status', 'status'], 'integer'],
            [['url', 'title', 'created_at', 'updated_at', 'published_at'], 'required'],
            [['content'], 'string'],
            [['lang'], 'string', 'max' => 2],
            [['url'], 'string', 'max' => 160],
            [['title', 'image', 'link'], 'string', 'max' => 255],
            [['quote'], 'string', 'max' => 512],
            [['create_user_ip'], 'string', 'max' => 20],
            [['meta_title', 'meta_keywords', 'meta_description'], 'string', 'max' => 250],
            [['url', 'lang'], 'unique', 'targetAttribute' => ['url', 'lang'], 'message' => 'The combination of Lang and Url has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Категория',
            'lang' => 'Язык',
            'url' => 'URL',
            'title' => 'Заголовок',
            'quote' => 'Цитата',
            'content' => 'Текст',
            'image' => 'Изображение',
            'create_user_id' => 'Создал',
            'update_user_id' => 'Обновил',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
            'published_at' => 'Дата',
            'create_user_ip' => 'Create User IP',
            'link' => 'Ссылка',
            'meta_title' => 'SEO Title',
            'meta_keywords' => 'SEO Keywords',
            'meta_description' => 'SEO Description',
            'access_type' => 'Доступ',
            'comment_status' => 'Комментарии',
            'status' => 'Статус',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'create_user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostTags()
    {
        return $this->hasMany(PostTag::className(), ['post_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTags()
    {
        return $this->hasMany(Tag::className(), ['id' => 'tag_id'])->viaTable('{{%post_tag}}', ['post_id' => 'id']);
    }
}
