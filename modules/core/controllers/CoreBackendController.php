<?php

namespace app\modules\core\controllers;

class CoreBackendController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

}
