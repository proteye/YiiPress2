<?php
namespace app\modules\core\widgets;

use Yii;
use yii\helpers\Html;

class SearchPost extends \yii\base\Widget
{
    public function run()
    {
        $query = Html::encode(Yii::$app->request->get('q'));
        return $this->render('search-post', ['query' => $query]);
    }
}