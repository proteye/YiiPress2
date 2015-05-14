<?php

namespace app\modules\core\controllers;

use Yii;
use app\modules\core\components\controllers\FrontendController;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\modules\blog\models\Post;
use app\modules\category\models\Category;
use app\modules\page\models\Page;
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

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionRoute($url)
    {
        $id = false;

        /* Post */
        $pathsMap = Yii::$app->getModule('blog')->getPathsMap();
        if (is_array($pathsMap))
            $id = array_search($url, $pathsMap);
        if ($id !== false) {
            $model = Post::find()->where(['id' => $id, 'status' => Post::STATUS_ACTIVE])->one();
            if ($model)
                return $this->render('/blog/show', ['model' => $model]);
        }

        /* Category */
        $pathsMap = Yii::$app->getModule('category')->getPathsMap();
        if (is_array($pathsMap))
            $id = array_search($url, $pathsMap);
        if ($id !== false) {
            $model = Category::find()->where(['id' => $id, 'status' => Category::STATUS_ACTIVE])->one();
            if ($model) {
                $posts = Post::find()->select(['category_id', 'title', 'slug', 'quote'])->where(['category_id' => $id, 'status' => Post::STATUS_ACTIVE])->asArray()->all();
                foreach ($posts as $key => $val)
                    $posts[$key]['url'] = Yii::$app->request->baseUrl . '/' . $pathsMap[$val['category_id']] . '/' . $val['slug'];

                return $this->render('/blog/category', ['model' => $model, 'posts' => $posts]);
            }
        }

        /* Page */
        $pathsMap = Yii::$app->getModule('page')->getPathsMap();
        if (is_array($pathsMap))
            $id = array_search($url, $pathsMap);
        if ($id !== false) {
            $model = Page::find()->where(['id' => $id, 'status' => Page::STATUS_ACTIVE])->one();
            if ($model)
                return $this->render('/page/show', ['model' => $model]);
        }

        throw new \yii\web\HttpException(404, 'Страница не найдена.');
    }
}
