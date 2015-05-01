<?php

namespace app\modules\image\controllers;

use yii\web\Controller;

class ImageBackendController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}
