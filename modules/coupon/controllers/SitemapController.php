<?php

namespace app\modules\coupon\controllers;

use Yii;
use yii\db\Query;
use app\modules\core\helpers\SitemapHelper;
use app\modules\coupon\models\Coupon;
use app\modules\coupon\models\CouponBrand;
use app\modules\category\models\Category;
use app\modules\core\components\controllers\FrontendController;
use yii\data\Pagination;

class SitemapController extends FrontendController
{
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        Yii::$app->urlManager->addRules(\app\modules\coupon\Module::rules(), false);

        return true;
    }

    public function actionIndex($page = null)
    {
        /*
        $command = Yii::$app->db->createCommand(
            'SELECT cat.name, cat.slug
            FROM {{%category}} cat
            WHERE cat.module = \'coupon\' AND cat.status = 1
            ORDER BY cat.name'
        );
        $command = Yii::$app->db->createCommand(
            'SELECT cp.name as cp_name, cp.slug as cp_slug,
            br.name as br_name, br.slug as br_slug
            FROM {{%coupon}} cp
            LEFT JOIN {{%coupon_brand}} br ON br.id = cp.brand_id
            WHERE cp.status = 1 AND br.status = 1
            ORDER BY br.name, cp.name'
        );
        */
        $categories = (new Query())
            ->select('cat.name, cat.slug')
            ->from('{{%category}} cat')
            ->where('cat.module = \'coupon\' AND cat.status = 1')
            ->orderBy('cat.name')
            ->all();
        $query = (new Query())
            ->select('cp.name as cp_name, cp.slug as cp_slug, br.name as br_name, br.slug as br_slug')
            ->from('{{%coupon}} cp')
            ->leftJoin('{{%coupon_brand}} br', 'br.id = cp.brand_id')
            ->where('cp.status = 1 AND br.status = 1')
            ->orderBy('br.name, cp.name');
        $count = $query->count();
        $pages = new Pagination(
            [
                'totalCount' => $count,
                'pageSize' => 500,
                'forcePageParam' => false,
                'pageSizeParam' => false,
                'validatePage' => true,
            ]
        );
        $coupons = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return $this->render('/sitemap', ['categories' => $categories, 'coupons' => $coupons, 'pages' => $pages]);
    }

    public function actionSitemapXml()
    {
        if (!$xml = Yii::$app->cache->get('sitemap')) {
            $classes = [
                '\app\modules\coupon\models\CouponBrand' => [SitemapHelper::MONTHLY, 0.8],
                '\app\modules\coupon\models\Coupon' => [SitemapHelper::MONTHLY, 0.7],
                '\app\modules\blog\models\Post' => [SitemapHelper::MONTHLY, 0.6],
                '\app\modules\page\models\Page' => [SitemapHelper::MONTHLY, 0.6],
            ];

            $query = new Query;
            $last_date = (int)$query->from('{{%coupon}}')->where(['status' => Coupon::STATUS_ACTIVE])->max('updated_at');
            $last_shop = (int)$query->from('{{%coupon_brand}}')->where(['status' => CouponBrand::STATUS_ACTIVE])->max('updated_at');
            $last_category = (int)$query->from('{{%category}}')->where(['status' => Category::STATUS_ACTIVE])->max('updated_at');

            $sitemap = new SitemapHelper();
            $sitemap->addUrl('/', SitemapHelper::DAILY, 1.0, $last_date);
            $sitemap->addUrl('/coupon/new', SitemapHelper::WEEKLY, 0.9, $last_date);
            $sitemap->addUrl('/coupon/best', SitemapHelper::WEEKLY, 0.9, $last_date);
            $sitemap->addUrl('/coupon/shops', SitemapHelper::WEEKLY, 0.9, $last_shop);
            $sitemap->addUrl('/coupon/categories', SitemapHelper::WEEKLY, 0.9, $last_category);
            $categories = Category::find()
                ->where(['module' => 'coupon'])
                ->active()
                ->all();
            foreach ($categories as $category) {
                $sitemap->addUrl('/coupon/cat-' . $category->slug, SitemapHelper::WEEKLY, 0.9, $category->updated_at);
            }
            $categories = Category::find()
                ->where('module != :module', ['module' => 'coupon'])
                ->active()
                ->all();
            $sitemap->addModels($categories, SitemapHelper::WEEKLY, 0.9);
            foreach ($classes as $class => $options) {
                $sitemap->addModels(
                    $class::find()
                        ->where('slug != :slug', ['slug' => 'index'])
                        ->active()
                        ->orderBy('updated_at')
                        ->all(),
                    $options[0],
                    $options[1]
                );
            }
            $xml = $sitemap->render();
            Yii::$app->cache->set('sitemap', $xml, 3600*6);
        }
        header("Content-type: text/xml");
        echo $xml;
        Yii::$app->end();
    }
}
