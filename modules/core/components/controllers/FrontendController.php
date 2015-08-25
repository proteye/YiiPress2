<?php
namespace app\modules\core\components\controllers;

use Yii;

class FrontendController extends Controller
{
    public $layout = '//main';

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
                'view' => '/error'
            ],
        ];
    }

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        return true;
    }
}
