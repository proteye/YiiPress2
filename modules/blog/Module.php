<?php

namespace app\modules\blog;

use Yii;

class Module extends \yii\base\Module
{
    const VERSION = '0.1.6';

    public $controllerNamespace = 'app\modules\blog\controllers';

    public $uploadPath = 'blog';

    public $cacheId = 'blogCID';

    public $tagUrl = 'tag';

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
        $post = Yii::$app->db->createCommand('SELECT id, category_id, slug FROM {{%post}}')->queryAll();
        $category = Yii::$app->getModule('category')->getPathsMap();
        $items = null;

        foreach ($post as $item) {
            $parent = ($item['category_id'] !== null) ? $category[$item['category_id']] . '/' : null;
            $items[$item['id']] = $parent . $item['slug'];
        }

        return $items;
    }
}
