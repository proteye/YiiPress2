<?php

namespace app\modules\menu\models;

use Yii;

/**
 * This is the model class for table "{{%menu_item}}".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property integer $menu_id
 * @property integer $regular_link
 * @property string $title
 * @property string $href
 * @property string $class
 * @property string $title_attr
 * @property string $before_link
 * @property string $after_link
 * @property string $target
 * @property string $rel
 * @property string $condition_name
 * @property integer $condition_denial
 * @property integer $sort
 * @property integer $status
 *
 * @property Menu $menu
 * @property MenuItem $parent
 * @property MenuItem[] $menuItems
 */
class MenuItem extends \app\modules\core\models\CoreModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%menu_item}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'menu_id', 'regular_link', 'condition_denial', 'sort', 'status'], 'integer'],
            [['menu_id', 'title', 'href'], 'required'],
            [['title', 'class', 'title_attr', 'before_link', 'after_link', 'target', 'rel', 'condition_name'], 'string', 'max' => 160],
            [['href'], 'string', 'max' => 255]
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
            'menu_id' => 'Меню',
            'regular_link' => 'Постоянная ссылка',
            'title' => 'Заголовок',
            'href' => 'Адрес',
            'class' => 'Атрибут title',
            'title_attr' => 'Атрибут class',
            'before_link' => 'Текст перед ссылкой',
            'after_link' => 'Текст после ссылки',
            'target' => 'Атрибут target',
            'rel' => 'Атрибут rel',
            'condition_name' => 'Условие',
            'condition_denial' => 'Отрицание условия',
            'sort' => 'Сортировка',
            'status' => 'Статус',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenu()
    {
        return $this->hasOne(Menu::className(), ['id' => 'menu_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(MenuItem::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuItems()
    {
        return $this->hasMany(MenuItem::className(), ['parent_id' => 'id']);
    }
}
