<?php

namespace app\modules\catalog\controllers;

use Yii;
use app\modules\core\components\controllers\FrontendController;

class CatalogFrontendController extends FrontendController
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}
