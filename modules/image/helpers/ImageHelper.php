<?php

namespace app\modules\image\helpers;

use Yii;

class ImageHelper
{
    const SNAP = 'http://s.wordpress.com/mshots/v1/';

    public static function siteShot($url, $alt, $width = 303, $height = 225)
    {
        $img = '<img src="' . self::SNAP . urlencode($url) . '?w=' . $width . '&h=' . $height . '" alt="' . $alt . '"/>';

        return $img;
    }
}
