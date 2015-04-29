<?php

namespace app\modules\core;

class Module extends \yii\base\Module
{
    const VERSION = '0.0.1';

    public $controllerNamespace = 'app\modules\core\controllers';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
