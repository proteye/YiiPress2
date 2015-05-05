<?php

namespace app\modules\category\models;

use Yii;
use app\modules\image\models\Image;
use app\modules\page\models\Page;
use app\modules\blog\models\Post;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use app\modules\core\components\behaviors\ImageUploadBehavior;

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
 * @property string $image_alt
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
    const STATUS_DRAFT = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETE = 2;

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
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            [['parent_id', 'created_at', 'updated_at', 'status'], 'integer'],
            [['alias', 'name'], 'required'],
            [['description'], 'string'],
            [['lang'], 'string', 'max' => 2],
            [['alias'], 'string', 'max' => 160],
            ['image', 'image', 'extensions' => 'jpg, jpeg, gif, png', 'skipOnEmpty' => true],
            [['name', 'image', 'image_alt'], 'string', 'max' => 255],
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
            'image_alt' => 'Атрибут alt',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
            'meta_title' => 'SEO Title',
            'meta_keywords' => 'SEO Keywords',
            'meta_description' => 'SEO Description',
            'status' => 'Статус',
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                ],
            ],
            'file' => [
                'class' => ImageUploadBehavior::className(),
                'attributeName' => 'image',
                'path' => Yii::$app->controller->module->uploadPath,
            ],
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
