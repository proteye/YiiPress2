<?php

namespace app\modules\coupon\models;

use Yii;
use app\modules\core\components\behaviors\FilterAttributeBehavior;
use app\modules\coupon\behaviors\CouponBehavior;
use app\modules\user\models\User;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use app\modules\core\components\behaviors\SluggableBehavior;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%coupon}}".
 *
 * @property integer $id
 * @property integer $brand_id
 * @property integer $adv_id
 * @property string $slug
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
 *
 * @property string $filteredDescription
 * @property string $metaDescription
 * @property string $anchorName
 * @property string $url
 * @property string $golink
 * @property Coupon[] $likeCoupons
 */
class Coupon extends \app\modules\core\models\CoreModel
{
    const SLUG_PREFIX = 'promokod';
    const ANCHOR_PREFIX = 'code_';
    const CSV_FILE = 'file_csv.csv';
    const LOG_PATH = 'coupon_import_csv.log';

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
            [['slug', 'name'], 'required'],
            [['slug'], 'string', 'max' => 160],
            [['description'], 'string'],
            [['short_name'], 'string', 'max' => 160],
            [['name', 'promolink', 'gotolink'], 'string', 'max' => 255],
            [['promocode', 'discount'], 'string', 'max' => 64],
            [['meta_title', 'meta_keywords', 'meta_description'], 'string', 'max' => 250],
            [['user_ip'], 'string', 'max' => 20],
            [['adv_id'], 'unique', 'targetAttribute' => ['adv_id'], 'message' => 'Такой Admitad ID уже существует..'],
            [['slug'], 'unique', 'targetAttribute' => ['slug'], 'message' => 'Такой Алиас уже существует..'],
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
            'slug' => 'Алиас',
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
            'slug' => [
                'class' => SluggableBehavior::className(),
                'attribute' => 'name',
                'slugAttribute' => 'slug',
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

    /**
     * @return string
     */
    public function getUrl()
    {
        if ($this->slug != null)
            return Yii::$app->request->baseUrl . '/coupon/' . $this->brand->slug . '/' . $this->slug;

        return false;
    }

    /**
     * @return string
     */
    public function getGolink()
    {
        return Url::to(['coupon-frontend/go', 'id' => $this->id]);
    }

    /**
     * @return string
     */
    public function getFilteredDescription()
    {
        $str = preg_replace('/\s?Ввод.+не требуется.*?[\.\!\?]/i', '', $this->description);
        return preg_replace('/\s?Без.+кода.*?[\.\!\?]/i', '', $str);
    }

    /**
     * @return string
     */
    public function getMetaDescription()
    {
        $filtered = $this->filteredDescription;
        preg_match('/.{1,75}.+?\s/ius', $filtered, $descr);
        $descr = isset($descr[0]) ? trim(trim($descr[0]), ' \t.,!?') : $filtered;
        if ($descr != null && $descr != '') {
            $str = $descr . '. В интернет магазине проходит акция - ' . $this->name;
        } else {
            $str = 'В интернет магазине проходит акция - ' . $this->name;
        }
        preg_match('/.{1,155}.+?[\s\%\!\?\.\,]/ius', $str, $result);
        $result = isset($result[0]) ? trim($result[0], ' \t.,!?') . '.' : $str . '.';
        return $result;
    }

    /**
     * @return string
     */
    public function getAnchorName()
    {
        return self::ANCHOR_PREFIX . $this->id;
    }

    /**
     * @param $brand_id
     * @param int $limit
     * @return null
     */
    public static function getLikeCoupons($brand_id, $limit = 15)
    {
        $model = null;
        $brands = CouponBrand::getLikeBrands($brand_id, null);

        if ($brands) {
            $brand_ids = ArrayHelper::map($brands, 'id', 'id');
            $model = self::find()
                ->where(['brand_id' => $brand_ids])
                ->andWhere(['>', 'end_dt', time()])
                ->active()
                ->limit($limit)
                ->orderBy(['view_count' => SORT_DESC])
                ->all();
        }

        return $model;
    }
}
