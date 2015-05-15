<?php

namespace app\modules\menu;

use app\modules\category\models\Category;
use Yii;

class Module extends \yii\base\Module
{
    const VERSION = '0.0.3';

    public $controllerNamespace = 'app\modules\menu\controllers';

    public function init()
    {
        parent::init();
    }
}
