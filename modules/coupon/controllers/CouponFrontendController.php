<?php

namespace app\modules\coupon\controllers;

use Yii;
use app\modules\core\components\controllers\FrontendController;
use yii\helpers\ArrayHelper;
use yii\data\Pagination;
use yii\helpers\Url;

class CouponFrontendController extends FrontendController
{
    public function actionDefault($id)
    {
        echo 'coupon #' . $id;
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
        echo 'brands';
    }

    public function actionBrand($brand)
    {
        echo 'brand - ' . $brand;
    }

    public function actionCategories()
    {
        echo 'categories';
    }

    public function actionCategory()
    {
        echo 'category';
    }

    public function actionSearch()
    {
        echo 'search';
    }
}