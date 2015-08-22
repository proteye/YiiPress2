<?php

namespace app\modules\user\traits;

use Yii;
use app\modules\user\Module;
/**
 * Class ModuleTrait
 * @package app\modules\user\traits
 * Implements `getModule` method, to receive current module instance.
 */
trait ModuleTrait
{
    /**
     * @var \app\modules\user\Module|null Module instance
     */
    private $_module;

    /**
     * @return \app\modules\user\Module|null Module instance
     */
    public function getModule()
    {
        if ($this->_module === null) {
            $module = Module::getInstance();
            if ($module instanceof Module) {
                $this->_module = $module;
            } else {
                $this->_module = Yii::$app->getModule('users');
            }
        }
        return $this->_module;
    }
}