<?php

namespace app\modules\coupon\models;

use Yii;
use app\modules\core\components\behaviors\FilterAttributeBehavior;
use app\modules\user\models\User;
use app\modules\category\models\Category;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%coupon}}".
 *
 * @property integer $id
 * @property integer $category_id
 * @property string $title
 * @property string $link
 * @property string $code
 * @property string $description
 * @property integer $type_id
 * @property string $value
 * @property integer $begin_dt
 * @property integer $end_dt
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $meta_title
 * @property string $meta_keywords
 * @property string $meta_description
 * @property string $user_ip
 * @property string $view_count
 * @property integer $recommended
 * @property integer $status
 *
 * @property User $updatedBy
 * @property Category $category
 * @property User $createdBy
 * @property CouponType $type
 */
class Coupon extends \app\modules\core\models\CoreModel
{
    const STATUS_BLOCKED = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;

    const RECOMMENDED_FALSE = 0;
    const RECOMMENDED_TRUE = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%coupon}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            [['begin_dt', 'end_dt'], 'default', 'value' => null],
            [['category_id', 'type_id', 'created_by', 'updated_by', 'created_at', 'updated_at', 'view_count', 'recommended', 'status'], 'integer'],
            [['title'], 'required'],
            [['description'], 'string'],
            [['title', 'link'], 'string', 'max' => 255],
            [['code'], 'string', 'max' => 160],
            [['value'], 'string', 'max' => 64],
            [['meta_title', 'meta_keywords', 'meta_description'], 'string', 'max' => 250],
            [['user_ip'], 'string', 'max' => 20]
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
            'title' => 'Заголовок',
            'link' => 'Партнерская ссылка',
            'code' => 'Промокод',
            'description' => 'Описание',
            'type_id' => 'Тип',
            'value' => 'Значение',
            'begin_dt' => 'Начало',
            'end_dt' => 'Завершение',
            'created_by' => 'Создан',
            'updated_by' => 'Изменен',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата изменения',
            'meta_title' => 'Meta Title',
            'meta_keywords' => 'Meta Keywords',
            'meta_description' => 'Meta Description',
            'user_ip' => 'IP адрес',
            'view_count' => 'Просмотров',
            'recommended' => 'Рекомендуем',
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
            'filter_attribute' => [
                'class' => FilterAttributeBehavior::className(),
                'dateAttribute' => 'begin_dt',
                'ipAttribute' => 'user_ip',
            ],
            'filter_attribute2' => [
                'class' => FilterAttributeBehavior::className(),
                'dateAttribute' => 'end_dt',
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
    public function getRecommendedName()
    {
        $statuses = self::getRecommendedsArray();
        return isset($statuses[$this->status]) ? $statuses[$this->status] : '';
    }

    /**
     * @return array
     */
    public static function getRecommendedsArray()
    {
        return [
            self::RECOMMENDED_FALSE => 'Нет',
            self::RECOMMENDED_TRUE => 'Да',
        ];
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
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(CouponType::className(), ['id' => 'type_id']);
    }
}
