<?php
namespace Transformatika\Utility;

class Fn
{
    public static function generateId()
    {
        $microtime         = microtime(true);
        $includeFloatMtime = str_replace(array('.', '.'), '', $microtime);
        $random            = mt_rand(1000, 8888);
        $uniqid            = ($includeFloatMtime + $random);
        return base_convert($uniqid, 10, 36);
    }

    public static function limitChar($content, $limit)
    {
        if (strlen($content) <= $limit) {
            return $content;
        } else {
            $hasil = substr($content, 0, $limit);
            return $hasil . "...";
        }
    }

    public static function limitWord($string, $word_limit)
    {
        $words = explode(" ", $string);
        return implode(" ", array_splice($words, 0, $word_limit));
    }
}
