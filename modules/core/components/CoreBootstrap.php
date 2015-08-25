<?php
namespace app\modules\core\components;

use Yii;
use yii\base\BootstrapInterface;
use yii\base\Application;
use yii\caching\DbDependency;

class CoreBootstrap implements BootstrapInterface
{
    public $theme;

    public function bootstrap($app)
    {
        $app->on(Application::EVENT_BEFORE_REQUEST, function () {
            /* Set current THEME */
            $theme = $this->theme;

            if (!$theme) {
                /*
                // Set cache
                $duration = 60*60*24;
                $dependency = new DbDependency(['sql' => 'SELECT MAX(updated_at) FROM {{%setting}}']);
                $result = Yii::$app->db->cache(function ($db) {
                    return Yii::$app->db->createCommand("SELECT param_value FROM {{%setting}} WHERE module_id='core' AND param_key = 'theme'")->queryOne();
                }, $duration, $dependency);
                */
                $result = Yii::$app->db->createCommand("SELECT param_value FROM {{%setting}} WHERE module_id='core' AND param_key = 'theme'")->queryOne();
                $theme = $result['param_value'];
                if (!$theme)
                    return false;
            }

            if (file_exists(Yii::getAlias('@app/themes/') . $theme)) {
                Yii::setAlias('theme', '@app/themes/' . $theme);
                Yii::$app->view->theme = Yii::createObject([
                    'class' => '\yii\base\Theme',
                    'basePath' => '@theme',
                    'baseUrl' => '@web/themes/' . $theme,
                    'pathMap' => [
                        '@app/views' => '@theme',
                        '@app/modules' => '@theme/modules',
                    ],
                ]);
            }
        });

        /* Set default layouts for Mailer */
        Yii::$app->getMailer()->htmlLayout = '@core/mail/layouts/html';
        Yii::$app->getMailer()->textLayout = '@core/mail/layouts/text';

        /* Set current locale for Date */
        $lang = str_replace('-', '_', Yii::$app->language) . '.UTF-8';
        setlocale(LC_TIME, $lang);
    }
}