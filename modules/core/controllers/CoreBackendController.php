<?php

namespace app\modules\core\controllers;

use Yii;
use app\modules\core\components\controllers\BackendController;
use app\modules\coupon\models\Coupon;

class CoreBackendController extends BackendController
{
    public function actionIndex()
    {
        $log_path = Yii::getAlias('@runtime') . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . Coupon::LOG_PATH;
        $log_arr = null;
        if (@is_file($log_path)) {
            $fr = fopen($log_path, 'r');
            $log_arr = fgetcsv($fr, 500, ';');
            fclose($fr);
            date_default_timezone_set('UTC');
            $fdate = date('d.m.Y H:i', filemtime($log_path) + 10800); // UTC+3
            array_push($log_arr, $fdate);
        }

        return $this->render('index', [
            'log_arr' => $log_arr,
        ]);
    }
}
