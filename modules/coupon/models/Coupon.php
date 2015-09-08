<?php

namespace app\modules\coupon\models;

use Yii;
use app\modules\core\components\behaviors\FilterAttributeBehavior;
use app\modules\coupon\behaviors\CouponBehavior;
use app\modules\user\models\User;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%coupon}}".
 *
 * @property integer $id
 * @property integer $brand_id
 * @property integer $adv_id
 * @property string $name
 * @property string $short_name
 * @property string $description
 * @property string $promocode
 * @property string $promolink
 * @property string $gotolink
 * @property integer $type_id
 * @property string $discount
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
 * @property integer $recommended
 * @property string $view_count
 * @property integer $status
 *
 * @property CouponBrand $brand
 * @property CouponType $type
 * @property User $createdBy
 * @property User $updatedBy
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
            ['recommended', 'default', 'value' => self::RECOMMENDED_FALSE],
            ['view_count', 'default', 'value' => 0],
            [['begin_dt', 'end_dt'], 'default', 'value' => null],
            [['brand_id', 'adv_id', 'type_id', 'created_by', 'updated_by', 'created_at', 'updated_at', 'view_count', 'recommended', 'status'], 'integer'],
            [['name'], 'required'],
            [['description'], 'string'],
            [['short_name'], 'string', 'max' => 160],
            [['name', 'promolink', 'gotolink'], 'string', 'max' => 255],
            [['promocode', 'discount'], 'string', 'max' => 64],
            [['meta_title', 'meta_keywords', 'meta_description'], 'string', 'max' => 250],
            [['user_ip'], 'string', 'max' => 20],
            [['adv_id'], 'unique', 'targetAttribute' => ['adv_id'], 'message' => 'Такой Admitad ID уже существует..'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'brand_id' => 'Магазин (бренд)',
            'adv_id' => 'Admitad ID',
            'name' => 'Название',
            'short_name' => 'Короткое название',
            'description' => 'Описание',
            'promocode' => 'Промокод',
            'promolink' => 'Promo ссылка',
            'gotolink' => 'Goto ссылка',
            'type_id' => 'Тип',
            'discount' => 'Скидка',
            'begin_dt' => 'Начало',
            'end_dt' => 'Завершение',
            'created_by' => 'Создал',
            'updated_by' => 'Изменил',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата изменения',
            'meta_title' => 'Meta Title',
            'meta_keywords' => 'Meta Keywords',
            'meta_description' => 'Meta Description',
            'user_ip' => 'IP адрес',
            'recommended' => 'Рекомендуем',
            'view_count' => 'Просмотров',
            'status' => 'Статус',
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'coupon' => [
                'class' => CouponBehavior::className(),
            ],
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
                'ipAttribute' => 'user_ip',
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
    public function getBrand()
    {
        return $this->hasOne(CouponBrand::className(), ['id' => 'brand_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(CouponType::className(), ['id' => 'type_id']);
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
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }
}
