<?php

namespace app\modules\comment\models;

use Yii;
use app\modules\user\models\User;

/**
 * This is the model class for table "{{%comment}}".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property integer $user_id
 * @property string $model
 * @property integer $model_id
 * @property string $url
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
            [['model', 'model_id', 'name', 'email', 'text', 'created_at', 'lft', 'rgt', 'depth'], 'required'],
            [['text'], 'string'],
            [['model', 'url', 'name', 'email'], 'string', 'max' => 160],
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
            'parent_id' => 'Родитель',
            'user_id' => 'Пользователь',
            'model' => 'Модель',
            'model_id' => 'ID модели',
            'url' => 'URL',
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
