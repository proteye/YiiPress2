<?php

namespace app\modules\page\controllers;

use yii\web\Controller;

class PageBackendController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}
