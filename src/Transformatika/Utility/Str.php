<?php
namespace Transformatika\Utility;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class Str
{
    public function validateEmail($str)
    {
        return filter_var($str, FILTER_VALIDATE_EMAIL);
    }

    public function validateIP($str)
    {
        return filter_var($str, FILTER_VALIDATE_IP);
    }

    public function getId($version = 4, $includeDash = false)
    {
        return $this->generateId($version, $includeDash);
    }

    public function getUUID($version = 4, $includeDash = false)
    {
        return $this->generateId($version, $includeDash);
    }

    public function uuid($version = 4, $includeDash = false)
    {
        return $this->generateId($version, $includeDash);
    }

    /**
     * Generate ID
     * @return String
     */
    public function generateId($version = 4, $includeDash = false)
    {

        try {
            switch ($version) {
                case 1:
                    $uuid = Uuid::uuid1();

                    break;

                case 3:
                    $uuid = Uuid::uuid3(Uuid::NAMESPACE_DNS, php_uname('n'));

                    break;
                case 5:
                    $uuid = Uuid::uuid5(Uuid::NAMESPACE_DNS, php_uname('n'));

                    break;
                default:
                    $uuid = Uuid::uuid4();

                    break;
            }

            return $includeDash ? $uuid->toString() : str_replace('-', '', $uuid->toString());

        } catch (UnsatisfiedDependencyException $e) {

            // Some dependency was not met. Either the method cannot be called on a
            // 32-bit system, or it can, but it relies on Moontoast\Math to be present.
            echo 'Caught exception: ' . $e->getMessage() . "\n";
            exit();
        }
    }

    /**
     * Limit Character
     * @param  String
     * @param  Integer
     * @return String
     */
    public function limitChar($content, $limit = 100)
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
    public function limitWord($string, $limit = 10)
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
    public function urlSlug($text)
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
    public function randomColor()
    {
        mt_srand((double)microtime() * 1000000);
        $c = '';
        while (strlen($c) < 6) {
            $c .= sprintf("%02X", mt_rand(0, 255));
        }
        return '#' . $c;
    }

    /**
     * Alias randomColor function
     * @return [type] [description]
     */
    public function generateRandomColor()
    {
        return $this->randomColor();
    }

    /**
     * Generate Random String
     * Exclude 0 and O
     * @param  integer $length            [description]
     * @param  [type]  $specialCharacters [description]
     * @return [type]                     [description]
     */
    public function generateRandomString($length = 32, $specialCharacters = true)
    {
        $digits = '';
        $chars = "abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789";

        if ($specialCharacters === true) {
            $chars .= "!?=/&+,.";
        }

        for ($i = 0; $i < $length; $i++) {
            $x = mt_rand(0, strlen($chars) - 1);
            $digits .= $chars{$x};
        }

        return $digits;
    }
}
