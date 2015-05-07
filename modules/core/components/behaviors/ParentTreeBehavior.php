<?php
namespace app\modules\core\components\behaviors;

use yii\base\Behavior;
use yii\helpers\ArrayHelper;

class ParentTreeBehavior extends Behavior
{
    public $parentAttr = 'parent_id';
    public $displayAttr = 'name';

    /**
     * @param bool $selfId
     * @return array
     */
    public function getItemsList($selfId = false)
    {
        $model = $this->owner;
        $condition = ($selfId)
            ? 'id != ' . $selfId
            : ''
        ;
        $items = $model->find()->where($condition)->asArray()->all();

        return ArrayHelper::map($items, 'id', $this->displayAttr);
    }
}
