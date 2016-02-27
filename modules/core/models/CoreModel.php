<?php

namespace app\modules\core\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

class CoreModel extends ActiveRecord
{
    const THUMB_WIDTH = 320;
    const THUMB_HEIGHT = 240;
    const THUMB_QUALITY = 75;

    public static function find()
    {
        return new CoreQuery(get_called_class());
    }

    /**
     * @return string
     */
    public static function uploadDirUrl($module_id, $withHost = true)
    {
        $core = Yii::$app->getModule('core');
        $module = Yii::$app->getModule($module_id);
        $url = $core->uploadPath . '/' . $module->uploadPath;
        if ($withHost) {
            $url = Yii::$app->request->hostInfo . '/' . $url;
        }
        return $url;
    }

    /**
     * @return string
     */
    public static function thumbDirUrl($module_id, $withHost = true)
    {
        $core = Yii::$app->getModule('core');
        $module = Yii::$app->getModule($module_id);
        $url = $core->uploadPath . '/' . $module->uploadPath . '/thumbs';
        if ($withHost) {
            $url = Yii::$app->request->hostInfo . '/' . $url;
        }
        return $url;
    }

    /**
     * @return string
     */
    public static function photoUrl($module_id, $filename)
    {
        if (!$filename) {
            return null;
        }
        return self::getUploadDirUrl($module_id) . '/' . $filename;
    }

    /**
     * @return string
     */
    public static function thumbUrl($module_id, $filename)
    {
        if (!$filename) {
            return null;
        }
        $arr_filename[0] = substr($filename, 0, strrpos($filename, '.'));
        $arr_filename[1] = substr($filename, strrpos($filename, '.'));
        $thumb_filename = $arr_filename[0] . '_' . static::THUMB_WIDTH . '_' . static::THUMB_HEIGHT . $arr_filename[1];

        return self::getThumbDirUrl($module_id) . '/' . $thumb_filename;
    }
}

class CoreQuery extends ActiveQuery
{
    public function active($status = 1)
    {
        return $this->andWhere(['status' => $status]);
    }
}