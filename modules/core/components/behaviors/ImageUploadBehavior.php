<?php
namespace app\modules\core\components\behaviors;

class ImageUploadBehavior extends FileUploadBehavior
{
    public $attributeName = 'image';

    public $path = 'image';

    /**
     * @return string
     */
    public function getImageUrl()
    {
        return $this->getUploadUrl() . '/' . $this->owner->{$this->attributeName};
    }
}
