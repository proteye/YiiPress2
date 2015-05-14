<?php

namespace app\modules\category;

use app\modules\category\models\Category;
use Yii;

class Module extends \yii\base\Module
{
    const VERSION = '0.0.3';

    public $controllerNamespace = 'app\modules\category\controllers';

    public $uploadPath = 'category';

    public $cacheId = 'categoryCID';

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
        $category = Yii::$app->db->createCommand('SELECT id, parent_id, slug FROM {{%category}}')->queryAll();
        $items = null;

        foreach ($category as $item) {
            $parent = isset($items[$item['parent_id']]) ? $items[$item['parent_id']] . '/' : null;
            $items[$item['id']] = $parent . $item['slug'];
        }

        return $items;
    }
}
