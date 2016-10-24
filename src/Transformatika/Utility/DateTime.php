<?php

namespace Transformatika\Utility;

class DateTime
{
    /**
     * Convert month into romans
     * @param  integer $number month in integer
     * @return string
     */
    public function monthInRoman($number)
    {
        $number = intval($number);
        $romanNumbers = array(
                1  => 'I',
                2  => 'II',
                3  => 'III',
                4  => 'IV',
                5  => 'V',
                6  => 'VI',
                7  => 'VII',
                8  => 'VIII',
                9  => 'IX',
                10 => 'X',
                11 => 'XI',
                12 => 'XII');
        if ($number >= 0 && $number <= 12) {
            return $romanNumbers[$number];
        } else {
            return $number;
        }
    }


    /**
     * Human time different
     * @param  Timestamp / Date
     * @return String
     */
    public function humanTimeDiff($time, $chunk = false)
    {
        if (!is_integer($time)) {
            $time = strtotime($time);
        }
        if ($chunk) {
            $different = time() - $time;

            $seconds = $different;
            $minutes = round($different / 60);
            $hours = round($different / 3600);
            $days = round($different / 86400);
            $weeks = round($different / 604800);
            $months = round($different / 2419200);
            $years = round($different / 29030400);

            if ($seconds <= 60) {
                if ($seconds == 1) {
                    return "$seconds second ago";
                } else {
                    return "$seconds seconds ago";
                }
            } elseif ($minutes <= 60) {
                if ($minutes == 1) {
                    return "$minutes minute ago";
                } else {
                    return "$minutes minutes ago";
                }
            } elseif ($hours <= 24) {
                if ($hours == 1) {
                    return "$hours hour ago";
                } else {
                    return "$hours hours ago";
                }
            } elseif ($days <= 7) {
                if ($days = 1) {
                    return "$days day ago";
                } else {
                    return "$days days ago";
                }
            } elseif ($weeks <= 4) {
                if ($weeks == 1) {
                    return "$weeks week ago";
                } else {
                    return "$weeks weeks ago";
                }
            } elseif ($months <= 12) {
                if ($months == 1) {
                    return "$months month ago";
                } else {
                    return "$months months ago";
                }
            } else {
                if ($years == 1) {
                    return "$years year ago";
                } else {
                    return "$years years ago";
                }
            }
        } else {
            $dtF = new \DateTime('@0');
            $dtT = new \DateTime("@$time");
            return $dtF->diff($dtT)->format('%a days, %h hours, %i minutes and %s seconds');
        }
    }

    /**
     * Generate date array
     * @param  Date $begin Date start
     * @param  Date $end   Date end
     * @return array       array date
     */
    public function dateArray($begin, $end, $format = 'Y-m-d')
    {
        $begin = new \DateTime($begin);
        $end = new \DateTime($end);
        $end = $end->modify('+1 day');

        $interval = new \DateInterval('P1D');
        $daterange = new \DatePeriod($begin, $interval, $end);

        $dateArray = array();
        foreach ($daterange as $date) {
            $dateArray[] = $date->format($format);
        }

        return $dateArray;
    }

    /**
     * Generate month number array
     * @param  Integer $begin month start
     * @param  Integer $end   month end
     * @param  string $year  Year
     * @return array
     */
    public function monthArray($begin, $end, $year = '')
    {
        if (empty($year)) {
            $year = date('Y');
        }
        $dateArray = array();
        for ($i = $begin; $i <= $end; ++$i) {
            $dateArray[] = $i;
        }
        return $dateArray;
    }

    /**
     * Generate Year array
     * @param  integer $begin [description]
     * @param  integer  $end   [description]
     * @return array        [description]
     */
    public function yearArray($begin, $end)
    {
        $dateArray = array();
        for ($i = $begin; $i <= $end; ++$i) {
            $dateArray[] = $i;
        }
        return $dateArray;
    }

    public function prevDay($date, $format = 'Y-m-d')
    {
        $date = new \DateTime($date);
        $date->modify('tomorrow');
        return $date->format($format);
    }

    public function nextDay($date, $format = 'Y-m-d')
    {
        $date = new \DateTime($date);
        $date->modify('yesterday');
        return $date->format($format);
    }

    public function isKabisat($tahun)
    {
        if ((($tahun % 4 == 0) && ($tahun % 100 != 0)) || ($tahun % 400 == 0)) {
            return true;
        } else {
            return false;
        }
    }

    public function isLeapYear($year)
    {
        return self::isKabisat($year);
    }
}
