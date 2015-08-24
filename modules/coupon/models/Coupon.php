<?php

namespace app\modules\coupon\models;

use Yii;

/**
 * This is the model class for table "{{%coupon}}".
 *
 * @property integer $id
 * @property integer $category_id
 * @property string $title
 * @property string $url
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
            [['category_id', 'type_id', 'begin_dt', 'end_dt', 'created_by', 'updated_by', 'created_at', 'updated_at', 'view_count', 'recommended', 'status'], 'integer'],
            [['title', 'url', 'created_at', 'updated_at'], 'required'],
            [['description'], 'string'],
            [['title', 'url'], 'string', 'max' => 255],
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
            'category_id' => 'Category ID',
            'title' => 'Title',
            'url' => 'Url',
            'description' => 'Description',
            'type_id' => 'Type ID',
            'value' => 'Value',
            'begin_dt' => 'Begin Dt',
            'end_dt' => 'End Dt',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'meta_title' => 'Meta Title',
            'meta_keywords' => 'Meta Keywords',
            'meta_description' => 'Meta Description',
            'user_ip' => 'User Ip',
            'view_count' => 'View Count',
            'recommended' => 'Recommended',
            'status' => 'Status',
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
