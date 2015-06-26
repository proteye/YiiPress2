<?php

namespace app\modules\page\controllers;

use Yii;
use app\modules\core\components\controllers\FrontendController;
use app\modules\category\models\Category;
use app\modules\page\models\Page;
use app\modules\blog\models\Post;

class PageFrontendController extends FrontendController
{
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

        $slider_posts = Post::find()
            ->active()
            ->orderby('published_at DESC')
            ->limit(15)
            ->all()
        ;

        return $this->render('/index', [
            'model' => $model,
            'categories' => $categories,
            'slider_posts' => $slider_posts
        ]);
    }

    public function actionRoute($url)
    {
        $id = false;

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
                return $this->render('/show', ['model' => $model]);
        }

        throw new \yii\web\HttpException(404, 'Страница не найдена.');
    }

    public function actionAbout()
    {
        return $this->render('/about');
    }

    public function actionRedirect($url)
    {
        switch ($url) {
            case 'inventar/sadovyi/sadovyi-nozh': $this->redirect('/ozelenenie/tsvety-i-kustarniki/sadovyi-nozh', 301); break;
        }
    }
}