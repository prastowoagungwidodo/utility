<?php
namespace Transformatika\Utility;

class Str
{
    public static function validateEmail($str)
    {
        return filter_var($str, FILTER_VALIDATE_EMAIL);
    }

    public static function validateIP($str)
    {
        return filter_var($str, FILTER_VALIDATE_IP);
    }

    /**
     * Generate ID
     * @return String
     */
    public static function generateId()
    {
        usleep(1);
        $microtime         = microtime(true);
        $includeFloatMtime = str_replace(array('.', '.'), '', $microtime);
        $random            = mt_rand(1000, 8888);
        $uniqid            = ($includeFloatMtime + $random);
        return base_convert($uniqid, 10, 36);
    }

    /**
     * Limit Character
     * @param  String
     * @param  Integer
     * @return String
     */
    public static function limitChar($content, $limit = 100)
    {
        if (strlen($content) <= $limit) {
            return $content;
        } else {
            $hasil = substr($content, 0, $limit);
            return $hasil . "...";
        }
    }

    /**
     * Limit Word
     * @param  String
     * @param  Integer
     * @return String
     */
    public static function limitWord($string, $limit = 10)
    {
        $words = explode(" ", $string);
        return implode(" ", array_splice($words, 0, $limit));
    }

    /**
     * URL Slugs
     * Original source: http://stackoverflow.com/questions/2955251/php-function-to-make-slug-url-string
     * @param  String
     * @return String
     */
    public static function urlSlug($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

        // trim
        $text = trim($text, '-');

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // lowercase
        $text = strtolower($text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

    /**
    * Validate POST
    */
    public function validatePOST($name)
    {
        if (isset($_POST[$name]) && !empty($_POST[$name])) {
            return true;
        } else {
            return false;
        }
    }

    /**
    * Generate Random Color
    */
    public static function randomColor()
    {
        mt_srand((double)microtime() * 1000000);
        $c = '';
        while (strlen($c) < 6) {
            $c .= sprintf("%02X", mt_rand(0, 255));
        }
        return '#' . $c;
    }
}
