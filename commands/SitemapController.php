<?php
namespace app\commands;
 
use Yii;
use yii\console\Controller;
use yii\helpers\Console;
use yii\console\Exception;
use yii\db\Query;

class SitemapController extends Controller
{
    public function actionIndex()
    {
        echo 'yii sitemap/default' . PHP_EOL;
        echo 'yii sitemap/coupon' . PHP_EOL;
    }
 
    public function actionDefault()
    {
    }
 
    public function actionCoupon($host)
    {
        if (Yii::$app->mutex->acquire('sitemap-coupon')) {
            $filepath = Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR . 'sitemap.xml';
            if (($handle = fopen($filepath, "w")) !== FALSE) {
                $query = new Query;
                $last_date = (int)$query->from('{{%coupon}}')->where(['status' => 1])->max('updated_at');
                $last_shop = (int)$query->from('{{%coupon_brand}}')->where(['status' => 1])->max('updated_at');
                $last_category = (int)$query->from('{{%category}}')->where(['module' => 'coupon', 'status' => 1])->max('updated_at');
                $str = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
                $str .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;
                $str .= '
                    <url>
                        <loc>' . $host . '/</loc>
                        <lastmod>' . date(DATE_W3C, $last_date) . '</lastmod>
                        <changefreq>daily</changefreq>
                        <priority>1.0</priority>
                    </url>
                ';
                $str .= '
                    <url>
                        <loc>' . $host . '/coupon/new</loc>
                        <lastmod>' . date(DATE_W3C, $last_date) . '</lastmod>
                        <changefreq>weekly</changefreq>
                        <priority>0.9</priority>
                    </url>
                ';
                $str .= '
                    <url>
                        <loc>' . $host . '/coupon/best</loc>
                        <lastmod>' . date(DATE_W3C, $last_date) . '</lastmod>
                        <changefreq>weekly</changefreq>
                        <priority>0.9</priority>
                    </url>
                ';
                $str .= '
                    <url>
                        <loc>' . $host . '/coupon/shops</loc>
                        <lastmod>' . date(DATE_W3C, $last_shop) . '</lastmod>
                        <changefreq>weekly</changefreq>
                        <priority>0.9</priority>
                    </url>
                ';
                $str .= '
                    <url>
                        <loc>' . $host . '/coupon/categories</loc>
                        <lastmod>' . date(DATE_W3C, $last_category) . '</lastmod>
                        <changefreq>monthly</changefreq>
                        <priority>0.9</priority>
                    </url>
                ';
                fwrite($handle, $str);
                /* Categories */
                $command = Yii::$app->db->createCommand(
                    'SELECT slug, updated_at FROM {{%category}} WHERE status = 1 AND module = \'coupon\' ORDER BY updated_at'
                );
                $reader = $command->query();
                foreach ($reader as $row) {
                    $str = '<url>' . PHP_EOL;
                    $str .= '<loc>' . $host . '/coupon/cat-' . $row['slug'] . '</loc>' . PHP_EOL;
                    $str .= '<lastmod>' . date(DATE_W3C, $row['updated_at']) . '</lastmod>' . PHP_EOL;
                    $str .= '<changefreq>weekly</changefreq>' . PHP_EOL;
                    $str .= '<changefreq>0.9</changefreq>' . PHP_EOL;
                    $str .= '</url>' . PHP_EOL;
                    fwrite($handle, $str);
                }
                /* Brands */
                $command = Yii::$app->db->createCommand(
                    'SELECT slug, updated_at FROM {{%coupon_brand}} WHERE status = 1 ORDER BY updated_at'
                );
                $reader = $command->query();
                foreach ($reader as $row) {
                    $str = '<url>' . PHP_EOL;
                    $str .= '<loc>' . $host . '/coupon/' . $row['slug'] . '</loc>' . PHP_EOL;
                    $str .= '<lastmod>' . date(DATE_W3C, $row['updated_at']) . '</lastmod>' . PHP_EOL;
                    $str .= '<changefreq>weekly</changefreq>' . PHP_EOL;
                    $str .= '<changefreq>0.9</changefreq>' . PHP_EOL;
                    $str .= '</url>' . PHP_EOL;
                    fwrite($handle, $str);
                }
                /* Coupons */
                $command = Yii::$app->db->createCommand(
                    'SELECT c.slug, c.updated_at, b.slug as brand FROM {{%coupon}} c LEFT JOIN {{%coupon_brand}} b ON c.brand_id = b.id WHERE c.status = 1 ORDER BY c.updated_at'
                );
                $reader = $command->query();
                foreach ($reader as $row) {
                    $str = '<url>' . PHP_EOL;
                    $str .= '<loc>' . $host . '/coupon/' . $row['brand'] . '/' . $row['slug'] . '</loc>' . PHP_EOL;
                    $str .= '<lastmod>' . date(DATE_W3C, $row['updated_at']) . '</lastmod>' . PHP_EOL;
                    $str .= '<changefreq>monthly</changefreq>' . PHP_EOL;
                    $str .= '<changefreq>0.8</changefreq>' . PHP_EOL;
                    $str .= '</url>' . PHP_EOL;
                    fwrite($handle, $str);
                }
                fwrite($handle, '</urlset>' . PHP_EOL);
                fclose($handle);
            }
            echo 'Sitemap.xml created!' . PHP_EOL;
            $this->log(true);
            Yii::$app->mutex->release('sitemap-coupon');
        } else {
            echo 'Sitemap.xml the process of creating!' . PHP_EOL;
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
