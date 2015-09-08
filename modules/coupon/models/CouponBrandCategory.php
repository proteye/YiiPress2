<?php

namespace app\modules\coupon\models;

use Yii;
use app\modules\category\models\Category;

/**
 * This is the model class for table "{{%coupon_brand_category}}".
 *
 * @property integer $brand_id
 * @property integer $category_id
 *
 * @property Category $category
 * @property CouponBrand $brand
 */
class CouponBrandCategory extends \app\modules\core\models\CoreModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%coupon_brand_category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['brand_id', 'category_id'], 'required'],
            [['brand_id', 'category_id'], 'integer'],
            [['brand_id', 'category_id'], 'unique', 'targetAttribute' => ['brand_id', 'category_id'], 'message' => 'Такая комбинация Брэнда и Категории уже существует..'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'brand_id' => 'Brand ID',
            'category_id' => 'Category ID',
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
    public function getBrand()
    {
        return $this->hasOne(CouponBrand::className(), ['id' => 'brand_id']);
    }
}
