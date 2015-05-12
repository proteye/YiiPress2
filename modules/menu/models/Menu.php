<?php

namespace app\modules\menu\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%menu}}".
 *
 * @property integer $id
 * @property string $slug
 * @property string $name
 * @property string $description
 * @property integer $status
 *
 * @property MenuItem[] $menuItems
 */
class Menu extends \app\modules\core\models\CoreModel
{
    const STATUS_BLOCKED = 0;
    const STATUS_ACTIVE = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%menu}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            [['slug', 'name'], 'required'],
            [['status'], 'integer'],
            [['slug'], 'string', 'max' => 160],
            [['name', 'description'], 'string', 'max' => 255],
            ['status', 'in', 'range' => array_keys(self::getStatusesArray())],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'slug' => 'Алиас',
            'name' => 'Название',
            'description' => 'Описание',
            'status' => 'Статус',
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuItems()
    {
        return $this->hasMany(MenuItem::className(), ['menu_id' => 'id']);
    }

    /**
     * @return array
     */
    public static function getItemsList()
    {
        $model = self::find()->where(['status' => self::STATUS_ACTIVE])->all();

        return ArrayHelper::map($model, 'id', 'name');
    }
}
