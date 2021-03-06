<?php
namespace app\commands;
 
use Yii;
use yii\console\Controller;
use yii\helpers\Console;
use app\modules\coupon\models\Coupon;
use app\modules\coupon\controllers\CouponBackendController;
use yii\helpers\Inflector;

class CouponsController extends Controller
{
    public function actionIndex()
    {
        echo 'yii coupons/import' . PHP_EOL;
    }
 
    public function actionImport($url, $folder, $fseek = 0, $offer = 'admitad')
    {
        Inflector::$transliterator = 'Russian-Latin/BGN; NFKD';
        if (Yii::$app->mutex->acquire('coupon-import')) {
            $csv_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . Coupon::CSV_FILE;
            $log_path = Yii::getAlias('@runtime') . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . Coupon::LOG_PATH;
            file_put_contents($csv_path, file_get_contents($url));
            $result = CouponBackendController::importCsv($csv_path, $fseek, $offer);
            if ($result !== true) {
                Yii::$app->mutex->release('coupon-import');
                exec('/var/www/' . $folder . '/yii coupons/import \'' . $url . '\' \'' . $folder . '\' ' . $result . ' \'' . $offer . '\'');
                die();
            }
            if (@is_file($csv_path)) {
                @unlink($csv_path);
            }
            if (@is_file($log_path)) {
                chmod($log_path, 0666);
            }
            echo 'Coupons imported!' . PHP_EOL;
            $this->log(true);
            Yii::$app->mutex->release('coupon-import');
        } else {
            echo 'The import process is not yet complete!' . PHP_EOL;
            $this->log(false);
        }
    }

    /**
     * @param bool $success
     */
    private function log($success)
    {
        if ($success) {
            $this->stdout('Success!', Console::FG_GREEN, Console::BOLD);
        } else {
            $this->stderr('Error!', Console::FG_RED, Console::BOLD);
        }
        echo PHP_EOL;
    }
}
