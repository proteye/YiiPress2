<?php

namespace app\modules\core\helpers;

use Yii;

class TextHelper
{
    /**
     * @return string
     */
    public static function getQuote($text, $num = 145)
    {
        $quote = mb_substr($text, 0, $num, 'utf-8');
        $quote .= (mb_strlen($text, 'utf-8') > $num) ? '...' : '';
        return $quote;
    }

    public static function getAlphabetArray()
    {
        $num_arr = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
        $en_arr = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T',
            'U', 'V', 'W', 'X', 'Y', 'Z'];
        $ru_arr = ['А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т',
            'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я'];
        return array_merge($num_arr, $en_arr, $ru_arr);
    }
}
