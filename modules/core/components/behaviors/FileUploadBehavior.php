<?php
namespace app\modules\core\components\behaviors;

use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

class FileUploadBehavior extends Behavior
{
    public $attributeName = 'file';

    public $path = 'files';

    public $minSize = 0;

    public $maxSize = 5368709120; /* 5 Mb */

    public $types = 'jpg, jpeg, gif, png';

    public $file;

    public $filename;

    /**
     * @return array
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeInsert',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeUpdate',
            ActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete',
        ];
    }

    /**
     * Before validate
     */
    public function beforeValidate()
    {
        $model = $this->owner;

        if ($model->{$this->attributeName} instanceof UploadedFile) {
            $this->file = $model->{$this->attributeName};
            $this->filename = $this->file->name;
        }
        elseif ($this->file = UploadedFile::getInstance($model, $this->attributeName)) {
            $this->filename = $this->file->name;
        }
    }

    /**
     * Before insert
     */
    public function beforeInsert()
    {
        $this->saveFile();
    }

    /**
     * Before update
     */
    public function beforeUpdate()
    {
        /* If not change then set old attribute filename */
        $model = $this->owner;
        $model->{$this->attributeName} = $model->getOldAttribute($this->attributeName);

        if ($this->file !== null) {
            $this->saveFile();
        }
    }

    /**
     * Before delete
     */
    public function beforeDelete()
    {
        $this->deleteFile();
    }

    /**
     * Save file to disk
     */
    protected function saveFile()
    {
        $model = $this->owner;
        $file = $this->file;

        if (!$file instanceof UploadedFile) {
            return;
        }

        /* Delete old file */
        $this->deleteFile();

        /* Check and create target directory */
        if (!is_dir($this->uploadPath)) {
            mkdir($this->uploadPath, 0755, true);
        }

        /* Save file or generate new name as filename_1,2,3..n */
        $i = 1;
        while(file_exists($this->filePath) || is_dir($this->filePath))
        {
            $this->filename = $file->baseName . '_' . $i++ . '.' . $file->extension;
        }
        $file->saveAs($this->filePath);

        /* Set new filename to model->attribute */
        $model->{$this->attributeName} = $this->filename;
    }

    /**
     * Delete file
     */
    protected function deleteFile()
    {
        $model = $this->owner;

        if (!$old_filename = $model->getOldAttribute($this->attributeName)) {
            return;
        }

        if (@is_file($this->getFilePath($old_filename))) {
            @unlink($this->getFilePath($old_filename));
        }
    }

    /**
     * @return string
     */
    protected function getBasePath()
    {
        $module = Yii::$app->getModule('core');
        return Yii::getAlias('@webroot' . DIRECTORY_SEPARATOR . $module->uploadPath);
    }

    /**
     * @return string
     */
    protected function getUploadPath()
    {
        return $this->getBasePath() . DIRECTORY_SEPARATOR . $this->path;
    }

    /**
     * @return string
     */
    protected function getFilePath($filename = null)
    {
        $filename = ($filename === null) ? $this->filename : $filename;

        return $this->getUploadPath() . DIRECTORY_SEPARATOR . $filename;
    }

    /**
     * @return string
     */
    protected function getUploadUrl()
    {
        $module = Yii::$app->getModule('core');
        return Yii::$app->request->baseUrl . '/' . $module->uploadPath . '/' . $this->path;
    }

    /**
     * @return string
     */
    public function getFileUrl()
    {
        return $this->getUploadUrl() . '/' . $this->owner->{$this->attributeName};
    }
}
