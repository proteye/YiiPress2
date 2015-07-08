<?php
namespace app\modules\core\widgets;

use Yii;

class VkGroup extends \yii\base\Widget
{
    public function run()
    {
        return $this->render('vk-group');
    }
}