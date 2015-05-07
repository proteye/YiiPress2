<?php

namespace app\modules\page\models;

use Yii;
use app\modules\category\models\Category;
use app\modules\user\models\User;

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
 * @property integer $created_by
 * @property integer $updated_by
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
 * @property User $user
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
            [['parent_id', 'category_id', 'created_by', 'updated_by', 'created_at', 'updated_at', 'sort', 'access_type', 'status'], 'integer'],
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
            'parent_id' => 'Родитель',
            'category_id' => 'Категория',
            'lang' => 'Язык',
            'url' => 'URL',
            'alias' => 'Алиас',
            'title' => 'Заголовок',
            'content' => 'Текст',
            'created_by' => 'Создал',
            'updated_by' => 'Обновил',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
            'layout' => 'Шаблон (layout)',
            'view' => 'Представление (view)',
            'meta_title' => 'SEO Title',
            'meta_keywords' => 'SEO Keywords',
            'meta_description' => 'SEO Description',
            'sort' => 'Сортировка',
            'access_type' => 'Доступ',
            'status' => 'Статус',
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
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
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
