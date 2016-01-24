<?php

namespace app\modules\core\controllers;

use Yii;
use app\modules\core\components\controllers\BackendController;
use app\modules\coupon\models\Coupon;

/**
 * Class CoreBackendController
 * @package app\modules\core\controllers
 */
class CoreBackendController extends BackendController
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        /* Admitad */
        $log_path = Yii::getAlias('@runtime') . D_S . 'logs' . D_S . Coupon::OFFER_ADMITAD . '_' . Coupon::LOG_PATH;
        $log_admitad = self::logToArray($log_path);
        /* Actionpay */
        $log_path = Yii::getAlias('@runtime') . D_S . 'logs' . D_S . Coupon::OFFER_ACTIONPAY . '_' . Coupon::LOG_PATH;
        $log_actionpay = self::logToArray($log_path);

        return $this->render('index', [
            'log_admitad' => $log_admitad,
            'log_actionpay' => $log_actionpay,
        ]);
    }

    /**
     * @param $log_path
     * @return array|null
     */
    private static function logToArray($log_path)
    {
        $log_arr = null;
        if (@is_file($log_path)) {
            $fr = fopen($log_path, 'r');
            $log_arr = fgetcsv($fr, 500, ';');
            fclose($fr);
            date_default_timezone_set('UTC');
            $fdate = date('d.m.Y H:i', filemtime($log_path) + 10800); // UTC+3
            array_push($log_arr, $fdate);
        }
        return $log_arr;
    }
}
