<?php

namespace app\modules\comment\models;

use Yii;
use app\modules\user\models\User;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use app\modules\core\components\behaviors\ParentTreeBehavior;
use app\modules\core\components\behaviors\FilterAttributeBehavior;


/**
 * This is the model class for table "{{%comment}}".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property integer $user_id
 * @property string $model
 * @property integer $model_id
 * @property string $name
 * @property string $email
 * @property string $text
 * @property integer $created_at
 * @property string $user_ip
 * @property integer $status
 * @property integer $tree
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 *
 * @property Comment $parent
 * @property Comment[] $comments
 * @property User $user
 */
class Comment extends \app\modules\core\models\CoreModel
{
    const STATUS_WAIT = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_SPAM = 2;
    const STATUS_DELETED = 3;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%comment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'user_id', 'model_id', 'created_at', 'status', 'tree', 'lft', 'rgt', 'depth'], 'integer'],
            [['model', 'model_id', 'name', 'email', 'text'], 'required'],
            [['text'], 'string'],
            [['model', 'name', 'email'], 'string', 'max' => 160],
            [['user_ip'], 'string', 'max' => 20],
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
            'parent_id' => 'Родитель',
            'user_id' => 'Пользователь',
            'model' => 'Модель',
            'model_id' => 'ID модели',
            'name' => 'Имя',
            'email' => 'Email',
            'text' => 'Комментарий',
            'created_at' => 'Дата создания',
            'user_ip' => 'User IP',
            'status' => 'Статус',
            'tree' => 'Tree',
            'lft' => 'Lft',
            'rgt' => 'Rgt',
            'depth' => 'Depth',
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'created_at',
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'created_at',
                ],
            ],
            'tree' => [
                'class' => ParentTreeBehavior::className(),
                'displayAttr' => 'id',
                'status' => self::STATUS_ACTIVE,
            ],
            'filter_attribute' => [
                'class' => FilterAttributeBehavior::className(),
                'ipAttribute' => 'user_ip',
            ],
            'blame' => [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'user_id',
                'updatedByAttribute' => 'user_id',
            ],
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
            self::STATUS_ACTIVE => 'Опубликован',
            self::STATUS_WAIT => 'В ожидании',
            self::STATUS_SPAM => 'Спам',
            self::STATUS_DELETED => 'Удален',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Comment::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
