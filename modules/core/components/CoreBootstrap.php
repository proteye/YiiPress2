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
                $duration = null;
                $dependency = new DbDependency(['sql' => 'SELECT MAX(updated_at) FROM {{%setting}}']);
                $result = Yii::$app->db->cache(function ($db) {
                    return Yii::$app->db->createCommand("SELECT param_value FROM {{%setting}} WHERE module_id='core' AND param_key = 'theme'")->queryOne();
                }, $duration, $dependency);
                $theme = $result['param_value'];
                if (!$theme)
                    return false;
            }

            if (file_exists('@app/themes/' . $theme)) {
                Yii::setAlias('theme', '@app/themes/' . $theme);
                Yii::$app->view->theme = Yii::createObject([
                    'class' => '\yii\base\Theme',
                    'pathMap' => [
                        '@app/views' => '@theme/views',
                        '@app/modules' => '@theme/views/modules',
                    ],
                    'baseUrl' => '@web/themes/' . $theme,
                ]);
            }
        });
    }
}