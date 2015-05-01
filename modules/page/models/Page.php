<?php

namespace app\modules\page\models;

use Yii;

/**
 * This is the model class for table "{{%page}}".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property integer $category_id
 * @property string $lang
 * @property string $url
 * @property string $alias
 * @property string $title
 * @property string $content
 * @property integer $create_user_id
 * @property integer $update_user_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $layout
 * @property string $view
 * @property string $meta_title
 * @property string $meta_keywords
 * @property string $meta_description
 * @property integer $sort
 * @property integer $access_type
 * @property integer $status
 *
 * @property Category $category
 * @property User $createUser
 * @property Page $parent
 * @property Page[] $pages
 * @property User $updateUser
 */
class Page extends \app\modules\core\models\CoreModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%page}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'category_id', 'create_user_id', 'update_user_id', 'created_at', 'updated_at', 'sort', 'access_type', 'status'], 'integer'],
            [['url', 'title', 'content', 'created_at', 'updated_at'], 'required'],
            [['content'], 'string'],
            [['lang'], 'string', 'max' => 2],
            [['url', 'alias'], 'string', 'max' => 160],
            [['title'], 'string', 'max' => 255],
            [['layout', 'view', 'meta_title', 'meta_keywords', 'meta_description'], 'string', 'max' => 250],
            [['url', 'lang'], 'unique', 'targetAttribute' => ['url', 'lang'], 'message' => 'The combination of Lang and Url has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => 'Parent ID',
            'category_id' => 'Category ID',
            'lang' => 'Lang',
            'url' => 'Url',
            'alias' => 'Alias',
            'title' => 'Title',
            'content' => 'Content',
            'create_user_id' => 'Create User ID',
            'update_user_id' => 'Update User ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'layout' => 'Layout',
            'view' => 'View',
            'meta_title' => 'Meta Title',
            'meta_keywords' => 'Meta Keywords',
            'meta_description' => 'Meta Description',
            'sort' => 'Sort',
            'access_type' => 'Access Type',
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
    public function getCreateUser()
    {
        return $this->hasOne(User::className(), ['id' => 'create_user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Page::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPages()
    {
        return $this->hasMany(Page::className(), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdateUser()
    {
        return $this->hasOne(User::className(), ['id' => 'update_user_id']);
    }
}
