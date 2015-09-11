<?php

namespace app\modules\coupon\controllers;

use app\modules\category\models\Category;
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
        $page = Page::find()
            ->where(['slug' => 'index'])
            ->active()
            ->one();

        $brands = CouponBrand::find()
            ->active()
            ->orderBy(['view_count' => SORT_DESC])
            ->limit(15)
            ->all();

        $best = Coupon::find()
            ->active()
            ->orderBy(['view_count' => SORT_DESC])
            ->limit(9)
            ->all();

        $new = Coupon::find()
            ->active()
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(9)
            ->all();

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
            $model = Coupon::find()
                ->active()
                ->orderBy(['created_at' => SORT_DESC])
                ->limit(36)
                ->all();
        } elseif ($action === 'best') {
            $model = Coupon::find()
                ->active()
                ->orderBy(['view_count' => SORT_DESC])
                ->limit(36)
                ->all();
        }

        return $this->render('/new-best', [
            'action' => $action,
            'model' => $model,
        ]);
    }

    public function actionBrands()
    {
        $model = CouponBrand::find()
            ->active()
            ->orderBy('name')
            ->all();

        return $this->render('/shops', [
            'model' => $model,
        ]);
    }

    public function actionBrand($brand)
    {
        $model = CouponBrand::find()
            ->where(['slug' => $brand])
            ->active()
            ->one();
        if (!$model) {
            throw new \yii\web\NotFoundHttpException('Страница не найдена.');
        }

        return $this->render('/brand', [
            'model' => $model,
        ]);
    }

    public function actionCategories()
    {
        $model = Category::find()
            ->active()
            ->orderBy('name')
            ->all();

        return $this->render('/categories', [
            'model' => $model,
        ]);
    }

    public function actionCategory($category)
    {
        $model = Category::find()
            ->where(['slug' => $category])
            ->active()
            ->one();
        if (!$model) {
            throw new \yii\web\NotFoundHttpException('Страница не найдена.');
        }
        $brands = CouponBrand::find()
            ->select('{{%coupon_brand}}.*')
            ->leftJoin('{{%coupon_brand_category}}', '{{%coupon_brand_category}}.brand_id = {{%coupon_brand}}.id')
            ->where(['category_id' => $model->id])
            ->active()
            ->all();

        return $this->render('/category', [
            'model' => $model,
            'brands' => $brands,
        ]);
    }

    public function actionSearch()
    {
        echo 'search';
    }
}