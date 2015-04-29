<?php

namespace app\modules\core\controllers;

use Yii;
use app\modules\core\models\Setting;
use app\modules\core\models\SettingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CoreBackendController implements the CRUD actions for Setting model.
 */
class CoreBackendController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Setting models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SettingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Setting model.
     * @param string $module_id
     * @param string $param_key
     * @return mixed
     */
    public function actionView($module_id, $param_key)
    {
        return $this->render('view', [
            'model' => $this->findModel($module_id, $param_key),
        ]);
    }

    /**
     * Creates a new Setting model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Setting();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'module_id' => $model->module_id, 'param_key' => $model->param_key]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Setting model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $module_id
     * @param string $param_key
     * @return mixed
     */
    public function actionUpdate($module_id, $param_key)
    {
        $model = $this->findModel($module_id, $param_key);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'module_id' => $model->module_id, 'param_key' => $model->param_key]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Setting model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $module_id
     * @param string $param_key
     * @return mixed
     */
    public function actionDelete($module_id, $param_key)
    {
        $this->findModel($module_id, $param_key)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Setting model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $module_id
     * @param string $param_key
     * @return Setting the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($module_id, $param_key)
    {
        if (($model = Setting::findOne(['module_id' => $module_id, 'param_key' => $param_key])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
