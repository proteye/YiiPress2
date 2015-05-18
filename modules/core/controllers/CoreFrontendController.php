<?php

namespace app\modules\core\controllers;

use Yii;
use app\modules\core\components\controllers\FrontendController;
use app\modules\blog\models\Post;
use yii\base\ErrorException;
use yii\helpers\Html;
use yii\data\Pagination;

class CoreFrontendController extends FrontendController
{
    public function actionSearch($q = null, $page = null)
    {
        $query = Html::encode($q);
        // Search posts
        $_model = Post::find()
            ->where(['like', 'title', $query])
            ->orWhere(['like', 'content', $query]);
        $_model->active();
        $_model->orderBy('published_at DESC');

        // Pagination
        $countQuery = clone $_model;
        $pages = new Pagination(
            [
                'totalCount' => $countQuery->count(),
                'defaultPageSize' => 10,
                'forcePageParam' => false,
                'pageSizeParam' => false,
                'validatePage'=>true,
            ]
        );

        if ($page > $pages->pageCount)
            throw new \yii\web\HttpException(404, 'Страница не найдена.');

        $model = $_model->offset($pages->offset)
            ->limit($pages->limit)
            ->all()
        ;

        try {
            return $this->render('search', ['model' => $model, 'query' => $query, 'pages' => $pages]);
        }  catch(ErrorException $e) {
            throw new \yii\web\HttpException(404, 'Страница не найдена.');
        }
    }
}
