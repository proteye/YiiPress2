<?php

namespace app\modules\coupon\controllers;

use Yii;
use app\modules\core\components\controllers\FrontendController;
use app\modules\page\models\Page;
use app\modules\coupon\models\CouponBrand;
use app\modules\coupon\models\Coupon;

class CouponFrontendController extends FrontendController
{
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        Yii::$app->urlManager->addRules(\app\modules\coupon\Module::rules(), false);

        return true;
    }

    public function actionIndex()
    {
        //print_r(Yii::$app->urlManager->rules);
        $page = Page::find()
            ->where(['slug' => 'index'])
            ->active()
            ->one()
        ;

        $brands = CouponBrand::find()
            ->active()
            ->orderBy(['view_count' => SORT_DESC])
            ->limit(15)
            ->all()
        ;

        $best = Coupon::find()
            ->active()
            ->orderBy(['view_count' => SORT_DESC])
            ->limit(9)
            ->all()
        ;

        $new = Coupon::find()
            ->active()
            ->orderBy(['updated_at' => SORT_DESC])
            ->limit(9)
            ->all()
        ;

        return $this->render('/index', [
            'page' => $page,
            'brands' => $brands,
            'best' => $best,
            'new' => $new,
        ]);
    }

    public function actionDefault($coupon)
    {
        echo 'coupon #' . $coupon;
    }

    public function actionNewBest($action)
    {
        if ($action === 'new') {
            echo 'new';
        } elseif ($action === 'best') {
            echo 'best';
        }
    }

    public function actionBrands()
    {
        echo 'shops';
    }

    public function actionBrand($brand)
    {
        echo 'brand - ' . $brand;
    }

    public function actionCategories()
    {
        echo 'categories';
    }

    public function actionCategory($category)
    {
        echo $category;
    }

    public function actionSearch()
    {
        echo 'search';
    }
}