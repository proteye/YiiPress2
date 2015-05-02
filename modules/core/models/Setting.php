<?php

namespace app\modules\core\models;

use Yii;
use yii\behaviors\TimestampBehavior;

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
            TimestampBehavior::className(),
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
            [['module_id', 'param_key'], 'unique', 'targetAttribute' => ['module_id', 'param_key'], 'message' => 'The combination of Module ID and Param Key has already been taken.']
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
}
