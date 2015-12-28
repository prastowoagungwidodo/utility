<?php
namespace Transformatika\Utility;
/*
 *---------------------------------------------------------------
 * GAK TAU NAMA FUNGSINYA?
 *---------------------------------------------------------------
 * Tenang bro, tinggal CTRL + F nama fungsinya
 * Atau cari manual aja, setiap fungsi udah ada comment tagnya
 *
 */
class Fn
{
    var $monthNames = 'January,February,March,April,May,June,July,August,September,October,November,December';
    var $dayNames = 'Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday';

    var $monthList = array(
        1  => 'January',
        2  => 'February',
        3  => 'March',
        4  => 'April',
        5  => 'May',
        6  => 'June',
        7  => 'July',
        8  => 'August',
        9  => 'September',
        10 => 'October',
        11 => 'November',
        12 => 'December',
    );

    /**
     * Generate ID
     * @return String
     */
    public static function generateId()
    {
        $microtime         = microtime(true);
        $includeFloatMtime = str_replace(array('.', '.'), '', $microtime);
        $random            = mt_rand(1000, 8888);
        $uniqid            = ($includeFloatMtime + $random);
        return base_convert($uniqid, 10, 36);
    }

    /**
     * Function to create and display error and success messages
     * @access public
     * @param string session name
     * @param string message
     * @param string display class
     * @return string message
     */
    public static function flash( $name = '', $message = '', $class = 'success fadeout-message' )
    {
        //We can only do something if the name isn't empty
        if( !empty( $name ) )
        {
            //No message, create it
            if( !empty( $message ) && empty( $_SESSION[$name] ) )
            {
                if( !empty( $_SESSION[$name] ) )
                {
                    unset( $_SESSION[$name] );
                }
                if( !empty( $_SESSION[$name.'_class'] ) )
                {
                    unset( $_SESSION[$name.'_class'] );
                }

                $_SESSION[$name]          = $message;
                $_SESSION[$name.'_class'] = $class;
            }
            //Message exists, display it
            elseif( !empty( $_SESSION[$name] ) && empty( $message ) )
            {
                $class   = !empty( $_SESSION[$name.'_class'] ) ? $_SESSION[$name.'_class'] : 'success';
                $message = $_SESSION[$name];
                echo '<div class="ui '.$class.' message">'.$message.'</div>';
                unset($_SESSION[$name]);
                unset($_SESSION[$name.'_class']);
            }
        }
    }
    
    public static function monthInRoman($number)
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
     * Limit Character
     * @param  String
     * @param  Integer
     * @return String
     */
    public static function limitChar($content, $limit)
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
    public static function limitWord($string, $word_limit)
    {
        $words = explode(" ", $string);
        return implode(" ", array_splice($words, 0, $word_limit));
    }

    /**
     * Zero Number
     * @param  Integer
     * @param  Integer
     * @return Integer
     */
    public static function zeroNumber($number = 0, $lenght = 5)
    {
        $no = '';
        if (strlen($number) < $lenght) {
            for ($i=strlen($number); $i < $lenght ; $i++) { 
                $no .= '0';
            }
        }

        $no .= $number;
        return $no;
    }

    /**
     * Timestamp
     * @param  Timestamp / Date
     * @return String
     */
    public static function timeStamp($time)
    {
        $different = time() - $time;

        $seconds = $different;
        $minutes = round($different / 60);
        $hours = round($different / 3600);
        $days = round($different / 86400);
        $weeks = round($different / 604800);
        $months = round($different / 2419200);
        $years = round($different / 29030400);

        //Second
        if ($seconds <= 60) {
            if ($seconds == 1) {
                return "$seconds second ago";
            } else {
                return "$seconds seconds ago";
            }
        }

        //Minutes
        elseif ($minutes <= 60) {
            if ($minutes == 1) {
                return "$minutes minute ago";
            } else {
                return "$minutes minutes ago";
            }
        }

        //Hours
        elseif ($hours <= 24) {
            if ($hours == 1) {
                return "$hours hour ago";
            } else {
                return "$hours hours ago";
            }
        }

        //Days
        elseif ($days <= 7) {
            if ($days = 1) {
                return "$days day ago";
            } else {
                return "$days days ago";
            }
        }

        //Weeks
        elseif ($weeks <= 4) {
            if ($weeks == 1) {
                return "$weeks week ago";
            } else {
                return "$weeks weeks ago";
            }
        }

        //Months
        elseif ($months <= 12) {
            if ($months == 1) {
                return "$months month ago";
            } else {
                return "$months months ago";
            }
        }

        //Years
        else {
            if ($years == 1) {
                return "$years year ago";
            } else {
                return "$years years ago";
            }
        }
    }

    /**
     * Byte Format
     * @param  Integer
     * @param  Integer
     * @return String
     */
    public static function bytesFormat($size, $precision = 2)
    {
        $base = log($size) / log(1024);
        $suffix = array('', 'KB', 'MB', 'GB', 'TB');

        return round(pow(1024, $base - floor($base)), $precision) . $suffix[floor($base)];
    }

    /**
     * Decimal To Alphanumeric
     * @param  Integer
     * @param  Integer
     * @param  Boolean
     * @return String
     */
    public static function decimalToAlphanumeric($number, $base = 64, $index = false)
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
     * Thumbnail
     * @param  String
     * @param  String
     * @param  String
     * @param  String
     * @return String
     */
    public static function makeThumbnail($uploadDir, $image, $thumbnailPath, $thumbnailWords)
    {
        $thumbnailWidth = 50;
        $thumbnailHeight = 50;
        $arrayImageDetail = getimagesize("$uploadDir"."$image");
        $originalWidth = $arrayImageDetail[0];
        $originalHeight = $arrayImageDetail[1];

        if ($originalWidth > $originalHeight) {
            $newWidth = $thumbnailWidth;
            $newHeight = intval($originalHeight * $newWidth / $originalWidth);
        } else {
            $newHeight = $thumbnailHeight;
            $newWidth = intval($originalWidth * $newHeight / $originalHeight);
        }

        $varX = intval(($thumbnailWidth - $newWidth) / 2);
        $varY = intval(($thumbnailHeight - $newHeight) / 2);

        if ($arrayImageDetail[2] == 1) {
            $imageType = "ImageGIF";
            $createFrom = "ImageCreateFromGIF";
        } elseif ($arrayImageDetail[2] == 2) {
            $imageType = "ImageJPEG";
            $createFrom = "ImageCreateFromJPEG";
        } elseif ($arrayImageDetail[2] == 3) {
            $imageType = "ImagePNG";
            $createFrom = "ImageCreateFromPNG";
        }

        if ($imageType) {
            $oldImage = $createFrom("$uploadDir"."$image");
            $newImage = imagecreatetruecolor($thumbnailWidth, $thumbnailHeight);
            imagecopyresized($newImage, $oldImage, $varX, $varY, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);
            $imageType($newImage, "$thumbnailPath"."$thumbnailWords".'_'."$image");
        }

        return "$thumbnailWords".'_'."$image";
    }

    /**
     * Resize (Sama dengan thumbnail)
     * @param  String
     * @param  String
     * @param  Integer
     * @param  Boolean
     * @return String
     */
    public static function resize($in_file, $out_file, $new_width, $new_height = false)
    {
        $image     = null;
        $extension = strtolower(preg_replace('/^.*\./', '', $in_file));
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                $image = imagecreatefromjpeg($in_file);
                break;
            case 'png':
                $image = imagecreatefrompng($in_file);
                break;
            case 'gif':
                $image = imagecreatefromgif($in_file);
                break;
        }
        if (!$image || !is_resource($image)) return false;


        $width  = imagesx($image);
        $height = imagesy($image);
        if ($new_height === false) {
            $new_height = (int)(($height * $new_width) / $width);
        }


        $new_image = imagecreatetruecolor($new_width, $new_height);
        imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

        $ret = imagejpeg($new_image, $out_file, 80);

        imagedestroy($new_image);
        imagedestroy($image);

        return $ret;
    }

    /**
     * Pre
     * @param  String
     * @return String
     */
    public static function pre($data)
    {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }

    /**
     * Month List
     * @param  Integer
     * @return String
     */
    public static function monthList($length = 0)
    {
        $monthNameArray = explode(',', $this->monthNames);
        for ($i = 1; $i <= 12; $i++) {
            if ($length == 0) {
                $monthList[$i] = trim($monthNameArray[$i - 1]);
            } else {
                $monthList[$i] = substr(trim($monthNameArray[$i - 1]), 0, $length);
            }
        }

        return $monthList;
    }

    /**
     * Day List
     * @param  Integer
     * @return String
     */
    public static function dayList($length = 0)
    {
        $dayNameArray = explode(',', $this->dayNames);
        for ($i = 1; $i <= 7; $i++) {
            if ($length == 0) {
                $dayList[$i] = trim($dayNameArray[$i - 1]);
            } else {
                $dayList[$i] = substr(trim($dayNameArray[$i - 1]), 0, $length);
            }
        }

        return $dayList;
    }

    /**
     * Validate Date Time SQL
     * @param  DateTime
     * @return Boolean
     */
    public static function isDateTimeSQL($dateTime)
    {
        if (preg_match('/[12][09][0-9]{2}[-](01|02|03|04|05|06|07|08|09|10|11|12)[-](0|1|2|3)[0-9] (0|1|2)[0-9]([:](0|1|2|3|4|5)[0-9]){2}/', $dateTime)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Validate Date SQL
     * @param  Date
     * @return Boolean
     */
    public static function isDateSQL($date)
    {
        if (@preg_match('/^([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})$/', $date)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Validate Date
     * @param  Date
     * @return boolean
     */
    public static function isValidDate($date)
    {
        $d = $this->splitDate($date, 'int');
        if ($d['year'] > 0 && $d['month'] > 0 && $d['date'] > 0) {
            return checkdate($d['month'], $d['date'], $d['year']);
        } else {
            return false;
        }
    }

    /**
     * Validate Time
     * @param  Time
     * @return boolean
     */
    public static function isValidTime($time)
    {
        if (preg_match('^(0|1|2)[0-9]([:](0|1|2|3|4|5)[0-9]){2}$', $time)) {
            $hour   = (int)substr($time, 0, 2);
            $minute = (int)substr($time, 3, 2);
            $second = (int)substr($time, 6, 2);
            if ($hour < 24 && $minute < 60 && $second < 60) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Validate DateTime
     * @param  DateTime
     * @return boolean
     */
    public static function isValidDateTime($dateTime)
    {
        $t = explode(' ', $dateTime);
        if ($this->isValidDate($t[0]) && $this->isValidDate($t[1])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Split Date
     * @param  DateTime
     * @param  String
     * @return Array
     */
    public static function splitDate($dateTime, $type = 'int')
    {
        $t  = explode(' ', $dateTime);
        $t1 = explode('-', $t[0]);
        $t2 = explode(':', $t[1]);
        if ($type == 'int') {
            $x['date']   = (int)$t1[2];
            $x['month']  = (int)$t1[1];
            $x['year']   = (int)$t1[0];
            $x['hour']   = (int)$t2[0];
            $x['minute'] = (int)$t2[1];
            $x['second'] = (int)$t2[2];
        } else { // string
            $x['date']   = $t1[2];
            $x['month']  = $t1[1];
            $x['year']   = $t1[0];
            $x['hour']   = $t2[0];
            $x['minute'] = $t2[1];
            $x['second'] = $t2[2];
        }

        return $x;
    }

    /**
     * Unix Time
     * @param  DateTime
     * @return UnixTime
     */
    public static function unixTime($dateTime)
    {
        $t = $this->splitDate($dateTime, 'int');

        return mktime($t['hour'], $t['minute'], $t['second'], $t['month'], $t['date'], $t['year']);
    }

    /**
     * Previous Date
     * @param  Date
     * @param  integer
     * @param  string
     * @return Date
     */
    public static function prevDate($date, $interval = 1, $newFormat = "Y-m-d")
    {
        $x            = $this->splitDate($date, 'int');
        $unixTime     = date("U", mktime(1, 0, 0, $x['month'], $x['date'], $x['year']));
        $sehari       = 24 * 60 * 60;
        $prevUnixTime = $unixTime - ($sehari * $interval);

        return date($newFormat, $prevUnixTime);
    }

    /**
     * Next Date
     * @param  Date
     * @param  integer
     * @param  string
     * @return Date
     */
    public static function nextDate($date, $interval = 1, $newFormat = 'Y-m-d')
    {
        $x            = $this->splitDate($date, 'int');
        $unixTime     = date("U", mktime(1, 0, 0, $x['month'], $x['date'], $x['year']));
        $sehari       = 24 * 60 * 60;
        $nextUnixTime = $unixTime + ($sehari * $interval);

        return date($newFormat, $nextUnixTime);
    }

    /**
     * Date Range Array
     * @param  Date
     * @param  Date
     * @param  string
     * @return Array
     */
    public static function dateRangeArray($strDateFrom, $strDateTo, $dateFormat = 'Y-m-d')
    {
        //Mengambil nilai tanggal dari dua tanggal dengan format YYYY-MM-DD

        $arrayRange  = array();
        $t1        = $this->splitDate($strDateFrom, 'int');
        $t2        = $this->splitDate($strDateTo, 'int');
        $iDateFrom = mktime(1, 0, 0, $t1['month'], $t1['date'], $t1['year']);
        $iDateTo   = mktime(1, 0, 0, $t2['month'], $t2['date'], $t2['year']);

        if ($iDateTo >= $iDateFrom) {
            array_push($arrayRange, date($dateFormat, $iDateFrom)); // first entry

            while ($iDateFrom < $iDateTo) {
                $iDateFrom += 86400; // add 24 hours
                array_push($arrayRange, date($dateFormat, $iDateFrom));
            }
        }

        return $arrayRange;
    }

    /**
     * Week Number
     * @param  Date
     * @return Date
     */
    public static function weekNumber($date)
    {
        /**
         * fungsi ini untuk mendapatkan nilai minggu ke berapa tanggal tersebut.
         * Week dimulai pada hari senin
         * $date = YYYY-mm-dd
         * returned type: integer
         */
        $x = $this->splitDate($date, 'int');
        //print_r($x);
        $weekNo = date("W", mktime(1, 0, 0, $x['month'], $x['date'], $x['year']));

        return $weekNo;
    }

    /**
     * Date To SQL
     * @param  Date - Year
     * @param  Date - Month
     * @param  Date - Day
     * @param  Date - Year
     * @param  Date - Year
     * @return DateSQL
     */
    public static function dateToSQL($tahun, $bulan, $tanggal, $minTahun = "", $maxTahun = "")
    {
        $tahun   = (int)$tahun;
        $bulan   = (int)$bulan;
        $tanggal = (int)$tanggal;
        if ($tanggal <= 0 || $tanggal > 31) {
            $tanggalX = "01";
        } else if ($tanggal < 10) {
            $tanggalX = "0" . $tanggal;
        } else {
            $tanggalX= $tanggal;
        }
        if ($bulan <= 0 || $bulan > 12) {
            $bulanX = "01";
        } else if ($bulan < 10) {
            $bulanX = "0" . $bulan;
        } else {
            $bulanX = $bulan;
        }
        if ($minTahun != "") {
            if ($tahun >= $minTahun) {
                if ($maxTahun != "") {
                    if ($tahun <= $maxTahun) {
                        $tahunX = $tahun;
                    } else {
                        $tahunX = date("Y");
                    }
                } else {
                    $tahunX = $tahun;
                }
            } else {
                $tahunX = date("Y");
            }
        } else {
            if (strlen($tahun) == 4) {
                $tahunX = $tahun;
            } else {
                $tahunX = date("Y");
            }
        }
        $tanggalSql = $tahunX . "-" . $bulanX . "-" . $tanggalX;

        return $tanggalSql;
    }

    /**
     * Display Date
     * @param  Date
     * @return String
     */
    public static function displayDate($date)
    {
        $namaBulan = $this->monthList();
        $dateInt   = $this->splitDate($date);

        return $namaBulan[$dateInt['month']] . ' ' . $dateInt['date'] . ', ' . $dateInt['year'];
    }

    /**
     * Display Date Time
     * @param  Date
     * @return String
     */
    public static function displayDateTime($date)
    {
        $namaBulan  = $this->monthList();
        $dateInt    = $this->splitDate($date);
        $dateString = $this->splitDate($date, 'string');

        return $namaBulan[$dateInt['month']] . ' ' . $dateInt['date'] . ', ' . $dateInt['year'] . ' ' . $dateString['hour'] . ':' . $dateString['minute'] . ':' . $dateString['second'];
    }

    /**
     * Time Different
     * @param  Time
     * @param  Time
     * @return String / Integer
     */
    public static function timeDifferent($time1, $time2)
    {
        $intTime1 = $this->unixTime($time1);
        $intTime2 = $this->unixTime($time2);
        $timeDiff = $intTime2 - $intTime1;

        return $timeDiff;
    }

    /**
     * Current Period
     * @param  Date
     * @return Date
     */
    public static function currentPeriod($period)
    {
        $thisMonth = date('n');

        return ceil($thisMonth / $period);
    }

    /**
     * Previous Period
     * @param  Date
     * @param  Date - Year
     * @param  Integer
     * @return Array
     */
    public static function prevPeriod($period, $year, $number)
    {
        if ($number > 1) {
            return array('year' => $year, 'number' => ($number - 1));
        } else {
            $maxPeriod = 12 / $period;

            return array('year' => ($year - 1), 'number' => $maxPeriod);
        }
    }

    /**
     * Next Period
     * @param  Date
     * @param  Date - Year
     * @param  Integer
     * @return Array
     */
    public static function nextPeriod($period, $year, $number)
    {
        $maxPeriod = 12 / $period;
        if ($number < $maxPeriod) {
            return array('year' => $year, 'number' => ($number + 1));
        } else {
            return array('year' => ($year + 1), 'number' => 1);
        }
    }

    /**
     * @param  Date
     * @param  integer
     * @return Array
     */
    public static function periodRange($period, $length = 0)
    {
        $period     = (int)$period;
        $monthNames = $this->monthList($length);
        $result     = array();
        switch ($period) {
            case 1:
                $result = $monthNames;
                break;
            case 2:
                $result[1] = $monthNames[1] . '-' . $monthNames[2];
                $result[2] = $monthNames[3] . '-' . $monthNames[4];
                $result[3] = $monthNames[5] . '-' . $monthNames[6];
                $result[4] = $monthNames[7] . '-' . $monthNames[8];
                $result[5] = $monthNames[9] . '-' . $monthNames[10];
                $result[6] = $monthNames[11] . '-' . $monthNames[12];
                break;
            case 3:
                /* $result[1] = $monthNames[1].'-'.$monthNames[3];
                  $result[2] = $monthNames[4].'-'.$monthNames[6];
                  $result[3] = $monthNames[7].'-'.$monthNames[9];
                  $result[4] = $monthNames[10].'-'.$monthNames[12]; */
                $result[1] = 'TW1';
                $result[2] = 's/d TW2';
                $result[3] = 's/d TW3';
                $result[4] = 'Tahunan';
                break;
            case 4:
                $result[1] = $monthNames[1] . '-' . $monthNames[4];
                $result[1] = $monthNames[5] . '-' . $monthNames[8];
                $result[1] = $monthNames[9] . '-' . $monthNames[12];
                break;
            case 6:
                $result[1] = 's/d TW2';
                $result[2] = 'Tahunan';
                break;
            default:
                $result[1] = $monthNames[1] . '-' . $monthNames[12];
                break;
        }

        return $result;
    }

    /**
     * Period Display
     * @param  Date
     * @param  Date - Year
     * @param  Integer
     * @param  integer
     * @return String
     */
    public static function periodDisplay($period, $year, $number, $length = 0)
    {
        $periodRange = $this->periodRange($period, $length);

        return $periodRange[$number] . ', ' . $year;
    }

    /**
     * Validate Date Form
     * @param  Date
     * @return Date
     */
    public static function validDateForm($date)
    {
        $isOK = true;
        if (preg_match("/([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{2,4})/", $date)) {
            list($d, $m, $y) = explode("/", $date);
            $month = (int)$m;
            $day   = (int)$d;
            $year  = (int)$y;
            if (!checkdate($month, $day, $year)) {
                $isOK = false;
            }
        } else {
            $isOK = false;
        }
        if ($isOK == false) {
            $date = date('d/m/Y');
        }

        return $date;
    }

    /**
     * Validate DateForm Check
     * @param  Date
     * @return boolean
     */
    public static function isValidDateForm($date)
    {
        if (preg_match("/([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{2,4})/", $date)) {
            list($d, $m, $y) = explode("/", $date);
            $month = (int)$m;
            $day   = (int)$d;
            $year  = (int)$y;
            if (!checkdate($month, $day, $year)) {
                return false;
            }
        } else {
            return false;
        }

        return true;
    }

    /**
     * Date Form To SQL
     * @param  Date
     * @return DateSQL
     */
    public static function dateFormToSQL($date)
    {
        list($d, $m, $y) = @explode("/", $date);
        $month = (int)$m;
        $day   = (int)$d;
        $year  = (int)$y;
        if (checkdate($month, $day, $year)) {
            if ($day < 10) {
                $day = '0' . $day;
            }
            if ($month < 10) {
                $month = '0' . $month;
            }

            return $y . '-' . $month . '-' . $day;
        } else {
            return '';
        }
    }

    /**
     * SQL Date To Date Form
     * @param  Date
     * @return Date
     */
    public static function dateSQLToForm($date)
    {
        //if (!empty($date) && $date != "0000-00-00" && $date != "0000-00-00 00:00:00"){
        if ($this->isDateSQL($date)) {
            $arr = explode('-', $date);
            $thn = (int)$arr[0];
            $bln = (int)$arr[1];
            $tgl = (int)$arr[2];

            if ($thn > 2000 AND $bln <= 12 AND $bln >= 1 AND $tgl <= 31 AND $tgl >= 1) {
                if ($tgl < 10) $tgl = '0' . $tgl;
                if ($bln < 10) $bln = '0' . $bln;
                if ($thn < 10) $thn = '0' . $thn;

                return $tgl . '/' . $bln . '/' . $thn;
            }
        }

        return '';
    }

    /**
     * Max Date In Year
     * @param  Date - Year
     * @return Integer
     */
    public static function maxDateInYear($year)
    {
        if (($year % 4) == 0) {
            return 366;
        } else {
            return 365;
        }
    }

    /**
     * Max Date In Month
     * @param  Date - Month
     * @param  Date - Year
     * @return Integer
     */
    public static function maxDateInMonth($month, $year)
    {
        switch ($month) {
            case 1:
                return 31;
            case 2:
                if (($year % 4) == 0) {
                    return 29;
                } else {
                    return 28;
                }
            case 3:
                return 31;
            case 4:
                return 30;
            case 5:
                return 31;
            case 6:
                return 30;
            case 7:
                return 31;
            case 8:
                return 31;
            case 9:
                return 30;
            case 10:
                return 31;
            case 11:
                return 30;
            case 12:
                return 31;
        }
    }

    /**
     * Date Range In Week
     * @param  integer
     * @param  integer
     * @return Array
     */
    public static function dateRangeInWeek($week = 0, $year = 0)
    {
        /*
          Fungsi ini untuk mendapatkan nilai tanggal berapa saja dalam minggu tertentu
          Tanggal dimulai pada hari senin pada minggu tersebut.
          returned type: array
         */
        $year = (int)$year;
        $week = (int)$week;
        if ($year < 1970) { // minimal unix time year 1970
            $year = date("Y");
            $week = date("W");
        }
        if ($week <= 0 || $week > 54) {
            $week = date("W");
            $year = date("Y");
        }
        //cek apakah tanggal 1 masuk minggu pertama atau minggu terakhir tahun sebelumnya
        $mingguTanggalAwalTahun   = date("W", mktime(1, 0, 0, 1, 1, $year));
        $jumlahDetikDalamSehari   = 24 * 60 * 60;
        $jumlahDetikDalamSeminggu = $jumlahDetikDalamSehari * 7;
        $hariAwalTahun            = date("w", mktime(1, 0, 0, 1, 1, $year));
        // Hari ke 0/7->minggu 1->Senin 2->Selasa 3->Rabu 4->Kamis 5->Jumat 6->Sabtu
        $detikAwalTahun = date("U", mktime(1, 0, 0, 1, 1, $year));
        if ($hariAwalTahun == 0) {
            $hariAwalTahun = 7; // ISO-8601 numeric representation
        }
        $jumlahHariPadaMingguPertama = (7 - $hariAwalTahun) + 1; //Long variable name but easily understood :P
        $detikTambahan               = (($week - 1) * $jumlahDetikDalamSeminggu) + ($jumlahHariPadaMingguPertama * $jumlahDetikDalamSehari);
        if ($mingguTanggalAwalTahun == 1) {
            $tanggalUnixSenin = $detikAwalTahun + $detikTambahan;
        } else {
            $tanggalUnixSenin = $detikAwalTahun + $detikTambahan + $jumlahDetikDalamSeminggu;
        }
        $dateRange = array();
        for ($i = 1; $i <= 7; $i++) {
            $dateRange[$i] = date("Y-m-d", ($tanggalUnixSenin + (($i - 1) * $jumlahDetikDalamSehari) - $jumlahDetikDalamSeminggu));
        }

        return $dateRange;
    }

    /**
     * Week Range In Month
     * @param  Date - Month
     * @param  Date - Year
     * @return Array
     */
    public static function weekRangeInMonth($month, $year)
    {
        $year  = (int)$year;
        $month = (int)$month;
        if ($year < 1970) { // minimal unix time year 1970
            $month = date("n");
            $year  = date("Y");
        }
        if ($month <= 0 || $month > 12) {
            $month = date("n");
            $year  = date("Y");
        }
        if ($month < 10) {
            $month2 = '0' . $month;
        } else {
            $month2 = $month;
        }
        $weekAwal  = (int)$this->weekNumber($year . '-' . $month2 . '-01');
        $weekAkhir = (int)$this->weekNumber($year . '-' . $month2 . '-' . $this->maxDateInMonth($month, $year));
        /*
          echo $year.'-'.$month2.'-01';
          echo '<br />';
          echo $weekAwal;
          echo '<br />';
          echo $year.'-'.$month2.'-'.$this->getMaxDateInMonth($month,$year);
          echo '<br />';
          echo $weekAkhir;
         */
        if ($weekAwal > $weekAkhir) {
            $weekAwal = 1;
        }
        $a = 0;

        for ($i = $weekAwal; $i <= $weekAkhir; $i++) {
            $a++;
            $weekRange[$a] = (int)$i;
        }

        return $weekRange;
    }

    /**
     * Previous Week
     * @param  Date - Week
     * @param  Date - Year
     * @return Array
     */
    public static function prevWeek($week, $year)
    {
        if ($year >= 1970) {
            if ($week > 1) {
                $prevWeek = $week - 1;
                $yearWeek = $year;
            } else {
                $maxWeek = (int)date("W", mktime(1, 0, 0, 12, 31, $year - 1));
                if ($maxWeek == 1) { // tanggal 31 desember bukan hari minggu dianggap minggu 1 tahun berikutnya
                    $detikSeminggu = 7 * 24 * 60 * 60;
                    $prevWeek      = (int)date("W", (mktime(1, 0, 0, 12, 31, $year) - $detikSeminggu));
                } else {
                    $prevWeek = date("W", mktime(1, 0, 0, 12, 31, $year - 1));
                }
                $yearWeek = $year - 1;
            }
        }

        return array('week' => $prevWeek, 'year' => $yearWeek);
    }

    /**
     * Next Week
     * @param  Date - Week
     * @param  Date - Year
     * @return Array
     */
    public static function nextWeek($week, $year)
    {
        if ($year >= 1970) {
            $maxWeek = (int)date("W", mktime(1, 0, 0, 12, 31, $year));
            if ($maxWeek == 1) { // tanggal 31 desember bukan hari minggu dianggap minggu 1 tahun berikutnya
                $detikSeminggu = 7 * 24 * 60 * 60;
                $maxWeek       = (int)date("W", (mktime(1, 0, 0, 12, 31, $year) - $detikSeminggu));
            }
            if ($week >= $maxWeek) {
                $nextWeek = 1;
                $yearWeek = $year + 1;
            } else {
                $nextWeek = $week + 1;
                $yearWeek = $year;
            }
        }

        return array('week' => $nextWeek, 'year' => $yearWeek);
    }

    /**
     * Previous Month
     * @param  Date - Month
     * @param  Date - Year
     * @return Array
     */
    public static function prevMonth($month, $year)
    {
        if ($year >= 1970) {
            if ($month == 1) {
                $prevMonth = 12;
                $yearMonth = $year - 1;
            } else {
                $prevMonth = $month - 1;
                $yearMonth = $year;
            }
        }

        return array('month' => $prevMonth, 'year' => $yearMonth);
    }

    /**
     * Next Month
     * @param  Date - Month
     * @param  Date - Year
     * @return Array
     */
    public static function nextMonth($month, $year)
    {
        if ($year >= 1970) {
            if ($month >= 12) {
                $nextMonth = 1;
                $yearMonth = $year + 1;
            } else {
                $nextMonth = $month + 1;
                $yearMonth = $year;
            }
        }

        return array('month' => $nextMonth, 'year' => $yearMonth);
    }

    /**
     * Date SQL to Form
     * @param  DateTime
     * @return String
     */
    public static function getDateSQLToForm($datetime)
    { 
        //YYYY-mm-dd
        $t = $this->splitDate($datetime, false);

        return $t['date'] . '/' . $t['month'] . '/' . $t['year'];
    }

    /**
     * Time To Sec
     * @param  Time
     * @return String
     */
    public static function timeToSec($time)
    {
        // hh:mm:ss
        $split_time = explode(':', $time);
        $hour       = $split_time[0] * 3600;
        $minutes    = $split_time[1] * 60;
        $second     = $split_time[2] * 1;

        $timetosec = (int)($hour + $minutes + $second);

        return $timetosec;
    }

    /**
     * Sec to Time
     * @param  Sec
     * @return Time
     */
    public static function secToTime($sec)
    {
        // int sec
        $hour    = (int)floor($sec / 3600);
        $minutes = (int)floor(($sec % 3600) / 60);
        $second  = (int)$sec - (($hour * 3600) + ($minutes * 60));

        if ($hour <= 9) {
            $hour = '0' . $hour;
        }
        if ($minutes <= 9) {
            $minutes = '0' . $minutes;
        }
        if ($second <= 9) {
            $second = '0' . $second;
        }

        return $hour . ':' . $minutes . ':' . $second; // return hh:mm:ss
    }

    /**
     * Time to Integer
     * @param  Time
     * @return Integer
     */
    public static function timeToInt($time)
    {
        $timeHour = substr($time, 0, 2);
        if (substr($time, 0, 1) == '0') {
            $timeHour = substr($time, 1, 1);
        }
        $timeMin     = substr($time, 3, 5);
        $timeHourMin = ((int)$timeHour * 60) + ((int)$timeMin / 1);
        $values      = $timeHourMin;

        return $values;
    }

    /**
     * Time To Text
     * @param  Time
     * @return String
     */
    public static function timeToText($time)
    {
        $timeHour   = ceil($time * 60);
        $modMinutes = $timeHour % 60;
        $hour       = floor($time);

        return $hour . ' h ' . $modMinutes . ' m';
    }

    /**
     * Random Color
     * @return Color
     */
    public static function randomColor()
    {
        mt_srand((double)microtime() * 1000000);
        $color = '';
        while (strlen($color) < 6) {
            $color .= sprintf("%02X", mt_rand(0, 255));
        }
        return '#' . $color;
    }

    /**
     * Image Decode
     * @param  String
     * @param  String
     * @return String
     */
    public static function imageDecode($string, $outputFile)
    {
        $fopen = fopen($outputFile, "wb");

        $data = explode(',', $string);

        fwrite($fopen, base64_decode($data[1]));
        fclose($fopen);

        return $outputFile;
    }

    /**
     * Image Encode
     * @param  String
     * @return String
     */
    public static function imageEncode($fileFullPath)
    {
        $type = pathinfo($fileFullPath, PATHINFO_EXTENSION);
        $data = file_get_contents($fileFullPath);
        return $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
    }

    /**
     * URL Slugs
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

      if (empty($text))
      {
        return 'n-a';
      }

      return $text;
    }

    /**
     * Redirect
     * @param  String
     * @param  integer
     * @return Header
     */
    public static function redirect($url, $statusCode = 303)
    {
        header('Location: ' . $url, true, $statusCode);
        die();
    }

    /**
     * Force Download
     * @param  String
     * @return Download
     */
    public static function forceDownload($file)
    {
        if (isset($file)&&(file_exists($file))) {
           header("Content-length: ".filesize($file));  
           header('Content-Type: application/octet-stream');  
           header('Content-Disposition: attachment; filename="' . $file . '"'); 
           readfile($file);
        } else {
            echo "No file selected";
        }
    }

    /**
     * Ordinal Number
     * @param  Integer
     * @return String
     */
    public static function ordinalNumber($number)
    {
        $ends = array('th','st','nd','rd','th','th','th','th','th','th');
        if ((($number % 100) >= 11) && (($number%100) <= 13)) {
            return $number. 'th';
        } else {
            return $number. $ends[$number % 10];
        }
    }

    /**
     * Validate Email
     * @param  String
     * @return boolean
     */
    public static function isValidEmail($email)
    {
        if(preg_match("~([a-zA-Z0-9!#$%&amp;'*+-/=?^_`{|}~])@([a-zA-Z0-9-]).([a-zA-Z0-9]{2,4})~",$email)) {
            echo 'This is a valid email.';
        } else{
            echo 'This is an invalid email.';
        }
    }

    /**
     * Count Month Between Date
     * @param  Date
     * @param  Date
     * @return Integer
     */
    public static function countMonthBetweenDate($startDate, $endDate)
    {
    	$d1 = strtotime($startDate);
		$d2 = strtotime($endDate);
		$min_date = min($d1, $d2);
		$max_date = max($d1, $d2);
		$i = 0;

		while (($min_date = strtotime("+1 MONTH", $min_date)) <= $max_date) {
		    $i++;
		}
		echo $i;
    }

    /**
     * Get Month List And Month Total
     * @param  Date
     * @param  Date
     * @return Array
     */
    public static function monthListBetweenDate($startDate, $endDate)
    {
		$output = [];
		$time   = strtotime($startDate);
		$last   = date('m-Y', strtotime($endDate));

		do {
		    $month = date('m-Y', $time);
		    $total = date('t', $time);

		    $output[] = [
		        'month' => $month,
		        'total' => $total,
		    ];

		    $time = strtotime('+1 month', $time);
		} while ($month != $last);
    }

    /**
     * Check String
     * @param  String
     * @return String
     */
    public function checkString($data)
    {
    	$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
    }

}
