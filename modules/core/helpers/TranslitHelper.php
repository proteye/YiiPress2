<?php

namespace app\modules\core\helpers;

use Yii;
use yii\helpers\Inflector;

class TranslitHelper
{
    public static function isEnglish($string)
    {
        return preg_match('/[A-Za-z]+/u', $string);
    }

    public static function convert($string)
    {
        if (self::isEnglish($string)) {
            $result = iconv("UTF-8", "UTF-8//IGNORE", strtr($string, self::russian_array()));
            $result = str_replace('.', ' ', $result);
        } else {
            $result = Inflector::camel2words(Inflector::slug($string));
        }
        return $result;
    }

    public static function russian_array()
    {
        return [
            "A"=>"А","a"=>"а",
            "B"=>"Б","b"=>"б",
            "C"=>"К","c"=>"ц",
            "D"=>"Д","d"=>"д",
            "E"=>"Э","e"=>"е",
            "F"=>"Ф","f"=>"ф",
            "G"=>"Г","g"=>"г",
            "H"=>"Х","h"=>"х",
            "I"=>"И","i"=>"и",
            "J"=>"Дж","j"=>"дж",
            "K"=>"К","k"=>"к",
            "L"=>"Л","l"=>"л",
            "M"=>"М","m"=>"м",
            "N"=>"Н","n"=>"н",
            "O"=>"О","o"=>"о",
            "P"=>"П","p"=>"п",
            "Q"=>"Кь","q"=>"кь",
            "R"=>"Р","r"=>"р",
            "S"=>"С","s"=>"с",
            "T"=>"Т","t"=>"т",
            "U"=>"У","u"=>"у",
            "V"=>"В","v"=>"в",
            "W"=>"В","w"=>"в",
            "X"=>"Кс","x"=>"кс",
            "Y"=>"И","y"=>"й",
            "Z"=>"З","z"=>"з",
        ];
    }
}
