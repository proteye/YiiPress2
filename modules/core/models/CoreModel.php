<?php

namespace app\modules\core\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

class CoreModel extends ActiveRecord
{
    public static function find()
    {
        return new CoreQuery(get_called_class());
    }
}

class CoreQuery extends ActiveQuery
{
    public function active($status = 1)
    {
        return $this->andWhere(['status' => $status]);
    }
}