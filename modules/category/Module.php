<?php

namespace app\modules\category;

class Module extends \yii\base\Module
{
    const VERSION = '0.0.2';

    public $controllerNamespace = 'app\modules\category\controllers';

    public $uploadPath = 'category';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
