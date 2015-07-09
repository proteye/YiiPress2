<?php
namespace app\modules\blog\widgets;

use Yii;
use yii\base\Widget;
use app\modules\blog\models\Tag;

class TagCloud extends Widget
{
    private $cacheId = 'tagCloud';
    public $sort = null;

    public function run()
    {
        $core = Yii::$app->getModule('core');
        $model = Yii::$app->cache[$this->cacheId];

        if ($model === false) {
            $model = Tag::find()->all();
            Yii::$app->cache->set($this->cacheId, $model, $core->cacheTime);
        }

        return $this->render('tag-cloud', ['model' => $model]);
    }
}