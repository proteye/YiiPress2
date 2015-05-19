<?php
namespace app\modules\core\components\behaviors;

use yii\web\UploadedFile;
use yii\imagine\Image as Imagine;

class ImageUploadBehavior extends FileUploadBehavior
{
    public $attributeName = 'image';

    public $path = 'image';

    public $thumb_path = 'thumbs';

    public $resizeOnUpload = true;

    public $resizeOptions = [];

    protected $defaultResizeOptions = [
        'width'   => 1200,
        'height'  => 800,
        'quality' => 75,
    ];

    /**
     * Before delete
     */
    public function beforeDelete()
    {
        $this->deleteImage();
    }

    /**
     * @return string
     */
    public function getImageUrl()
    {
        return $this->getUploadUrl() . '/' . $this->owner->{$this->attributeName};
    }

    /**
     * Save file to disk
     */
    protected function saveFile()
    {
        if (!$this->resizeOnUpload) {
            parent::saveFile();
            return;
        }

        $model = $this->owner;
        $file = $this->file;

        if (!$file instanceof UploadedFile) {
            return;
        }

        /* Delete old file and thumbs */
        $this->deleteImage();

        /* Check and create target directory */
        if (!is_dir($this->uploadPath)) {
            mkdir($this->uploadPath, 0755, true);
        }

        /* Merge options */
        $this->resizeOptions = array_merge(
            $this->defaultResizeOptions,
            $this->resizeOptions
        );
        $this->filename = $this->generateFilename();

        /* Resize proportional width and save */
        $width = $this->resizeOptions['width'];
        $height = $this->resizeOptions['height'];
        $img = Imagine::getImagine()->open($this->file->tempName);
        $size = $img->getSize();
        if ($size->getWidth() > $this->resizeOptions['width'] && $size->getHeight() <= $this->resizeOptions['height']) {
            $ratio = $size->getWidth()/$size->getHeight();
            $width = $this->resizeOptions['width'];
            $height = round($width/$ratio);
        } elseif ($size->getHeight() > $this->resizeOptions['height'] && $size->getWidth() <= $this->resizeOptions['width']) {
            $ratio = $size->getHeight()/$size->getWidth();
            $height = $this->resizeOptions['height'];
            $width = round($height/$ratio);
        }

        Imagine::thumbnail($this->file->tempName, $width, $height)
            ->save($this->getFilePath($this->filename), ['quality' => $this->resizeOptions['quality']])
        ;

        /* Set new filename to model->attribute */
        $model->{$this->attributeName} = $this->filename;
    }

    /**
     * Delete image
     */
    protected function deleteImage()
    {
        $model = $this->owner;

        if (!$old_filename = $model->getOldAttribute($this->attributeName)) {
            return;
        }

        if (@is_file($this->getFilePath($old_filename))) {
            @unlink($this->getFilePath($old_filename));
            /* Delete all thumbs of this image */
            $arr_filename = explode('.', $old_filename);
            array_map('unlink', glob($this->thumbDirPath . DIRECTORY_SEPARATOR . $arr_filename[0] . '*'));
        }
    }

    /**
     * @return string
     */
    protected function getThumbDirPath()
    {
        return $this->getUploadPath() . DIRECTORY_SEPARATOR . $this->thumb_path;
    }

    /**
     * @return string
     */
    protected function getThumbDirUrl()
    {
        return $this->getUploadUrl() . '/' . $this->thumb_path;
    }

    /**
     * @return string
     */
    public function getThumbUrl($width = 160, $height = 120, $quality = 75)
    {
        /* Thumb filename (ex. image_160_120.jpg) */
        $arr_filename = explode('.', $this->owner->{$this->attributeName});
        $filename = $arr_filename[0] . "_{$width}_{$height}." . $arr_filename[1];

        if (!@is_file($this->thumbDirPath . DIRECTORY_SEPARATOR . $filename)) {
            /* Check and create target directory */
            if (!is_dir($this->thumbDirPath)) {
                mkdir($this->thumbDirPath, 0755, true);
            }
            /* Create thumb */
            if (@is_file($this->filepath)) {
                Imagine::thumbnail($this->filePath, $width, $height)
                    ->save($this->thumbDirPath . DIRECTORY_SEPARATOR . $filename, ['quality' => $quality]);
            }
        }

        return $this->getThumbDirUrl() . '/' . $filename;
    }
}
