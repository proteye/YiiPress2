<?php
namespace app\modules\core\components\behaviors;

use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;

class RpcPingBehavior extends Behavior
{
    public $titleAttribute = 'title';
    public $urlAttribute = 'url';

    /**
     * @return array
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'afterInsert',
        ];
    }

    public function afterInsert()
    {
        $this->pingPage();
    }

    protected function pingPage()
    {
        $model = $this->owner;
        Yii::$app->rpcManager->pingPage($model->{$this->titleAttribute}, $model->{$this->urlAttribute});
    }
}
