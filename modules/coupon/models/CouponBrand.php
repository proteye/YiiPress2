<?php

namespace app\modules\coupon\models;

use Yii;
use yii\helpers\ArrayHelper;
use app\modules\category\models\Category;
use app\modules\user\models\User;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use app\modules\core\components\behaviors\SluggableBehavior;
use app\modules\core\components\behaviors\ImageUploadBehavior;
use yii\helpers\Url;
use app\modules\core\helpers\TextHelper;
use yii\db\Query;

/**
 * This is the model class for table "{{%coupon_brand}}".
 *
 * @property integer $id
 * @property string $advcampaign_id
 * @property string $slug
 * @property string $name
 * @property string $sec_name
 * @property string $short_description
 * @property string $description
 * @property string $image
 * @property string $image_alt
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $title
 * @property string $meta_title
 * @property string $meta_keywords
 * @property string $meta_description
 * @property string $site
 * @property string $advlink
 * @property string $view_count
 * @property integer $status
 * @property integer $offer_id
 *
 * @property Coupon[] $coupons
 * @property User $createdBy
 * @property User $updatedBy
 * @property CouponBrandCategory[] $brandCategories
 * @property Category[] $categories
 * @property Coupon[] $activeCoupons
 * @property Coupon[] $expiredCoupons
 * @property CouponBrand[] $topBrands
 * @property CouponBrand[] $likeBrands
 * @property integer $couponsCount
 * @property string $gotolink
 * @property string $golink
 * @property string $filteredName
 * @property string $metaTitle
 * @property string $metaKeywords
 * @property string $metaDescription
 */
class CouponBrand extends \app\modules\core\models\CoreModel
{
    const STATUS_BLOCKED = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;

    const PROMONAME_PREFIX = 'Промокоды';
    const M_TITLE = 'Промокод';
    const M_KEYWORDS = 'промокод купон скидка акция дисконт бесплатно доставка';
    const M_DESCRIPTION_BEG = 'Действующие промокоды';
    const M_DESCRIPTION_END = '• Максимум скидки на сегодня • Ежедневное обновление купонов, кодов и акций! ✓ 100% бесплатно!';

    /**
     * @var
     * @return array
     */
    private $_categories;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%coupon_brand}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['categories', 'default', 'value' => []],
            [['advcampaign_id', 'created_by', 'updated_by', 'created_at', 'updated_at', 'view_count', 'status', 'offer_id'], 'integer'],
            [['slug', 'name'], 'required'],
            [['description'], 'string'],
            [['slug'], 'string', 'max' => 160],
            ['image', 'image', 'extensions' => 'jpg, jpeg, gif, png', 'skipOnEmpty' => true],
            [['name', 'image', 'image_alt', 'site', 'advlink', 'sec_name', 'title'], 'string', 'max' => 255],
            [['short_description'], 'string', 'max' => 1024],
            [['meta_title', 'meta_keywords', 'meta_description'], 'string', 'max' => 250],
            // [['advcampaign_id'], 'unique', 'targetAttribute' => ['advcampaign_id'], 'message' => 'Такой AdvСampaign ID уже существует..'],
            // [['offer_id'], 'unique', 'targetAttribute' => ['offer_id'], 'message' => 'Такой Offer ID уже существует..'],
            ['categories', 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'categories' => 'Категории',
            'advcampaign_id' => 'AdvСampaign ID',
            'slug' => 'Алиас',
            'name' => 'Название',
            'sec_name' => 'Название #2',
            'short_description' => 'Краткое описание',
            'description' => 'Описание',
            'image' => 'Логотип',
            'image_alt' => 'Атрибут alt',
            'created_by' => 'Создал',
            'updated_by' => 'Изменил',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата изменения',
            'title' => 'Заголовок H1',
            'meta_title' => 'Meta Title',
            'meta_keywords' => 'Meta Keywords',
            'meta_description' => 'Meta Description',
            'site' => 'Сайт (ссылка)',
            'advlink' => 'Партнерская ссылка',
            'view_count' => 'Просмотров',
            'status' => 'Статус',
            'offer_id' => 'Offer ID',
        ];
    }

    /**
     * Init post tags
     */
    public function afterFind()
    {
        $this->_categories = ArrayHelper::map($this->brandCategories, 'slug', 'id');

        parent::afterFind();
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        $this->saveCategories();

        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        $module = Yii::$app->getModule('coupon');

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
                'attribute' => 'name',
                'slugAttribute' => 'slug',
            ],
            'image' => [
                'class' => ImageUploadBehavior::className(),
                'attributeName' => 'image',
                'path' => $module->uploadPath,
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
    public function getCoupons($only_active = false)
    {
        if ($only_active)
            return $this->hasMany(Coupon::className(), ['brand_id' => 'id'])
                ->where(['status' => Coupon::STATUS_ACTIVE])
                ->all();

        return $this->hasMany(Coupon::className(), ['brand_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActiveCoupons()
    {
        return $this->hasMany(Coupon::className(), ['brand_id' => 'id'])
            ->where(['>', 'end_dt', time()])
            ->andWhere(['status' => Coupon::STATUS_ACTIVE])
            ->orderBy('created_at DESC')
            ->all();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExpiredCoupons()
    {
        return $this->hasMany(Coupon::className(), ['brand_id' => 'id'])
            ->where(['<', 'end_dt', time()])
            ->andWhere(['status' => Coupon::STATUS_ACTIVE])
            ->orderBy('created_at DESC')
            ->all();
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

    /**
     * @return array
     */
    public static function getItemsList()
    {
        $model = self::find()->where(['status' => self::STATUS_ACTIVE])->all();

        return ArrayHelper::map($model, 'id', 'name');
    }

    /**
     * @return array
     */
    public static function getSearchList()
    {
        $model = self::find()->where(['status' => self::STATUS_ACTIVE])->all();
        foreach ($model as $brand) {
            $result[] = ['title' => $brand->name, 'sec_name' => $brand->sec_name, 'image' => $brand->imageUrl, 'url' => $brand->url];
        }

        return $result;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrandCategories()
    {
        return $this->hasMany(Category::className(), ['id' => 'category_id'])->viaTable('{{%coupon_brand_category}}', ['brand_id' => 'id']);
    }

    /**
     * @return array
     */
    public function getCategories()
    {
        return $this->_categories;
    }

    /**
     * @param $value
     */
    public function setCategories($value)
    {
        $this->_categories = $value;
    }

    /**
     * Remove all categories
     */
    public function removeCategories()
    {
        CouponBrandCategory::deleteAll(['brand_id' => $this->id]);
    }

    /**
     * Add categories to brand
     */
    public function saveCategories()
    {
        $this->removeCategories();

        if (!empty($this->categories)) {
            foreach ($this->categories as $val) {
                if (is_numeric($val)) {
                    $brand_category = new CouponBrandCategory();
                    $brand_category->brand_id = $this->id;
                    $brand_category->category_id = $val;
                    $brand_category->save();
                }
            }
        }

        $this->_categories = ArrayHelper::map($this->brandCategories, 'slug', 'id');
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        if ($this->slug != null)
            return Yii::$app->request->baseUrl . '/coupon/' . $this->slug;

        return false;
    }

    /**
     * @return string
     */
    public function getPromoName()
    {
        $name = self::PROMONAME_PREFIX . ' ' . $this->name;
        $name .= $this->sec_name ? ' (' . $this->sec_name . ')' : null;
        $name .= ' на ' . strftime('%B', time()) . ' - ' . strftime('%B %Y', strtotime('now +1 month'));
        return $name;
    }

    /**
     * @return int|string
     */
    public function getCouponsCount()
    {
        $count = Coupon::find()
            ->where(['brand_id' => $this->id])
            ->andWhere(['>', 'end_dt', time()])
            ->active()
            ->count();

        return $count;
    }
    
    /**
     * @return string
     */
    public function getGotolink()
    {
        $gotolink = null;
        if ($this->advlink != null) {
            $gotolink = $this->advlink;
        } else {
            $coupon = Coupon::find()
                ->where(['brand_id' => $this->id])
                ->andWhere(['>', 'end_dt', time()])
                ->active()
                ->one();
            if ($coupon) {
                $gotolink = $coupon->gotolink;
            }
        }

        return $gotolink;
    }

    /**
     * @return string
     */
    public function getGolink()
    {
        $golink = null;
        if ($this->gotolink != null) {
            $golink = Url::to(['coupon-frontend/go', 'id' => $this->slug]);
        }
        return $golink;
    }

    /**
     * @param int $limit
     * @param null $brand_id
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getTopBrands($limit = 15, $brand_id = null)
    {
        if ($brand_id == null) {
            $model = (new Query())
                ->select(['b.*', 'SUM(IF(c.end_dt > unix_timestamp(), 1, 0)) cnt'])
                ->from('{{%coupon_brand}} b')
                ->leftJoin('{{%coupon}} c', 'c.brand_id = b.id')
                ->where(['b.status' => self::STATUS_ACTIVE])
                ->groupBy('b.id')
                ->having('cnt > 0')
                ->orderBy(['b.view_count' => SORT_DESC])
                ->limit($limit)
                ->all();
        } else {
            $model = self::find()
                ->where(['!=', 'id', $brand_id])
                ->active()
                ->limit($limit)
                ->orderBy(['view_count' => SORT_DESC])
                ->all();
        }

        return $model;
    }

    /**
     * @param int $limit
     * @param null $brand_id
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getLastBrands($limit = 10, $brand_id = null)
    {
        if ($brand_id == null) {
            $model = (new Query())
                ->select(['b.*', 'SUM(IF(c.end_dt > unix_timestamp(), 1, 0)) cnt'])
                ->from('{{%coupon_brand}} b')
                ->leftJoin('{{%coupon}} c', 'c.brand_id = b.id')
                ->where(['b.status' => self::STATUS_ACTIVE])
                ->groupBy('b.id')
                ->having('cnt > 0')
                ->orderBy(['b.created_at' => SORT_DESC])
                ->limit($limit)
                ->all();
        } else {
            $model = self::find()
                ->where(['!=', 'id', $brand_id])
                ->active()
                ->limit($limit)
                ->orderBy(['created_at' => SORT_DESC])
                ->all();
        }

        return $model;
    }

    /**
     * @param $brand_id
     * @param int $limit
     * @return mixed
     */
    public static function getLikeBrands($brand_id, $limit = 15)
    {
        $model = null;
        $brand = self::findOne($brand_id);

        if ($brand->categories) {
            $brandCategory = CouponBrandCategory::find()
                ->select('brand_id')
                ->distinct()
                ->where(['category_id' => $brand->categories])
                ->andWhere(['!=', 'brand_id', $brand_id])
                ->all();
            $brand_ids = ArrayHelper::map($brandCategory, 'brand_id', 'brand_id');
            $model = self::find()
                ->where(['id' => $brand_ids])
                ->active()
                ->limit($limit)
                ->orderBy(['view_count' => SORT_DESC])
                ->all();
        }

        return $model;
    }

    /**
     * @return string
     */
    public function getFilteredName()
    {
        return strpos($this->name, '.') ? substr($this->name, 0, strpos($this->name, '.')) : $this->name;

    }

    /**
     * @return string
     */
    public function getMetaTitle()
    {
        $title = self::M_TITLE . ' ' . $this->filteredName;
        return $this->meta_title ? $this->meta_title : $title;

    }

    /**
     * @return string
     */
    public function getMetaKeywords()
    {
        $keywords = $this->filteredName . ' ' . $this->sec_name . ' ' . self::M_KEYWORDS . ' ' . strftime('%B', time()) . ' ' . strftime('%B %Y', strtotime('now +1 month'));
        return $this->meta_keywords ? $this->meta_keywords : mb_strtolower($keywords, 'UTF-8');
    }

    /**
     * @return string
     */
    public function getMetaDescription()
    {
        $description = self::M_DESCRIPTION_BEG . ' ' . $this->filteredName . ' на ' . strftime('%B', time()) . '-' . strftime('%B %Y', strtotime('now +1 month')) . ' ' . self::M_DESCRIPTION_END;
        return $this->meta_description ? $this->meta_description : $description;
    }

    /**
     * @return array
     */
    public static function getAlphabetBrands()
    {
        $result = [];
        foreach (TextHelper::getAlphabetArray() as $chr) {
            $rows = (new Query())
                ->select(['name', 'sec_name', 'slug'])
                ->from('{{%coupon_brand}}')
                ->where(['like', 'name', $chr . '%', false])
                ->all();
            foreach ($rows as $row) {
                if (is_numeric($chr)) {
                    $result['0-9'][] = ['name' => $row['name'], 'sec_name' => $row['sec_name'], 'slug' => $row['slug']];
                } else {
                    $result[$chr][] = ['name' => $row['name'], 'sec_name' => $row['sec_name'], 'slug' => $row['slug']];
                }
            }
        }

        return $result;
    }
}
