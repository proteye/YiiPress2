<?php

namespace app\modules\page;

use Yii;

class Module extends \yii\base\Module
{
    const VERSION = '0.0.9';

    public $controllerNamespace = 'app\modules\page\controllers';

    public $cacheId = 'pageCID';

    public function init()
    {
        parent::init();

        if (Yii::$app->cache[$this->cacheId] === false)
            $this->updatePathsMap();
    }

    public function getPathsMap()
    {
        $pathsMap = Yii::$app->cache[$this->cacheId];

        return $pathsMap === false ? $this->generatePathsMap() : $pathsMap;
    }

    public function updatePathsMap()
    {
        $cacheTime = Yii::$app->getModule('core')->cacheTime;
        Yii::$app->cache->set($this->cacheId, $this->generatePathsMap(), $cacheTime);
    }

    public function generatePathsMap()
    {
        $page = Yii::$app->db->createCommand('SELECT id, parent_id, slug FROM {{%page}} ORDER BY parent_id')->queryAll();
        $items = null;

        foreach ($page as $item) {
            $parent = isset($items[$item['parent_id']]) ? $items[$item['parent_id']] . '/' : null;
            $items[$item['id']] = $parent . $item['slug'];
        }

        return $items;
    }

    public static function rules()
    {
        return [
            'page/<action:(index)>'                 => 'page/page-frontend/<action>',
            'page/<url:[\w\-\/]+>'                  => 'page/page-frontend/route',
        ];
    }
}
