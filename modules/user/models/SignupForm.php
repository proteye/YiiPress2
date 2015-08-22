<?php
namespace app\modules\user\models;

use Yii;
use yii\base\Model;
use app\modules\user\traits\ModuleTrait;

/**
 * Signup form
 */
class SignupForm extends Model
{
    use ModuleTrait;

    public $username;
    public $email;
    public $password;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => 'app\modules\user\models\User', 'message' => 'Пользователь с таким Логином уже существует.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => 'app\modules\user\models\User', 'message' => 'Пользователь с таким Email уже существует.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Логин',
            'email' => 'Email',
            'password' => 'Пароль',
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $profile = new UserProfile();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->status = User::STATUS_WAIT;
            $user->generateEmailConfirmToken();

            if ($user->validate() && $profile->validate()) {
                $user->save(false);
                $profile->user_id = $user->id;
                $profile->save(false);

                $this->module->mail
                    ->compose(['html' => 'emailConfirm-html', 'text' => 'emailConfirm-text'], ['user' => $user])
                    ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
                    ->setTo($this->email)
                    ->setSubject('Подтвердите Email для завершения регистрации на ' . Yii::$app->name)
                    ->send();

                return $user;
            }
        }

        return null;
    }
}
