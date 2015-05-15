<?php
namespace app\modules\core\components\behaviors;

use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;

class CacheClearBehavior extends Behavior
{
    public $modules = [];

    /**
     * @return array
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'afterInsert',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterInsert',
        ];
    }

    public function afterInsert()
    {
        $this->clearModulesCache($this->modules);
    }

    /**
     * @param $modules
     */
    public function clearModulesCache($modules)
    {
        if (is_array($modules) && !empty($modules)) {
            foreach ($modules as $module) {
                $cacheId = Yii::$app->getModule($module)->cacheId;
                Yii::$app->cache->delete($cacheId);
            }
        }
    }
}
