<?php
namespace app\modules\blog\widgets;

use yii\base\Widget;
use app\modules\blog\models\Tag;

class TagCloud extends Widget
{
    public $sort = null;

    public function run()
    {
        $model = Tag::find()->all();

        return $this->render('tag-cloud', ['model' => $model]);
    }
}