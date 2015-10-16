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
use yii\base\Exception;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\menu\models\Menu;
use yii\web\UploadedFile;
use yii\helpers\Inflector;
use app\modules\core\helpers\TranslitHelper;

/**
 * CouponBackendController implements the CRUD actions for Coupon model.
 */
class CouponBackendController extends BackendController
{
    const LOG_TEMP = 'log_temp.csv';

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
        $module = Yii::$app->controller->module->id;
        /* New and best coupons */
        $menuItem = new MenuItem();
        $menuItem->menu_id = $menu_id;
        $menuItem->regular_link = 1;
        $menuItem->title = 'Новые промокоды';
        $menuItem->href = $module . '/new';
        $menuItem->save();

        $menuItem = new MenuItem();
        $menuItem->menu_id = $menu_id;
        $menuItem->regular_link = 1;
        $menuItem->title = 'Лучшие промокоды';
        $menuItem->href = $module . '/best';
        $menuItem->save();

        /* Brands */
        $menuItem = new MenuItem();
        $menuItem->menu_id = $menu_id;
        $menuItem->regular_link = 1;
        $menuItem->title = 'Магазины';
        $menuItem->href = $module . '/shops';
        $menuItem->save();

        /* Categories */
        $menuItem = new MenuItem();
        $menuItem->menu_id = $menu_id;
        $menuItem->regular_link = 1;
        $menuItem->title = 'Категории';
        $menuItem->href = $module . '/categories';
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
            $menuItem->href = $module . '/cat-' . Yii::$app->getModule('category')->pathsMap[$category->id];
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
     * Import CSV file with promocodes
     * 20 columns
     * @param $file_csv
     */
    public static function importCsv($csv_path, $fseek)
    {
        $log_path = Yii::getAlias('@runtime') . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . Coupon::LOG_PATH;
        $log_temp = sys_get_temp_dir() . DIRECTORY_SEPARATOR . self::LOG_TEMP;
        $log_arr = [0,0,0]; // category, brand, coupon counts
        if (@is_file($log_temp)) {
            $ftemp = fopen($log_temp, 'r');
            $data = fgetcsv($ftemp, 100, ';');
            foreach ($data as $key => $val) {
                $log_arr[$key] = $val;
            }
            fclose($ftemp);
        }
        if (($fr = fopen($csv_path, 'r')) !== FALSE) {
            /* Read header */
            $hdr = null;
            $data = fgetcsv($fr, 2000, ';');
            foreach ($data as $key => $val) {
                $hdr[$val] = $key;
            }
            /* Read data */
            $i = 0;
            if ($fseek != 0) {
                fseek($fr, $fseek);
            }
            while (($data = fgetcsv($fr, 2000, ';')) !== FALSE) {
                $i++;
                /* Category */
                $category_arr = explode(',', $data[$hdr['categories']]);
                foreach ($category_arr as $name) {
                    $category = new Category();
                    $category->name = $name;
                    $category->lang = Category::DEFAULT_LANG;
                    $category->module = Yii::$app->controller->module->id;
                    if ($category->validate()) {
                        $category->save();
                        $log_arr[0]++;
                    }
                }
                /* Brand */
                $brand = CouponBrand::findOne(['advcampaign_id' => $data[$hdr['advcampaign_id']]]);
                if ($brand == null) {
                    $brand = new CouponBrand();
                    $brand->advcampaign_id = $data[$hdr['advcampaign_id']];
                    $brand->name = $data[$hdr['advcampaign_name']];
                    $brand->site = $data[$hdr['site']];
                    $brand->image_alt = 'Магазин ' . $brand->name;
                    if ($brand->sec_name == null) {
                        $brand->sec_name = TranslitHelper::convert($brand->name);
                    }
                    /* If Brand is new then download logo image */
                    if (!isset($brand->id)) {
                        $file_name = Inflector::slug($brand->name) . substr($data[$hdr['logo']], strrpos($data[$hdr['logo']], '.'));
                        $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                        file_put_contents($file_path, file_get_contents($data[$hdr['logo']]));
                        UploadedFile::reset();
                        $_FILES['brand-logo'] = [
                            'name' => $file_name,
                            'type' => mime_content_type($file_path),
                            'tmp_name' => $file_path,
                            'error' => 0,
                            'size' => filesize($file_path),
                        ];
                        $brand->image = UploadedFile::getInstanceByName('brand-logo');
                    }
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
                        $log_arr[1]++;
                    }
                }
                /* Coupon Type */
                $type_arr = explode(',', $data[$hdr['types']]);
                foreach ($type_arr as $name) {
                    $couponType = new CouponType();
                    $couponType->name = $name;
                    if ($couponType->validate())
                        $couponType->save();
                }
                /* Coupon */
                $coupon = Coupon::findOne(['adv_id' => $data[$hdr['id']]]);
                if ($coupon == null) {
                    $coupon = new Coupon();
                    $coupon->adv_id = $data[$hdr['id']];
                    $coupon->brand_id = $brand->id;
                    $coupon->name = $data[$hdr['name']];
                    $coupon->slug = Coupon::SLUG_PREFIX . '-' . $coupon->adv_id . '-' . Inflector::slug($coupon->name);
                    $coupon->short_name = $data[$hdr['short_name']];
                    $coupon->description = $data[$hdr['description']];
                    $coupon->promocode = ($data[$hdr['species']] == 'promocode') ? $data[$hdr['promocode']] : null;
                    $coupon->promolink = $data[$hdr['promolink']];
                    $coupon->gotolink = $data[$hdr['gotolink']];
                    $couponType = CouponType::findOne(['name' => $type_arr[count($type_arr)-1]]);
                    if ($couponType != null) {
                        $coupon->type_id = $couponType->id;
                        $coupon->discount = $data[$hdr['discount']];
                    }
                    $coupon->begin_dt = $data[$hdr['date_start']];
                    $coupon->end_dt = $data[$hdr['date_end']];
                    if ($coupon->validate()) {
                        $coupon->save();
                        $log_arr[2]++;
                    }
                }
                unset($coupon);
                unset($couponType);
                unset($brand);
                unset($category);
                if ($i == 500) {
                    $ftemp = fopen($log_temp, 'w');
                    fputcsv($ftemp, $log_arr, ';');
                    fclose($ftemp);
                    $fseek = ftell($fr);
                    fclose($fr);
                    return $fseek;
                }
            }
            fclose($fr);
            @unlink($csv_path);
            @unlink($log_temp);
            $log_content = 'Категорий добавлено - ' . $log_arr[0] . ';';
            $log_content .= 'Магазинов добавлено - ' . $log_arr[1] . ';';
            $log_content .= 'Купонов добавлено - ' . $log_arr[2];
            $flog = fopen($log_path, 'w');
            fwrite($flog, $log_content);
            fclose($flog);
        }
        return true;
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionImportCsv($fseek = 0)
    {
        $csv_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . Coupon::CSV_FILE;
        if (Yii::$app->request->isPost) {
            $file_csv = UploadedFile::getInstanceByName('file-csv');
            if ($file_csv instanceof UploadedFile && $file_csv->type == 'text/csv') {
                $file_csv->saveAs($csv_path);
                $result = self::importCsv($csv_path, $fseek);
                if ($result === true) {
                    Yii::$app->getSession()->setFlash('success', 'Импорт произведен успешно!');
                    return $this->redirect(['index']);
                }
                return Yii::$app->getResponse()->redirect('?fseek=' . $result);
            } else {
                Yii::$app->getSession()->setFlash('error', 'Файл не соответствует формату!');
            }
        } elseif ($fseek != 0) {
            if (@is_file($csv_path)) {
                $result = self::importCsv($csv_path, $fseek);
                if ($result === true) {
                    Yii::$app->getSession()->setFlash('success', 'Импорт произведен успешно!');
                    return $this->redirect(['index']);
                }
                return Yii::$app->getResponse()->redirect('?fseek=' . $result);
            } else {
                Yii::$app->getSession()->setFlash('error', 'Файл не соответствует формату!');
            }
        }

        return $this->render('import-csv');
    }
}
