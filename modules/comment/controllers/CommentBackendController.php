<?php

namespace app\modules\comment\controllers;

use yii\web\Controller;

class CommentBackendController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}
