<?php
namespace app\modules\core\components\behaviors;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;

class FilterAttributeBehavior extends AttributeBehavior
{
    public $slugAttribute = 'url';

    public $dateAttribute = 'published_at';

    public $ipAttribute = 'user_ip';

    /**
     * @return array
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeInsert',
        ];
    }

    public function beforeValidate()
    {
        $model = $this->owner;

        if ($model->hasAttribute($this->dateAttribute)) {
            $model->{$this->dateAttribute} = $this->getDateToTime();
        }
    }

    public function beforeInsert()
    {
        $model = $this->owner;

        if ($model->hasAttribute($this->ipAttribute)) {
            if (isset(Yii::$app->request->userIP))
                $model->{$this->ipAttribute} = Yii::$app->request->userIP;
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
