<?php

namespace app\modules\core;

class Module extends \yii\base\Module
{
    const VERSION = '0.0.1';

    public $controllerNamespace = 'app\modules\core\controllers';

    public $coreCacheTime = 3600;

    public $uploadPath = 'uploads';

    public $defaultLanguage = 'ru-RU';

    public $languages = 'ru-RU,en-US';

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
}
