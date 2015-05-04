<?php

namespace app\modules\core\controllers;

use Yii;
use app\modules\core\components\controllers\BackendController;

class CoreBackendController extends BackendController
{
    public function actionIndex()
    {
        return $this->render('index');
    }

}
