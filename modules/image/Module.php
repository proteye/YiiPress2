<?php

namespace app\modules\image;

use Yii;

class Module extends \yii\base\Module
{
    const VERSION = '0.0.1';

    public $controllerNamespace = 'app\modules\image\controllers';

    public $uploadPath = 'image';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }

    /**
     * @return string
     */
    public function  getFullUploadPath()
    {
        return Yii::$app->getModule('core')->fullUploadPath . '/' . $this->uploadPath;
    }

    /**
     * @return string
     */
    public function  getFullUploadUrl()
    {
        return Yii::$app->getModule('core')->fullUploadUrl . '/' . $this->uploadPath;
    }
}
