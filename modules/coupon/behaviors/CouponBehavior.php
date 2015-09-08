<?php
namespace app\modules\coupon\behaviors;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;

class CouponBehavior extends AttributeBehavior
{
    public $begin_dtAttribute = 'begin_dt';
    public $end_dtAttribute = 'end_dt';

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

        if ($model->hasAttribute($this->begin_dtAttribute) && $model->{$this->begin_dtAttribute} != null) {
            $model->{$this->begin_dtAttribute} = $this->getDateToTime($model->{$this->begin_dtAttribute});
        }
        if ($model->hasAttribute($this->end_dtAttribute) && $model->{$this->end_dtAttribute} != null) {
            $model->{$this->end_dtAttribute} = $this->getDateToTime($model->{$this->end_dtAttribute});
        }
    }

    public function beforeInsert()
    {
        $model = $this->owner;

        if ($model->hasAttribute($this->begin_dtAttribute) && $model->{$this->begin_dtAttribute} == null) {
            $model->{$this->begin_dtAttribute} = time();
        }
        if ($model->hasAttribute($this->end_dtAttribute) && $model->{$this->end_dtAttribute} == null) {
            $model->{$this->end_dtAttribute} = time();
        }
    }

    /**
     * @return int
     */
    protected function getDateToTime($date)
    {
        return (is_int($date) || is_float($date)) ? $date : strtotime($date);
    }
}
