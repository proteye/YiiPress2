<?php

namespace app\modules\menu\controllers;

use yii\web\Controller;

class MenuBackendController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}
