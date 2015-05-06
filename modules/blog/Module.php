<?php

namespace app\modules\blog;

class Module extends \yii\base\Module
{
    const VERSION = '0.0.1';

    public $controllerNamespace = 'app\modules\blog\controllers';

    public $uploadPath = 'blog';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
