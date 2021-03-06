<?php
namespace app\modules\user\models;

use Yii;
use yii\base\Model;
use yii\base\InvalidParamException;

class EmailConfirmForm extends Model
{
    /**
     * @var User
     */
    private $_user;

    /**
     * Creates a form model given a token.
     *
     * @param  string $token
     * @param  array $config
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    public function __construct($token, $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidParamException('Отсутствует код подтверждения.');
        }
        $this->_user = User::findByEmailConfirmToken($token);
        if (!$this->_user) {
            throw new InvalidParamException('Неверный токен.');
        }
        parent::__construct($config);
    }

    /**
     * Confirm email.
     *
     * @return boolean if email was confirmed.
     */
    public function confirmEmail()
    {
        $user = $this->_user;
        $profile = UserProfile::findOne($user->id);
        $user->status = User::STATUS_ACTIVE;
        $user->removeEmailConfirmToken();
        $profile->email_confirm = UserProfile::STATUS_TRUE;

        return ($user->save() && $profile->save());
    }
}