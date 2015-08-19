<?php

namespace app\modules\user\controllers;

use Yii;
use app\modules\core\components\controllers\BackendController;
use app\modules\user\models\User;
use app\modules\user\models\UserSearch;
use app\modules\user\models\UserProfile;
use yii\debug\models\search\Profile;
use yii\web\NotFoundHttpException;

/**
 * DefaultController implements the CRUD actions for User model.
 */
class UserBackendController extends BackendController
{
    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'profile' => UserProfile::findOne($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();
        $model->setScenario($model::OP_INSERT);
        $profile = new UserProfile();

        if ($model->load(Yii::$app->request->post()) && $profile->load(Yii::$app->request->post())) {
            if ($model->validate() && $profile->validate()) {
                $model->save(false);
                $profile->user_id = $model->id;
                $profile->save(false);
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'profile' => $profile,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->setScenario($model::OP_UPDATE);
        $profile = UserProfile::findOne($id);
        if (!isset($profile)) {
            $profile = new UserProfile();
            $profile->user_id = $id;
        }

        if ($model->load(Yii::$app->request->post()) && $profile->load(Yii::$app->request->post())) {
            if ($model->validate() && $profile->validate()) {
                $model->save(false);
                $profile->save(false);
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'profile' => $profile,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionChangePassword($id)
    {
        $model = $this->findModel($id);
        $model->setScenario($model::OP_UPDATE);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('change-password', [
                'model' => $model,
            ]);
        }
    }
}
