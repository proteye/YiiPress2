<?php

namespace app\modules\catalog;

class Module extends \yii\base\Module
{
    const VERSION = '0.0.3';

    public $controllerNamespace = 'app\modules\catalog\controllers';

    public $uploadPath = 'catalog';

    public $cacheId = 'catalogCID';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
