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
     *
     * @param $csv_path
     * @param $fseek
     * @param string $offer
     * @return bool|int
     */
    public static function importCsv($csv_path, $fseek, $offer = Coupon::OFFER_ADMITAD)
    {
        $log_path = Yii::getAlias('@runtime') . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . $offer . '_' . Coupon::LOG_PATH;
        $log_temp = sys_get_temp_dir() . DIRECTORY_SEPARATOR . self::LOG_TEMP;
        $log_arr = [0,0,0,0]; // category, brand, coupons added, coupons count
        if (@is_file($log_temp)) {
            $ftemp = fopen($log_temp, 'r');
            $data = fgetcsv($ftemp, 100, ';');
            foreach ($data as $key => $val) {
                $log_arr[$key] = $val;
            }
            fclose($ftemp);
        }
        if ($offer == Coupon::OFFER_ADMITAD) {
            /* Admitad */
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
                    $is_coupon_add = false;
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
                        $is_coupon_add = true;
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
                    } else {
                        if ($brand->advcampaign_id != null) {
                            $is_coupon_add = true;
                        }
                    }
                    if ($is_coupon_add === true) {
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
                        $log_arr[3]++;
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
                            $couponType = CouponType::findOne(['name' => $type_arr[count($type_arr) - 1]]);
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
                        } else {
                            if ($coupon->begin_dt != Coupon::getDateToTime($data[$hdr['date_start']]) || $coupon->end_dt != Coupon::getDateToTime($data[$hdr['date_end']])) {
                                $coupon->begin_dt = $data[$hdr['date_start']];
                                $coupon->end_dt = $data[$hdr['date_end']];
                                if ($coupon->validate()) {
                                    $coupon->save();
                                    $log_arr[2]++;
                                }
                            }
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
                $log_content .= 'Купонов добавлено - ' . $log_arr[2] . ';';
                $log_content .= 'Купонов обработано - ' . $log_arr[3];
                $flog = fopen($log_path, 'w');
                fwrite($flog, $log_content);
                fclose($flog);
            }
        } elseif ($offer == Coupon::OFFER_ACTIONPAY) {
            /* ActionPay */
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
                    $is_coupon_add = false;
                    $log_arr[3]++;
                    /* Category */
                    $category_arr = explode(',', $data[$hdr['categories']]);
                    /* Brand */
                    $offer_link = $data[$hdr['offer_link']];
                    $pattern = '/http.?:\/\/(.+)[\/]?/i';
                    preg_match($pattern, $offer_link, $matches);
                    $site = isset($matches[1]) ? $matches[1] : $offer_link;
                    $brand = CouponBrand::find()
                        ->where(['offer_id' => $data[$hdr['offer_id']]])
                        ->orWhere(['like', 'site', $site])
                        ->one();
                    if ($brand == null) {
                        $is_coupon_add = true;
                        $brand = new CouponBrand();
                        $brand->offer_id = $data[$hdr['offer_id']];
                        $brand->name = $data[$hdr['offer_name']];
                        $brand->site = $data[$hdr['offer_link']];
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
                            $categories = Category::find()->asArray()->all();
                            foreach ($category_arr as $name) {
                                $category_id = null;
                                $name_arr = explode(' ', $name);
                                foreach ($name_arr as $nm) {
                                    if (strlen($nm) > 1) {
                                        foreach ($categories as $cat) {
                                            $pattern = '/' . $nm . '/i';
                                            if (preg_match($pattern, $cat['name'], $matches)) {
                                                $category_id = $cat['id'];
                                                break;
                                            }
                                        }
                                        if ($category_id != null) {
                                            break;
                                        }
                                    }
                                }
                                if ($category_id != null) {
                                    $brandCategory = new CouponBrandCategory();
                                    $brandCategory->brand_id = $brand->id;
                                    $brandCategory->category_id = $category_id;
                                    if ($brandCategory->validate()) {
                                        $brandCategory->save(false);
                                    }
                                }
                            }
                            if (!isset($brandCategory) && $category_id == null) {
                                $category = new Category();
                                $category->name = $category_arr[0];
                                $category->lang = Category::DEFAULT_LANG;
                                $category->module = Yii::$app->controller->module->id;
                                if ($category->validate()) {
                                    $category->save();
                                    $log_arr[0]++;
                                    $brandCategory = new CouponBrandCategory();
                                    $brandCategory->brand_id = $brand->id;
                                    $brandCategory->category_id = $category->id;
                                    if ($brandCategory->validate()) {
                                        $brandCategory->save(false);
                                    }
                                }
                            }
                            $log_arr[1]++;
                        }
                    } else {
                        if ($brand->offer_id != null) {
                            $is_coupon_add = true;
                        }
                    }
                    /* Add coupon when brand is not Admitad */
                    if ($is_coupon_add === true) {
                        /* Coupon Type */
                        $type_arr = explode(',', $data[$hdr['types']]);
                        foreach ($type_arr as $name) {
                            $couponType = new CouponType();
                            $couponType->name = $name;
                            if ($couponType->validate()) {
                                $couponType->save(false);
                            }
                        }
                        /* Coupon */
                        $coupon = Coupon::findOne(['actionpay_id' => $data[$hdr['id']]]);
                        if ($coupon == null) {
                            $coupon = new Coupon();
                            $coupon->actionpay_id = $data[$hdr['id']];
                            $coupon->brand_id = $brand->id;
                            $coupon->name = $data[$hdr['title']];
                            $coupon->slug = Coupon::SLUG_PREFIX . '-' . $coupon->actionpay_id . '-' . Inflector::slug($coupon->name);
                            $coupon->short_name = $data[$hdr['type_name']];
                            $coupon->description = $data[$hdr['description']];
                            $coupon->promocode = $data[$hdr['code']] ? $data[$hdr['code']] : null;
                            $coupon->promolink = $data[$hdr['link']];
                            $coupon->gotolink = $data[$hdr['link']];
                            $couponType = CouponType::findOne(['name' => $type_arr[count($type_arr) - 1]]);
                            if ($couponType != null) {
                                $coupon->type_id = $couponType->id;
                            }
                            $coupon->begin_dt = $data[$hdr['begin_date']];
                            $coupon->end_dt = $data[$hdr['end_date']];
                            if ($coupon->validate()) {
                                $coupon->save(false);
                                $log_arr[2]++;
                            }
                        } else {
                            if ($coupon->begin_dt != Coupon::getDateToTime($data[$hdr['begin_date']]) || $coupon->end_dt != Coupon::getDateToTime($data[$hdr['end_date']])) {
                                $coupon->begin_dt = $data[$hdr['begin_date']];
                                $coupon->end_dt = $data[$hdr['end_date']];
                                if ($coupon->validate()) {
                                    $coupon->save(false);
                                    $log_arr[2]++;
                                }
                            }
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
                $log_content .= 'Купонов добавлено - ' . $log_arr[2] . ';';
                $log_content .= 'Купонов обработано - ' . $log_arr[3];
                $flog = fopen($log_path, 'w');
                fwrite($flog, $log_content);
                fclose($flog);
            }
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
