<?php
/**
 * Created by Edouard Kombo.
 * @Author: Edouard Kombo
 * Date: 20/04/2016
 * Time: 10:42
 */

namespace App\Lib;

/**
 * @author: Edouard Kombo
 * Class Helper
 * Bring helper methods
 * @package App\Lib
 */
class Helper
{
    /**
     * constructor.
     */
    public function __construct()
    {
    }

    /**
     * Create slug from a specified string
     *
     * @param $text string
     * @return String
     */
    static public function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text))
        {
            return 'n-a';
        }

        return (string) $text;

    }
}