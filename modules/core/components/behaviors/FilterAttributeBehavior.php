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

    public $contentAttribute = 'content';

    public $adsenseScript = null;

    /**
     * @return array
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeInsert',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeUpdate',
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

        if ($model->hasAttribute($this->contentAttribute)) {
            $model->{$this->contentAttribute} = $this->getContentWithAdsense();
        }
    }

    public function beforeUpdate()
    {
        $model = $this->owner;

        if ($model->hasAttribute($this->contentAttribute)) {
            $model->{$this->contentAttribute} = $this->getContentWithAdsense();
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

    /**
     * @return string
     */
    protected function getContentWithAdsense()
    {
        $model = $this->owner;
        $old_content = $model->{$this->contentAttribute};
        $length = mb_strlen($old_content);
        $hpos = mb_strrpos($old_content, '<h2>');
        $lpos = mb_strpos($old_content, '<h2>', ceil($length / 2));
        $pos = ($hpos < $lpos) ? $hpos : $lpos;
        $content = substr_replace($old_content, $this->adsenseScript, $pos, 0);

        return $content;
    }
}
