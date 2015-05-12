<?php

namespace app\modules\image\models;

use Yii;
use app\modules\category\models\Category;
use app\modules\user\models\User;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use app\modules\core\components\behaviors\ImageUploadBehavior;

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
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $user_id
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
            [['category_id', 'parent_id', 'created_at', 'updated_at', 'user_id', 'sort', 'type', 'status'], 'integer'],
            [['name'], 'required'],
            [['description'], 'string'],
            ['file', 'image', 'extensions' => 'jpg, jpeg, gif, png', 'skipOnEmpty' => true],
            [['name', 'file', 'alt'], 'string', 'max' => 255]
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
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
            'user_id' => 'Пользователь',
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
                'createdByAttribute' => 'user_id',
                'updatedByAttribute' => 'user_id',
            ],
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
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
