<?php

namespace app\modules\image\models;

use Yii;

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
            [['category_id', 'parent_id', 'created_at', 'updated_at', 'user_id', 'sort', 'type', 'status'], 'integer'],
            [['name', 'file', 'created_at', 'updated_at'], 'required'],
            [['description'], 'string'],
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
            'category_id' => 'Category ID',
            'parent_id' => 'Parent ID',
            'name' => 'Name',
            'file' => 'File',
            'alt' => 'Alt',
            'description' => 'Description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'user_id' => 'User ID',
            'sort' => 'Sort',
            'type' => 'Type',
            'status' => 'Status',
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
