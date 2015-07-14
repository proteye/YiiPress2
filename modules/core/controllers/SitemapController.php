<?php

namespace app\modules\core\controllers;

use Yii;
use yii\db\Query;
use app\modules\core\components\controllers\FrontendController;
use app\modules\blog\models\Post;
use app\modules\category\models\Category;

class SitemapController extends FrontendController
{
    const ALWAYS = 'always';
    const HOURLY = 'hourly';
    const DAILY = 'daily';
    const WEEKLY = 'weekly';
    const MONTHLY = 'monthly';
    const YEARLY = 'yearly';
    const NEVER = 'never';

    public function actionIndex()
    {
        $model = Category::find()
            ->where(['parent_id' => null])
            ->active()
            ->all()
        ;
        return $this->render('/sitemap', ['model' => $model]);
    }

    public function actionSitemapXml()
    {
        $classes = [
            '\app\modules\category\models\Category' => [self::WEEKLY, 0.9],
            '\app\modules\blog\models\Post' => [self::MONTHLY, 0.8],
            '\app\modules\page\models\Page' => [self::MONTHLY, 0.6],
        ];

        $items = array();
        foreach ($classes as $class => $options){
            $items = array_merge($items, [
                [
                'model' => $class::find()
                        ->where('slug != :slug', ['slug' => 'index'])
                        ->active()
                        ->orderBy('updated_at')
                        ->all(),
                'changefreq' => $options[0],
                'priority' => $options[1],
                ]
            ]);
        }

        $query = new Query;
        $last_date = $query->from('{{%post}}')->where(['status' => Post::STATUS_ACTIVE])->max('published_at');

        return $this->renderPartial('sitemap-xml', [
            'host' => Yii::$app->request->hostInfo,
            'last_date' => $last_date,
            'items' => $items
        ]);
    }
}
