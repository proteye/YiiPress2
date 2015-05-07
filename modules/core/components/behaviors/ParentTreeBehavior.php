<?php
namespace app\modules\core\components\behaviors;

use yii\base\Behavior;
use yii\helpers\ArrayHelper;

class ParentTreeBehavior extends Behavior
{
    public $parentAttr = 'parent_id';

    public $displayAttr = 'name';

    public $status = 1;

    /**
     * @param bool $selfId
     * @return array
     */
    public function getParentsList($selfId = false)
    {
        $model = $this->owner;
        $condition = ($selfId)
            ? 'id != ' . $selfId
            : ''
        ;
        $items = $model->find()->where($condition)->andWhere(['status' => $this->status])->all();

        return ArrayHelper::map($items, 'id', $this->displayAttr);
    }
}
