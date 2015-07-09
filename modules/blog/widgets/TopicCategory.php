<?php
namespace app\modules\blog\widgets;

use Yii;
use app\modules\category\models\Category;
use yii\base\Widget;

class TopicCategory extends Widget
{
    private $cacheId = 'topicCategory';
    public $category_id;

    public function run()
    {
        if ($this->category_id === null)
            return false;

        // Topic Categories
        $core = Yii::$app->getModule('core');
        $cacheId = $this->cacheId . '_' . $this->category_id;
        $category = Category::findOne($this->category_id);

        $model = Yii::$app->cache[$cacheId];
        if ($model === false) {
            $model = Category::find()
                ->where(['parent_id' => $category->parent_id])
                ->active()
                ->all();
            Yii::$app->cache->set($cacheId, $model, $core->cacheTime);
        }

        return $this->render('topic-category', ['parent' => $category->parent, 'model' => $model]);
    }
}