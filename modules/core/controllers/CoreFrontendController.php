<?php

namespace app\modules\core\controllers;

use app\modules\blog\models\Tag;
use Yii;
use app\modules\core\components\controllers\FrontendController;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\modules\blog\models\Post;
use app\modules\category\models\Category;
use app\modules\page\models\Page;
use yii\helpers\ArrayHelper;
use yii\base\ErrorException;
use yii\helpers\Html;
use yii\data\Pagination;
use yii\helpers\Url;

class CoreFrontendController extends FrontendController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        /* Set current locale for Date */
        $lang = str_replace('-', '_', Yii::$app->language) . '.UTF-8';
        setlocale(LC_ALL, $lang);

        return true; // or false to not run the action
    }

    public function actionIndex()
    {
        $model = Page::find()
            ->where(['slug' => 'index'])
            ->active()
            ->one()
        ;

        $categories = Category::find()
            ->where(['parent_id' => null])
            ->active()
            ->all()
        ;

        return $this->render('index', [
            'model' => $model,
            'categories' => $categories,
        ]);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

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
                return $this->render('/blog/show', ['model' => $model]);
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
                        'defaultPageSize' => 6,
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
                return $this->render('/blog/category', ['model' => $model, 'posts' => $posts, 'pages' => $pages]);
            }
        }

        /* Page */
        $pathsMap = Yii::$app->getModule('page')->getPathsMap();
        if (is_array($pathsMap))
            $id = array_search($url, $pathsMap);
        if ($id !== false) {
            $model = Page::find()->where(['id' => $id])
                ->active()
                ->one()
            ;
            if ($model)
                return $this->render('/page/show', ['model' => $model]);
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
                return $this->render('/blog/tag', ['model' => $model, 'posts' => $posts, 'pages' => $pages]);
        }
    }

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
