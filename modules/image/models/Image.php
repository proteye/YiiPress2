<?php

namespace app\modules\image\models;

use Yii;
use app\modules\category\models\Category;
use app\modules\user\models\User;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use app\modules\core\components\behaviors\ImageUploadBehavior;
use app\modules\core\components\behaviors\ParentTreeBehavior;

/**
 * This is the model class for table "{{%image}}".
 *
 * @property integer $id
 * @property integer $category_id
 * @property integer $parent_id
 * @property string $name
 * @property string $file
 * @property string $alt
 * @property string $description
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $sort
 * @property integer $type
 * @property integer $status
 *
 * @property Category $category
 * @property Image $parent
 * @property Image[] $images
 * @property User $user
 */
class Image extends \app\modules\core\models\CoreModel
{
    const STATUS_BLOCKED = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;

    const TYPE_SIMPLE = 0;
    const TYPE_PREVIEW = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%image}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['type', 'default', 'value' => self::TYPE_SIMPLE],
            ['sort', 'default', 'value' => 1],
            [['category_id', 'parent_id', 'created_at', 'updated_at', 'created_by', 'updated_by', 'sort', 'type', 'status'], 'integer'],
            [['name'], 'required'],
            [['description'], 'string'],
            ['file', 'image', 'extensions' => 'jpg, jpeg, gif, png', 'skipOnEmpty' => true],
            [['name', 'file', 'alt'], 'string', 'max' => 255],
            ['status', 'in', 'range' => array_keys(self::getStatusesArray())],
            ['type', 'in', 'range' => array_keys(self::getTypesArray())],
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
            'parent_id' => 'Родитель',
            'name' => 'Название',
            'file' => 'Файл',
            'alt' => 'Атрибут alt',
            'description' => 'Описание',
            'created_by' => 'Создал',
            'updated_by' => 'Обновил',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
            'sort' => 'Сортировка',
            'type' => 'Тип',
            'status' => 'Статус',
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        $module = Yii::$app->getModule('image');

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
                'attributeName' => 'file',
                'path' => $module->uploadPath,
            ],
            'blame' => [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
            'tree' => [
                'class' => ParentTreeBehavior::className(),
                'displayAttr' => 'name',
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
            self::STATUS_ACTIVE => 'Активен',
            self::STATUS_BLOCKED => 'Заблокирован',
            self::STATUS_DELETED => 'Удален',
        ];
    }

    /**
     * @return string
     */
    public function getTypeName()
    {
        $statuses = self::getTypesArray();
        return isset($statuses[$this->status]) ? $statuses[$this->status] : '';
    }

    /**
     * @return array
     */
    public static function getTypesArray()
    {
        return [
            self::TYPE_SIMPLE => 'Основной',
            self::TYPE_PREVIEW => 'Сниппет',
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
    public function getParent()
    {
        return $this->hasOne(Image::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImages()
    {
        return $this->hasMany(Image::className(), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }
}
