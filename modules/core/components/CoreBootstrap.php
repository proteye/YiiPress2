<?php
namespace app\modules\core\components;

use Yii;
use yii\base\BootstrapInterface;
use yii\base\Application;
use yii\helpers\Inflector;

class CoreBootstrap implements BootstrapInterface
{
    const DEFAULT_CLASS = 'app\modules\core\Module';

    public $theme;

    /**
     * @param Application $app
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function bootstrap($app)
    {
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

        /* Add module url rules */
        $this->addUrlRules();
        /* Set default layouts for Mailer */
        Yii::$app->getMailer()->htmlLayout = '@core/mail/layouts/html';
        Yii::$app->getMailer()->textLayout = '@core/mail/layouts/text';
        /* Set current locale for Date */
        $lang = str_replace('-', '_', Yii::$app->language) . '.UTF-8';
        setlocale(LC_TIME, $lang);
        /* Set transliterator */
        Inflector::$transliterator = 'Russian-Latin/BGN; NFKD';
    }

    /**
     * Add current module url rules
     */
    public function addUrlRules()
    {
        $moduleName = $this->_getModuleName();
        $urlManager = Yii::$app->getUrlManager();

        if ($moduleName == 'backend') {
            $class = self::DEFAULT_CLASS;
            if (method_exists($class, 'backendRules')) {
                $urlManager->addRules(call_user_func($class . '::backendRules'), false);
                return;
            }
        }

        if (Yii::$app->hasModule($moduleName))
        {
            $modules = Yii::$app->getModules();
            $module = $modules[$moduleName];
            if ($module instanceof \yii\base\Module) {
                $class = get_class($module);
            } else {
                $class = $module['class'];
            }
            if(method_exists($class, 'rules'))
            {
                $urlManager->addRules(call_user_func($class . '::rules'), false);
            }
        }
    }

    /**
     * @return mixed
     */
    protected function _getModuleName()
    {
        $route = Yii::$app->request->pathInfo;
        $domains = explode('/', $route);
        $moduleName = array_shift($domains);

        return $moduleName;
    }
}