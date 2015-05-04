<?php
namespace app\modules\core\components\controllers;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class BackendController extends Controller
{
    public $layout = '@core/views/layouts/main';

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
                'denyCallback' => function ($rule, $action) {
                        throw new \yii\web\NotFoundHttpException('Страница не найдена.');
                    },
            ],
        ];
    }
}
