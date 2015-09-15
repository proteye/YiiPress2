<?php

namespace app\modules\core;

use Yii;

class Module extends \yii\base\Module
{
    const VERSION = '0.3.1';

    public $controllerNamespace = 'app\modules\core\controllers';

    public $cacheTime = 3600;

    public $uploadPath = 'uploads';

    public $defaultLanguage = 'ru-RU';

    public $languages = 'ru-RU,en-US';

    public $transliterator = 'Russian-Latin/BGN; NFKD';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }

    /**
     * @return array
     */
    public function getLanguagesList()
    {
        $languages = [];
        foreach (explode(',', $this->languages) as $lang) {
            $l = explode('-', $lang);
            $languages[$l[0]] = $lang;
        }

        return $languages;
    }

    /**
     * @return string
     */
    public function  getFullUploadPath()
    {
        return Yii::getAlias('@webroot') . '/' . $this->uploadPath;
    }

    /**
     * @return string
     */
    public function  getFullUploadUrl()
    {
        return Yii::$app->request->baseUrl . '/' . $this->uploadPath;
    }

    /**
     * @return array
     */
    public function getModulesList()
    {
        $modules = [];
        foreach (array_keys(Yii::$app->modules) as $module) {
            if ($module == 'debug' || $module == 'gii') {
                continue;
            }
            $modules[$module] = $module;
        }

        return $modules;
    }
}
