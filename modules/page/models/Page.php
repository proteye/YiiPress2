<?php

namespace app\modules\page\models;

use Yii;
use app\modules\category\models\Category;
use app\modules\user\models\User;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use app\modules\core\components\behaviors\SluggableBehavior;
use app\modules\core\components\behaviors\ParentTreeBehavior;

/**
 * This is the model class for table "{{%page}}".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property integer $category_id
 * @property string $lang
 * @property string $slug
 * @property string $alias
 * @property string $title
 * @property string $content
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $layout
 * @property string $view
 * @property string $meta_title
 * @property string $meta_keywords
 * @property string $meta_description
 * @property integer $sort
 * @property integer $access_type
 * @property integer $status
 *
 * @property Category $category
 * @property User $user
 * @property Page $parent
 * @property Page[] $pages
 * @property User $updateUser
 */
class Page extends \app\modules\core\models\CoreModel
{
    const STATUS_DRAFT = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_WAIT = 2;
    const STATUS_DELETED = 3;

    const ACCESS_PUBLIC = 1;
    const ACCESS_PRIVATE = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%page}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_DRAFT],
            ['access_type', 'default', 'value' => self::ACCESS_PUBLIC],
            [['parent_id', 'category_id', 'created_by', 'updated_by', 'created_at', 'updated_at', 'sort', 'access_type', 'status'], 'integer'],
            [['slug', 'title', 'content'], 'required'],
            [['content'], 'string'],
            [['lang'], 'string', 'max' => 2],
            [['slug', 'alias'], 'string', 'max' => 160],
            [['title'], 'string', 'max' => 255],
            [['layout', 'view', 'meta_title', 'meta_keywords', 'meta_description'], 'string', 'max' => 250],
            [['slug', 'lang'], 'unique', 'targetAttribute' => ['slug', 'lang'], 'message' => 'Такая комбинация Языка и URL уже существует.'],
            ['access_type', 'in', 'range' => array_keys(self::getAccessesArray())],
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
            'category_id' => 'Категория',
            'lang' => 'Язык',
            'slug' => 'URL',
            'alias' => 'Алиас',
            'title' => 'Заголовок',
            'content' => 'Текст',
            'created_by' => 'Создал',
            'updated_by' => 'Обновил',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
            'layout' => 'Шаблон (layout)',
            'view' => 'Представление (view)',
            'meta_title' => 'SEO Title',
            'meta_keywords' => 'SEO Keywords',
            'meta_description' => 'SEO Description',
            'sort' => 'Сортировка',
            'access_type' => 'Доступ',
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
            'blame' => [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
            'slug' => [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
                'slugAttribute' => 'slug',
            ],
            'tree' => [
                'class' => ParentTreeBehavior::className(),
                'displayAttr' => 'title',
                'status' => self::STATUS_ACTIVE,
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
            self::STATUS_ACTIVE => 'Опубликован',
            self::STATUS_DRAFT => 'Черновик',
            self::STATUS_WAIT => 'В ожидании',
            self::STATUS_DELETED => 'Удален',
        ];
    }

    /**
     * @return string
     */
    public function getAccessName()
    {
        $accesses = self::getAccessesArray();
        return isset($accesses[$this->access_type]) ? $accesses[$this->access_type] : '';
    }

    /**
     * @return array
     */
    public static function getAccessesArray()
    {
        return [
            self::ACCESS_PUBLIC => 'Публичный',
            self::ACCESS_PRIVATE => 'Приватный',
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
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Page::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPages()
    {
        return $this->hasMany(Page::className(), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdateUser()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        $pathsMap = Yii::$app->getModule('page')->getPathsMap();
        if (is_array($pathsMap))
            return Yii::$app->request->baseUrl . '/' . $pathsMap[$this->id];

        return false;
    }
}
