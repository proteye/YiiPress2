<?php

namespace app\modules\user\models;

use Yii;

/**
 * This is the model class for table "{{%user_profile}}".
 *
 * @property integer $user_id
 * @property string $nick_nm
 * @property string $first_nm
 * @property string $last_nm
 * @property string $patron
 * @property integer $birth_dt
 * @property string $about
 * @property string $site
 * @property string $address
 * @property string $user_ip
 * @property string $avatar
 * @property integer $last_visit
 * @property integer $email_confirm
 *
 * @property User $user
 */
class UserProfile extends \app\modules\core\models\CoreModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_profile}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['birth_dt', 'last_visit', 'email_confirm'], 'integer'],
            [['nick_nm', 'first_nm', 'last_nm', 'patron', 'about', 'site', 'avatar'], 'string', 'max' => 255],
            [['address'], 'string', 'max' => 512],
            [['user_ip'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'nick_nm' => 'Ник',
            'first_nm' => 'Имя',
            'last_nm' => 'Фамилия',
            'patron' => 'Отчество',
            'birth_dt' => 'Дата рождения',
            'about' => 'Обо мне',
            'site' => 'Сайт',
            'address' => 'Адрес',
            'user_ip' => 'User IP',
            'avatar' => 'Аватар',
            'last_visit' => 'Последнее посещение',
            'email_confirm' => 'Email подтвержден',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
