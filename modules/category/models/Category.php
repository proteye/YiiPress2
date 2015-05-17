<?php

namespace app\modules\category\models;

use Yii;
use app\modules\image\models\Image;
use app\modules\page\models\Page;
use app\modules\blog\models\Post;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use app\modules\core\components\behaviors\ImageUploadBehavior;
use app\modules\core\components\behaviors\ParentTreeBehavior;
use yii\behaviors\SluggableBehavior;
use app\modules\core\components\behaviors\CacheClearBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%category}}".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $lang
 * @property string $slug
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
    const STATUS_BLOCKED = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;

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
            [['slug', 'name'], 'required'],
            [['description'], 'string'],
            [['lang'], 'string', 'max' => 2],
            [['slug'], 'string', 'max' => 160],
            ['image', 'image', 'extensions' => 'jpg, jpeg, gif, png', 'skipOnEmpty' => true],
            [['name', 'image', 'image_alt'], 'string', 'max' => 255],
            [['short_description'], 'string', 'max' => 512],
            [['meta_title', 'meta_keywords', 'meta_description'], 'string', 'max' => 250],
            [['slug', 'lang'], 'unique', 'targetAttribute' => ['slug', 'lang'], 'message' => 'Такая комбинация Языка и Алиас уже существует..'],
            ['status', 'in', 'range' => array_keys(self::getStatusesArray())],
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
            'slug' => 'Алиас',
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
        $module = Yii::$app->getModule('category');

        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                ],
            ],
            'image' => [
                'class' => ImageUploadBehavior::className(),
                'attributeName' => 'image',
                'path' => $module->uploadPath,
            ],
            'tree' => [
                'class' => ParentTreeBehavior::className(),
                'displayAttr' => 'name',
                'status' => self::STATUS_ACTIVE,
            ],
            'slug' => [
                'class' => SluggableBehavior::className(),
                'attribute' => 'name',
                'slugAttribute' => 'slug',
            ],
            'cacheClear' => [
                'class' => CacheClearBehavior::className(),
                'modules' => ['category', 'blog'],
            ],
        ];
    }

    /**
     * @return string
     */
    public function getStatusName()
    {
        $statuses = self::getStatusesArray();
        return isset($statuses[$this->status]) ? $statuses[$this->status] : '';
    }

    /**
     * @return array
     */
    public static function getStatusesArray()
    {
        return [
            self::STATUS_ACTIVE => 'Активен',
            self::STATUS_BLOCKED => 'Заблокирован',
            self::STATUS_DELETED => 'Удален',
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAllPosts($limit = false)
    {
        $model = self::find()
            ->where(['id' => $this->id])
            ->orWhere(['parent_id' => $this->id])
            ->active()
            ->all()
        ;

        return Post::find()
            ->where(['category_id' => ArrayHelper::map($model, 'id', 'id')])
            ->active()
            ->orderBy('published_at DESC')
            ->limit($limit)
            ->all()
            ;
    }

    /**
     * @return array
     */
    public static function getItemsList()
    {
        $model = self::find()->where(['status' => self::STATUS_ACTIVE])->all();

        return ArrayHelper::map($model, 'id', 'name');
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        $pathsMap = Yii::$app->getModule('category')->getPathsMap();
        if (is_array($pathsMap))
            return Yii::$app->request->baseUrl . '/' . $pathsMap[$this->id];

        return false;
    }
}
