<?php

namespace app\modules\blog\models;

use Yii;
use app\modules\user\models\User;
use app\modules\category\models\Category;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use app\modules\core\components\behaviors\SluggableBehavior;
use app\modules\core\components\behaviors\CacheClearBehavior;
use app\modules\core\components\behaviors\FilterAttributeBehavior;
use app\modules\core\components\behaviors\RpcPingBehavior;
use app\modules\core\components\behaviors\ImageUploadBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%post}}".
 *
 * @property integer $id
 * @property integer $category_id
 * @property string $lang
 * @property string $slug
 * @property string $title
 * @property string $quote
 * @property string $content
 * @property string $image
 * @property string $image_alt
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $published_at
 * @property string $user_ip
 * @property string $link
 * @property string $meta_title
 * @property string $meta_keywords
 * @property string $meta_description
 * @property integer $access_type
 * @property integer $comment_status
 * @property integer $status
 * @property integer $view_count
 *
 * @property Category $category
 * @property Category $user
 * @property PostTag[] $postTags
 * @property Tag[] $tags
 */
class Post extends \app\modules\core\models\CoreModel
{
    const STATUS_DRAFT = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_WAIT = 2;
    const STATUS_DELETED = 3;

    const ACCESS_PUBLIC = 1;
    const ACCESS_PRIVATE = 2;

    const COMMENT_YES = 1;
    const COMMENT_NO = 0;

    /**
     * @var
     * @return array
     */
    private $_tags;

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
            ['status', 'default', 'value' => self::STATUS_DRAFT],
            ['published_at', 'default', 'value' => null],
            ['comment_status', 'default', 'value' => self::COMMENT_YES],
            ['access_type', 'default', 'value' => self::ACCESS_PUBLIC],
            ['tags', 'default', 'value' => []],
            ['view_count', 'default', 'value' => 0],
            [['category_id', 'created_by', 'updated_by', 'created_at', 'updated_at', 'access_type', 'comment_status', 'status'], 'integer'],
            [['title', 'slug', 'published_at'], 'required'],
            [['content'], 'string'],
            [['lang'], 'string', 'max' => 2],
            [['slug'], 'string', 'max' => 160],
            ['image', 'image', 'extensions' => 'jpg, jpeg, gif, png', 'skipOnEmpty' => true],
            [['title', 'image', 'image_alt', 'link'], 'string', 'max' => 255],
            [['quote'], 'string', 'max' => 512],
            [['user_ip'], 'string', 'max' => 20],
            [['meta_title', 'meta_keywords', 'meta_description'], 'string', 'max' => 250],
            [['slug', 'lang'], 'unique', 'targetAttribute' => ['slug', 'lang'], 'message' => 'Такая комбинация Языка и URL уже существует.'],
            ['access_type', 'in', 'range' => array_keys(self::getAccessesArray())],
            ['comment_status', 'in', 'range' => array_keys(self::getCommentStatusesArray())],
            ['status', 'in', 'range' => array_keys(self::getStatusesArray())],
            ['tags', 'safe'],
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
            'slug' => 'URL',
            'title' => 'Заголовок',
            'quote' => 'Цитата',
            'content' => 'Текст',
            'image' => 'Изображение',
            'image_alt' => 'Атрибут alt',
            'created_by' => 'Создал',
            'updated_by' => 'Обновил',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
            'published_at' => 'Дата',
            'user_ip' => 'Create User IP',
            'link' => 'Ссылка',
            'meta_title' => 'SEO Title',
            'meta_keywords' => 'SEO Keywords',
            'meta_description' => 'SEO Description',
            'access_type' => 'Доступ',
            'comment_status' => 'Комментарии',
            'status' => 'Статус',
            'tags' => 'Теги',
            'view_count' => 'Просмотров',
        ];
    }

    /**
     * Init post tags
     */
    public function afterFind()
    {
        $this->_tags = ArrayHelper::map($this->postTags, 'slug', 'id');

        parent::afterFind();
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        $this->saveTags();

        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        $module = Yii::$app->getModule('blog');

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
            'filter_attribute' => [
                'class' => FilterAttributeBehavior::className(),
                'dateAttribute' => 'published_at',
                'ipAttribute' => 'user_ip',
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
                'transliterator' => Yii::$app->getModule('core')->transliterator,
                'forceUpdate' => false,
            ],
            'cacheClear' => [
                'class' => CacheClearBehavior::className(),
                'modules' => ['blog'],
            ],
            'pingPage' => [
                'class' => RpcPingBehavior::className(),
                'titleAttribute' => 'title',
                'urlAttribute' => 'url',
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
     * @return string
     */
    public function getCommentStatusName()
    {
        $statuses = self::getCommentStatusesArray();
        return isset($statuses[$this->access_type]) ? $statuses[$this->access_type] : '';
    }

    /**
     * @return array
     */
    public static function getCommentStatusesArray()
    {
        return [
            self::COMMENT_YES => 'Разрешены',
            self::COMMENT_NO => 'Запрещены',
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
    public function getPostTags()
    {
        return $this->hasMany(Tag::className(), ['id' => 'tag_id'])->viaTable('{{%post_tag}}', ['post_id' => 'id']);
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->_tags;
    }

    /**
     * @param $value
     */
    public function setTags($value)
    {
        $this->_tags = $value;
    }

    /**
     * Remove all tags
     */
    public function removeTags()
    {
        PostTag::deleteAll(['post_id' => $this->id]);
    }

    /**
     * Add tags to post
     */
    public function saveTags()
    {
        $this->removeTags();

        if (!empty($this->tags)) {
            foreach ($this->tags as $val) {
                if (is_numeric($val)) {
                    $post_tag = new PostTag();
                    $post_tag->post_id = $this->id;
                    $post_tag->tag_id = $val;
                    $post_tag->save();
                } else {
                    $tag = new Tag();
                    $tag->title = $val;
                    if ($tag->save()) {
                        $post_tag = new PostTag();
                        $post_tag->post_id = $this->id;
                        $post_tag->tag_id = $tag->id;
                        $post_tag->save();
                    }
                }
            }
        }

        $this->_tags = ArrayHelper::map($this->postTags, 'slug', 'id');
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        $pathsMap = Yii::$app->getModule('blog')->getPathsMap();
        if (is_array($pathsMap))
            return Yii::$app->request->baseUrl . '/' . $pathsMap[$this->id];

        return false;
    }

    /**
     * @return string
     */
    public function getShortQuote($num = 145)
    {
        $quote = mb_substr($this->quote, 0, $num, 'utf-8');
        $quote .= (mb_strlen($this->quote, 'utf-8') > $num) ? '...' : '';
        return $quote;
    }
}
