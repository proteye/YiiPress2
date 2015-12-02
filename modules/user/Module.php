<?php

namespace app\modules\user;

class Module extends \yii\base\Module
{
    const VERSION = '0.1.2';

    public $controllerNamespace = 'app\modules\user\controllers';

    private $_mail;

    public function init()
    {
        parent::init();
    }

    /**
     * @return mixed
     */
    public function getMail()
    {
        if ($this->_mail === null) {
            $this->_mail = \Yii::$app->getMailer();
            $this->_mail->viewPath = '@theme/modules/user/mail';
        }
        return $this->_mail;
    }

    public static function rules()
    {
        return [
            'user/<action>' => 'user/user-frontend/<action>', // login|logout|signup|email-confirm|request-password-reset|password-reset
        ];
    }
}
