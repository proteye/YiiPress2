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
            ],
        ];
    }

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        /* Set current locale for Date */
        $lang = str_replace('-', '_', Yii::$app->language) . '.UTF-8';
        setlocale(LC_ALL, $lang);

        return true;
    }
}
