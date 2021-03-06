<?php
namespace app\modules\blog\widgets;

use Yii;
use app\modules\category\models\Category;
use yii\base\Widget;
use app\modules\blog\models\Post;

class TopicPost extends Widget
{
    private $cacheId = 'topicPost';
    public $post_id;
    public $category_id;
    public $limit = 3;
    public $sort = 'published_at';
    /**
     * default | bottom
     * @var string
     */
    public $template = 'bottom';

    public function run()
    {
        if ($this->post_id === null && $this->category_id === null) {
            return false;
        }

        // Topic Posts - bottom
        $core = Yii::$app->getModule('core');
        $cacheId = $this->cacheId . '_' . $this->template . $this->category_id . $this->post_id;

        $model = Yii::$app->cache[$cacheId];

        if ($this->template == 'bottom') {
            if ($model === false) {
                $model = Post::find()
                    ->where(['category_id' => $this->category_id])
                    ->andWhere('id != :id', ['id' => $this->post_id])
                    ->active()
                    ->orderBy($this->sort . ' DESC')
                    ->limit($this->limit)
                    ->all();
                Yii::$app->cache->set($cacheId, $model, $core->cacheTime);
            }
            $view = 'topic-post-btm';
        } else {
            if ($model === false) {
                $category = Category::findOne($this->category_id);
                $model = $category->parent->getAllPosts($this->limit, $this->post_id);
                Yii::$app->cache->set($cacheId, $model, $core->cacheTime);
            }
            $view = 'topic-post';
        }

        return $this->render($view, ['model' => $model]);
    }
}