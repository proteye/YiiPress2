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
 *
 * @property Coupon[] $coupons
 * @property User $createdBy
 * @property User $updatedBy
 * @property CouponBrandCategory[] $brandCategories
 * @property Category[] $categories
 */
class CouponBrand extends \app\modules\core\models\CoreModel
{
    const STATUS_BLOCKED = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;

    const NAME_PREFIX = 'Промокоды';

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
            [['advcampaign_id', 'created_by', 'updated_by', 'created_at', 'updated_at', 'view_count', 'status'], 'integer'],
            [['slug', 'name'], 'required'],
            [['description'], 'string'],
            [['slug'], 'string', 'max' => 160],
            ['image', 'image', 'extensions' => 'jpg, jpeg, gif, png', 'skipOnEmpty' => true],
            [['name', 'image', 'image_alt', 'site', 'advlink', 'sec_name', 'title'], 'string', 'max' => 255],
            [['short_description'], 'string', 'max' => 512],
            [['meta_title', 'meta_keywords', 'meta_description'], 'string', 'max' => 250],
            [['advcampaign_id'], 'unique', 'targetAttribute' => ['advcampaign_id'], 'message' => 'Такой AdvСampaign ID уже существует..'],
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
            return $this->hasMany(Coupon::className(), ['brand_id' => 'id'])->where(['status' => Coupon::STATUS_ACTIVE])->all();

        return $this->hasMany(Coupon::className(), ['brand_id' => 'id']);
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
        $name = self::NAME_PREFIX . ' ' . $this->name . ' (' . $this->sec_name . ') на ' . strftime('%B', time()) . ' - ' . strftime('%B %Y', strtotime('now +1 month'));
        return $name;
    }
}
