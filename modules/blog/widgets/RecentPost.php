<?php
namespace app\modules\blog\widgets;

use yii\base\Widget;
use app\modules\blog\models\Post;

class RecentPost extends Widget
{
    public $post_id = null;
    public $limit = 10;
    public $sort = 'published_at';

    public function run()
    {
        // Top Posts
        if ($this->post_id !== null) {
            $model = Post::find()
                ->where('id != :id', ['id' => $this->post_id])
                ->active()
                ->orderBy($this->sort . ' DESC')
                ->limit($this->limit)
                ->all()
            ;
        } else {
            $model = Post::find()
                ->active()
                ->orderBy($this->sort . ' DESC')
                ->limit($this->limit)
                ->all()
            ;
        }

        return $this->render('recent-post', ['model' => $model]);
    }
}