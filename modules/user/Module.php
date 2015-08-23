<?php

namespace app\modules\user;

class Module extends \yii\base\Module
{
    const VERSION = '0.0.9';

    public $controllerNamespace = 'app\modules\user\controllers';

    private $_mail;

    public function init()
    {
        parent::init();

        // custom initialization code goes here
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
}
