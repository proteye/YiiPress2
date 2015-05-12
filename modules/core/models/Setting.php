<?php

namespace app\modules\core\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "{{%setting}}".
 *
 * @property string $module_id
 * @property string $param_key
 * @property string $param_value
 * @property integer $user_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $type
 */
class Setting extends CoreModel
{
    const TYPE_CORE = 1; /* для модуля */
    const TYPE_USER = 2; /* для пользователей */

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%setting}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
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
                'createdByAttribute' => 'user_id',
                'updatedByAttribute' => 'user_id',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['type', 'default', 'value' => self::TYPE_CORE],
            [['module_id', 'param_key'], 'required'],
            [['user_id', 'created_at', 'updated_at', 'type'], 'integer'],
            [['module_id'], 'string', 'max' => 64],
            [['param_key'], 'string', 'max' => 128],
            [['param_value'], 'string', 'max' => 255],
            [['module_id', 'param_key'], 'unique', 'targetAttribute' => ['module_id', 'param_key'], 'message' => 'Такая комбинация Модуля и Параметра уже существует.'],
            ['type', 'in', 'range' => array_keys(self::getTypesArray())],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'module_id' => 'Модуль',
            'param_key' => 'Параметр',
            'param_value' => 'Значение',
            'user_id' => 'Пользователь',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
            'type' => 'Тип',
        ];
    }
    /**
     * @return string
     */
    public function getTypeName()
    {
        $statuses = self::getTypesArray();
        return isset($statuses[$this->status]) ? $statuses[$this->status] : '';
    }

    /**
     * @return array
     */
    public static function getTypesArray()
    {
        return [
            self::TYPE_CORE => 'Основной',
            self::TYPE_USER => 'Для пользователя',
        ];
    }
}
