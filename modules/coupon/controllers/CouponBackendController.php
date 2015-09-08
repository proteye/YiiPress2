<?php

namespace app\modules\coupon\controllers;

use Yii;
use app\modules\category\models\Category;
use app\modules\coupon\models\CouponBrand;
use app\modules\coupon\models\CouponBrandCategory;
use app\modules\coupon\models\CouponType;
use app\modules\menu\models\MenuItem;
use app\modules\coupon\models\Coupon;
use app\modules\coupon\models\CouponSearch;
use app\modules\core\components\controllers\BackendController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\menu\models\Menu;
use yii\web\UploadedFile;
use yii\helpers\Inflector;

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

    /**
     * @param $menu_id
     */
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

    /**
     * 18 columns
     * @param $file_csv
     */
    private static function importCsv($file_csv)
    {
        $module = Yii::$app->getModule('core');
        Inflector::$transliterator = $module->transliterator;
        $image_path = Yii::getAlias(
            '@webroot' .
            DIRECTORY_SEPARATOR . $module->uploadPath
        );
        $csv_path = $image_path . DIRECTORY_SEPARATOR . 'file_csv.csv';
        $file_csv->saveAs($csv_path);
        if (($fr = fopen($csv_path, 'r')) !== FALSE) {
            $data = fgetcsv($fr, 2000, ';'); // skip header
            while (($data = fgetcsv($fr, 2000, ';')) !== FALSE) {
                /* Category */
                $category_arr = explode(',', $data[16]);
                foreach ($category_arr as $name) {
                    $category = new Category();
                    $category->name = $name;
                    $category->module = Yii::$app->controller->module->id;
                    if ($category->validate())
                        $category->save();
                }
                /* Brand */
                $brand = CouponBrand::findOne(['advcampaign_id' => $data[4]]);
                if ($brand == null) {
                    $brand = new CouponBrand();
                    $brand->advcampaign_id = $data[4];
                }
                $brand->name = $data[5];
                $brand->site = $data[2];
                /* Download logo image */
                $file_name = Inflector::slug($brand->name) . substr($data[6], -4);
                $file_path = $image_path . DIRECTORY_SEPARATOR . $file_name;
                if (!@is_file($file_path)) {
                    file_put_contents($file_path, file_get_contents($data[6]));
                }
                UploadedFile::reset();
                $_FILES['brand-logo'] = [
                    'name' => $file_name,
                    'type' => mime_content_type($file_path),
                    'tmp_name' => $file_path,
                    'error' => 0,
                    'size' => filesize($file_path),
                ];
                $brand->image = UploadedFile::getInstanceByName('brand-logo');
                $brand->image_alt = 'Магазин ' . $brand->name;
                if ($brand->save()) {
                    @unlink($file_path);
                    foreach ($category_arr as $name) {
                        $category = Category::findOne(['name' => $name]);
                        if ($category != null) {
                            $brandCategory = new CouponBrandCategory();
                            $brandCategory->brand_id = $brand->id;
                            $brandCategory->category_id = $category->id;
                            if ($brandCategory->validate())
                                $brandCategory->save();
                        }
                    }
                }
                /* Coupon Type */
                $type_arr = explode(',', $data[15]);
                foreach ($type_arr as $name) {
                    $couponType = new CouponType();
                    $couponType->name = $name;
                    if ($couponType->validate())
                        $couponType->save();
                }
                /* Coupon */
                $coupon = Coupon::findOne(['adv_id' => $data[0]]);
                if ($coupon == null) {
                    $coupon = new Coupon();
                    $coupon->adv_id = $data[0];
                }
                $coupon->brand_id = $brand->id;
                $coupon->name = $data[1];
                $coupon->short_name = $data[3];
                $coupon->description = $data[7];
                $coupon->promocode = $data[9];
                $coupon->promolink = $data[10];
                $coupon->gotolink = $data[11];
                $couponType = CouponType::findOne(['name' => $type_arr[count($type_arr)-1]]);
                if ($couponType != null) {
                    $coupon->type_id = $couponType->id;
                    $coupon->discount = $data[17];
                }
                $coupon->begin_dt = $data[12];
                $coupon->end_dt = $data[13];
                if ($coupon->validate())
                    $coupon->save();
            }
            fclose($fr);
            @unlink($csv_path);
        }
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionImportCsv()
    {
        if (Yii::$app->request->isPost) {
            $file_csv = UploadedFile::getInstanceByName('file-csv');
            if ($file_csv instanceof UploadedFile && $file_csv->type == 'text/csv') {
                self::importCsv($file_csv);
                Yii::$app->getSession()->setFlash('success', 'Импорт произведен успешно!');
                return $this->redirect(['index']);
            } else {
                Yii::$app->getSession()->setFlash('error', 'Файл не соответствует формату!');
            }
        }

        return $this->render('import-csv');
    }
}
