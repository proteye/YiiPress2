<?php
namespace app\modules\core\components\behaviors;

use yii\base\Behavior;
use yii\db\ActiveRecord;

class FilterAttributeBehavior extends Behavior
{
    public $slugAttribute = 'url';

    public $dateAttribute = 'published_at';

    /**
     * @return array
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
        ];
    }

    /**
     * @param bool $selfId
     * @return array
     */
    public function beforeValidate()
    {
        $model = $this->owner;

        if (isset($model->{$this->dateAttribute})) {
            $model->{$this->dateAttribute} = $this->getDateToTime();
        }
    }

    /**
     * @return int
     */
    protected function getDateToTime()
    {
        $model = $this->owner;
        return strtotime($model->{$this->dateAttribute});
    }
}
