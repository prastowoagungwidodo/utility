<?php

namespace Transformatika\Utility;

class Number
{
    /**
     * Format bytes into human readable format
     * @param  [type]  $size      [description]
     * @param  integer $precision [description]
     * @return [type]             [description]
     */
    public function formatBytes($size, $precision = 2)
    {
        $base = log($size) / log(1024);
        $suffixes = array('', 'KB', 'MB', 'GB', 'TB');

        return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
    }

    /**
     * Zero Number
     * @param  Integer
     * @param  Integer
     * @return Integer
     */
    public function zeroNumber($number = 0, $length = 5)
    {
        $no = '';
        if (strlen($number) < $length) {
            for ($i = strlen($number); $i < $length; $i++) {
                $no .= '0';
            }
        }

        $no .= $number;
        return $no;
    }

    /**
     * Decimal To Alphanumeric
     * @param  Integer
     * @param  Integer
     * @param  Boolean
     * @return String
     */
    public function decimalToAlphanumeric($number, $base = 64, $index = false)
    {
        if (!$base) {
            $base = strlen($index);
        } elseif (!$index) {
            $index = substr("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ", 0, $base);
        }

        $out = "";

        for ($i = floor(log10($number) / log10($base)); $i >= 0; $i--) {
            $no = floor($num/pow($base, $i));
            $out = $out.substr($index, $no, 1);
            $number = $number - ($no * pow($base, $i));
        }
        return $out;
    }

    /**
     * Ordinal Number
     * @param  Integer
     * @return String
     */
    public function ordinalNumber($number)
    {
        $ends = array('th','st','nd','rd','th','th','th','th','th','th');
        if ((($number % 100) >= 11) && (($number%100) <= 13)) {
            return $number. 'th';
        } else {
            return $number. $ends[$number % 10];
        }
    }
}
