<?php

namespace app\modules\coupon\models;

use Yii;

/**
 * This is the model class for table "{{%coupon_type}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property string $extra
 *
 * @property Coupon[] $coupons
 */
class CouponType extends \app\modules\core\models\CoreModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%coupon_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'slug'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['slug'], 'string', 'max' => 160],
            [['extra'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'slug' => 'Slug',
            'extra' => 'Extra',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoupons()
    {
        return $this->hasMany(Coupon::className(), ['type_id' => 'id']);
    }
}
