<?php

namespace app\modules\blog\controllers;

use yii\web\Controller;

class BlogBackendController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}
