<?php

namespace app\modules\coupon\models;

use Yii;
use yii\helpers\ArrayHelper;
use app\modules\core\components\behaviors\SluggableBehavior;

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
            [['extra'], 'string', 'max' => 64],
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
            'name' => 'Название',
            'slug' => 'Алиас',
            'extra' => 'Дополнительно',
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        $module = Yii::$app->getModule('coupon');

        return [
            'slug' => [
                'class' => SluggableBehavior::className(),
                'attribute' => 'name',
                'slugAttribute' => 'slug',
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoupons()
    {
        return $this->hasMany(Coupon::className(), ['type_id' => 'id']);
    }

    /**
     * @return array
     */
    public static function getItemsList()
    {
        $model = self::find()->all();

        return ArrayHelper::map($model, 'id', 'name');
    }
}
