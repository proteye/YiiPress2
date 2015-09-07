<?php

namespace app\modules\coupon\controllers;

use app\modules\category\models\Category;
use app\modules\menu\models\MenuItem;
use Yii;
use app\modules\coupon\models\Coupon;
use app\modules\coupon\models\CouponSearch;
use app\modules\core\components\controllers\BackendController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\menu\models\Menu;

/**
 * CouponBackendController implements the CRUD actions for Coupon model.
 */
class CouponBackendController extends BackendController
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
     * Lists all Coupon models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CouponSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Coupon model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Coupon model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Coupon();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Coupon model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Coupon model.
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
     * Finds the Coupon model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Coupon the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Coupon::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    private static function createMenuItems($menu_id)
    {
        /* New and best coupons */
        $menuItem = new MenuItem();
        $menuItem->menu_id = $menu_id;
        $menuItem->regular_link = 1;
        $menuItem->title = 'Новые промокоды';
        $menuItem->href = 'kupon/new';
        $menuItem->save();

        $menuItem = new MenuItem();
        $menuItem->menu_id = $menu_id;
        $menuItem->regular_link = 1;
        $menuItem->title = 'Лучшие промокоды';
        $menuItem->href = 'kupon/best';
        $menuItem->save();

        /* Brands */
        $menuItem = new MenuItem();
        $menuItem->menu_id = $menu_id;
        $menuItem->regular_link = 1;
        $menuItem->title = 'Магазины';
        $menuItem->href = 'kupon/brands';
        $menuItem->save();

        /* Categories */
        $menuItem = new MenuItem();
        $menuItem->menu_id = $menu_id;
        $menuItem->regular_link = 1;
        $menuItem->title = 'Категории';
        $menuItem->href = 'kupon/categories';
        $menuItem->save();
        $parent_id = $menuItem->id;

        $categories = Category::find()
            ->where(['module' => Yii::$app->controller->module->id, 'parent_id' => null])
            ->active()
            ->all();
        foreach ($categories as $category) {
            $menuItem = new MenuItem();
            $menuItem->menu_id = $menu_id;
            $menuItem->parent_id = $parent_id;
            $menuItem->regular_link = 1;
            $menuItem->title = $category->name;
            $menuItem->href = 'kupon/category/' . Yii::$app->getModule('category')->pathsMap[$category->id];
            $menuItem->save();
        }
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionMenuCreate()
    {
        $model = new Menu();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            self::createMenuItems($model->id);
            Yii::$app->getSession()->setFlash('success', 'Меню создано');
            return $this->redirect(['index']);
        } else {
            return $this->render('menu-create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionMenuUpdate()
    {
        $menu_id = (int)Yii::$app->request->post('menu_id');
        if (Yii::$app->request->isPost && $menu_id != null) {
            // MenuItem::updateAll(['status' => MenuItem::STATUS_BLOCKED], ['menu_id' => $menu_id]);
            MenuItem::deleteAll(['menu_id' => $menu_id]);
            self::createMenuItems($menu_id);
            Yii::$app->getSession()->setFlash('success', 'Меню обновлено');
            return $this->redirect(['index']);
        } else {
            return $this->render('menu-update');
        }
    }
}
