<?php
namespace app\modules\blog\widgets;

use Yii;
use yii\base\Widget;
use app\modules\blog\models\Post;

class RecentPost extends Widget
{
    private $cacheId = 'recentPost';
    public $post_id = null;
    public $limit = 10;
    public $sort = 'published_at';

    public function run()
    {
        $core = Yii::$app->getModule('core');

        // Top Posts
        if ($this->post_id !== null) {
            $cacheId = $this->cacheId . '_' . $this->post_id;
            $model = Yii::$app->cache[$cacheId];
            if ($model === false) {
                $model = Post::find()
                    ->where('id != :id', ['id' => $this->post_id])
                    ->active()
                    ->orderBy($this->sort . ' DESC')
                    ->limit($this->limit)
                    ->all();
                Yii::$app->cache->set($cacheId, $model, $core->cacheTime);
            }
        } else {
            $model = Yii::$app->cache[$this->cacheId];
            if ($model === false) {
                $model = Post::find()
                    ->active()
                    ->orderBy($this->sort . ' DESC')
                    ->limit($this->limit)
                    ->all();
                Yii::$app->cache->set($this->cacheId, $model, $core->cacheTime);
            }
        }

        return $this->render('recent-post', ['model' => $model]);
    }
}