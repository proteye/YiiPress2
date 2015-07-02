<?php
namespace app\modules\blog\widgets;

use app\modules\category\models\Category;
use yii\base\Widget;
use app\modules\blog\models\Post;

class TopicCategory extends Widget
{
    public $category_id;

    public function run()
    {
        if ($this->category_id === null)
            return false;

        // Topic Categories
        $category = Category::findOne($this->category_id);
        $model = Category::find()
            ->where(['parent_id' => $category->parent_id])
            ->active()
            ->all()
        ;
        return $this->render('topic-category', ['parent' => $category->parent, 'model' => $model]);
    }
}