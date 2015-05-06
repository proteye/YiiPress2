<?php
namespace app\modules\core\components\behaviors;

use yii\web\UploadedFile;
use yii\imagine\Image as Imagine;

class ImageUploadBehavior extends FileUploadBehavior
{
    public $attributeName = 'image';

    public $path = 'image';

    public $resizeOnUpload = true;

    public $resizeOptions = [];

    protected $defaultResizeOptions = [
        'width'   => 1200,
        'height'  => 1200,
        'quality' => 75,
    ];

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

        /* Delete old file */
        $this->deleteFile();

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
}
