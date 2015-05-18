<?php

namespace app\modules\blog\controllers;

use Yii;
use app\modules\core\components\controllers\FrontendController;
use app\modules\blog\models\Post;
use app\modules\category\models\Category;
use app\modules\blog\models\Tag;
use yii\helpers\ArrayHelper;
use yii\data\Pagination;

class BlogFrontendController extends FrontendController
{
    public function actionRoute($url, $page = null)
    {
        $id = false;

        /* Post */
        $pathsMap = Yii::$app->getModule('blog')->getPathsMap();
        if (is_array($pathsMap))
            $id = array_search($url, $pathsMap);
        if ($id !== false) {
            $model = Post::find()
                ->where(['id' => $id])
                ->active()
                ->one()
            ;
            if ($model) {
                /* Update post view count */
                $model->updateCounters(['view_count' => 1]);
                return $this->render('/show', ['model' => $model]);
            }
        }

        /* Category */
        $pathsMap = Yii::$app->getModule('category')->getPathsMap();
        if (is_array($pathsMap))
            $id = array_search($url, $pathsMap);
        if ($id !== false) {
            $model = Category::find()
                ->where(['id' => $id])
                ->active()
                ->one();
            if ($model) {
                $categories = Category::find()
                    ->where(['id' => $model->id])
                    ->orWhere(['parent_id' => $model->id])
                    ->active()
                    ->all()
                ;
                $arr_categories = ArrayHelper::map($categories, 'id', 'id');

                $_posts = Post::find()
                    ->where(['category_id' => $arr_categories])
                    ->active()
                    ->orderBy('published_at DESC')
                ;

                // Pagination
                $countQuery = clone $_posts;
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

                $posts = $_posts->offset($pages->offset)
                    ->limit($pages->limit)
                    ->all()
                ;
                return $this->render('/category', ['model' => $model, 'posts' => $posts, 'pages' => $pages]);
            }
        }

        throw new \yii\web\HttpException(404, 'Страница не найдена.');
    }

    public function actionTag($url, $page = null)
    {
        $model = Tag::findOne(['slug' => $url]);

        if ($model) {
            $_posts = Post::find()
                ->leftJoin('{{%post_tag}}', '{{%post_tag}}.post_id = {{%post}}.id')
                ->where(['tag_id' => $model->id])
                ->active()
                ->orderBy('published_at DESC');
            ;

            // Pagination
            $countQuery = clone $_posts;
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

            $posts = $_posts->offset($pages->offset)
                ->limit($pages->limit)
                ->all()
            ;

            if ($model)
                return $this->render('/tag', ['model' => $model, 'posts' => $posts, 'pages' => $pages]);
        }
    }
}