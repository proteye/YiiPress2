<?php
namespace app\modules\core\components\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Inflector;
use vova07\imperavi\actions\GetAction;

class BackendController extends Controller
{
    public $layout = '@core/views/layouts/main';

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'ajax-slug' => ['post'],
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

    /**
     * @return array
     */
    public function actions()
    {
        $image = Yii::$app->getModule('image');

        return [
            'image-get' => [
                'class' => GetAction::className(),
                'url' => $image->fullUploadUrl,
                'path' => $image->fullUploadPath,
                'type' => GetAction::TYPE_IMAGES,
            ]
        ];
    }

    /**
     * Ajax slug action
     */
    public function actionAjaxSlug()
    {
        $data = Yii::$app->request->post('data');
        echo Inflector::slug($data);
    }
}
