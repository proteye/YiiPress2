<?php
namespace app\modules\blog\widgets;

use yii\base\Widget;
use app\modules\blog\models\Post;

class TopicPost extends Widget
{
    public $post_id;
    public $category_id;
    public $limit = 10;
    public $sort = 'published_at';

    public function run()
    {
        if ($this->post_id === null || $this->category_id === null)
            return false;

        // Topic Posts
        $model = Post::find()
            ->where(['category_id' => $this->category_id])
            ->andWhere('id != :id', ['id' => $this->post_id])
            ->active()
            ->orderBy($this->sort . ' DESC')
            ->limit($this->limit)
            ->all()
        ;

        return $this->render('topic-post', ['model' => $model]);
    }
}