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
        /* Set current locale for Date */
        $lang = str_replace('-', '_', Yii::$app->language) . '.UTF-8';
        setlocale(LC_ALL, $lang);

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
                $categories = Category::find()
                    ->where(['id' => $model->id])
                    ->orWhere(['parent_id' => $model->id])
                    ->andWhere(['status' => Category::STATUS_ACTIVE])
                    ->all()
                ;
                $arr_categories = ArrayHelper::map($categories, 'id', 'id');

                $posts = Post::find()
                    ->where(['category_id' => $arr_categories])
                    ->andWhere('status = :status', ['status' => Post::STATUS_ACTIVE])
                    ->all()
                ;

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

        /* Tag */
        $tagUrl = Yii::$app->getModule('blog')->tagUrl . '/';
        $pos = strpos($url, $tagUrl);
        if ($pos !== false) {
            $tag = str_replace($tagUrl, '', $url);
            $model = Tag::findOne(['slug' => $tag]);
            if ($model)
                return $this->render('/blog/tag', ['model' => $model]);
        }

        throw new \yii\web\HttpException(404, 'Страница не найдена.');
    }
}
