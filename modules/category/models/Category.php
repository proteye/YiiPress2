<?php

namespace app\modules\category\models;

use Yii;

/**
 * This is the model class for table "{{%category}}".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $lang
 * @property string $alias
 * @property string $name
 * @property string $short_description
 * @property string $description
 * @property string $image
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $meta_title
 * @property string $meta_keywords
 * @property string $meta_description
 * @property integer $status
 *
 * @property Category $parent
 * @property Category[] $categories
 * @property Image[] $images
 * @property Page[] $pages
 * @property Post[] $posts
 */
class Category extends \app\modules\core\models\CoreModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'created_at', 'updated_at', 'status'], 'integer'],
            [['alias', 'name', 'created_at', 'updated_at'], 'required'],
            [['description'], 'string'],
            [['lang'], 'string', 'max' => 2],
            [['alias'], 'string', 'max' => 160],
            [['name', 'image'], 'string', 'max' => 255],
            [['short_description'], 'string', 'max' => 512],
            [['meta_title', 'meta_keywords', 'meta_description'], 'string', 'max' => 250],
            [['alias', 'lang'], 'unique', 'targetAttribute' => ['alias', 'lang'], 'message' => 'The combination of Lang and Alias has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => 'Родитель',
            'lang' => 'Язык',
            'alias' => 'Алиас',
            'name' => 'Название',
            'short_description' => 'Короткое описание',
            'description' => 'Описание',
            'image' => 'Изображение',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'meta_title' => 'SEO Title',
            'meta_keywords' => 'SEO Keywords',
            'meta_description' => 'SEO Description',
            'status' => 'Статус',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Category::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImages()
    {
        return $this->hasMany(Image::className(), ['category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPages()
    {
        return $this->hasMany(Page::className(), ['category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(Post::className(), ['category_id' => 'id']);
    }
}
