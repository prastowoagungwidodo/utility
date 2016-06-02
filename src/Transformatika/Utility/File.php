<?php

namespace Transformatika\Utility;

class File
{
    private static $instance;

    private $rootDir;

    public function __construct($rootDir)
    {
        $this->rootDir = $rootDir;
    }

    public static function init($rootDir = '')
    {
        if (is_null(self::$instance)) {
            self::$instance = new self($rootDir);
        }
        return self::$instance;
    }

    public function createDirectory($path, $includeFileName = false)
    {
        $dirpath = str_replace('/', DIRECTORY_SEPARATOR, rawurldecode($path));
        $dirpath = str_replace($this->rootDir, '', $dirpath);
        $dir = explode(DIRECTORY_SEPARATOR, $dirpath);
        // Array direktori
        $total = (int)count($dir);
        // Total array
        if ($includeFileName == true) {
            unset($dir[($total - 1)]);
            // Unset array terakhir (filename)
        }
        $currentDirectory = $this->rootDir;
        $error = 0;
        foreach ($dir as $key) {// Membuat direktori
            if ($key != '' && !is_dir($currentDirectory . $key)) {
                $oldumask = umask(0);
                $m = mkdir($currentDirectory . $key, 0777);
                if (!$m) {
                    $error++;
                }
                umask($oldumask);
            }
            $currentDirectory .= $key . DIRECTORY_SEPARATOR;
        }
        if ($error > 0) {
            return false;
        } else {
            return true;
        }
    }

    public function removeDirectory($dirPath)
    {
        $error = 0;
        if (is_dir($dirPath)) {
            $objects = scandir($dirPath);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dirPath . DIRECTORY_SEPARATOR . $object) == "dir") {
                        $del = self::removeDirectory($dirPath . DIRECTORY_SEPARATOR . $object);
                        if (!$del) {
                            $error++;
                        }
                    } else {
                        $del = unlink($dirPath . DIRECTORY_SEPARATOR . $object);
                        if (!$del) {
                            $error++;
                        }
                    }
                }
            }
            reset($objects);
            $del = rmdir($dirPath);
            if (!$del) {
                $error++;
            }
        } else {
            $del = unlink($dirPath);
            if (!$del) {
                $error++;
            }
        }
        if ($error > 0) {
            return false;
        } else {
            return true;
        }
    }

    public function imageDecode($imageString, $outputFile)
    {
        $this->createDirectory($outputFile, true);
        $splitImageString = explode('base64', $imageString);
        $sanitizeOutputFile = str_replace('/', DIRECTORY_SEPARATOR, $outputFile);
        $fullPathOutputFile = $this->rootDir . DIRECTORY_SEPARATOR . $sanitizeOutputFile;
        if (file_put_contents($fullPathOutputFile, base64_decode($splitImageString[1]))) {
            return true;
        } else {
            return false;
        }
    }

    public function imageEncode($inputFile = '', $filetype = 'jpeg')
    {
        if ($inputFile) {
            $inputFile = $this->rootDir . str_replace('/', DS, $inputFile);
            $imgbinary = fread(fopen($inputFile, "r"), filesize($inputFile));
            return 'data:image/' . $filetype . ';base64,' . base64_encode($imgbinary);
        }
    }

    public function readFile($filePath, $mimeContentType = '')
    {
        if (file_exists($filePath)) {
            if (empty($mimeContentType)) {
                header('Content-Type: ' . $this->mimeContentType($filePath));
            } else {
                header('Content-Type: ' . $mimeContentType);
            }

            header('Content-Length: ' . filesize($filePath));
            readfile($filePath);
        }
    }

    public function openFile($filePath, $mimeContentType = '')
    {
        $this->readFile($filePath, $mimeContentType);
    }

    /** Get Mime Content Type
    * Original source: http://stackoverflow.com/questions/1263957/why-is-mime-content-type-deprecated-in-php
    */
    public function mimeContentType($file)
    {
        $result = new \finfo();

        if (is_resource($result) === true) {
            return $result->file($filename, FILEINFO_MIME_TYPE);
        }

        return false;
    }

    public static function bufferFile($filePath)
    {
        if (is_file($filePath)) {
            header("Content-type: ".$this->mimeContentType($filePath));
            // change mimetype

            if (isset($_SERVER['HTTP_RANGE'])) {// do it for any device that supports byte-ranges not only iPhone
                $this->rangeDownload($filePath);
            } else {
                header("Content-length: " . filesize($filePath));
                readfile($filePath);
            }
        }
    }

    private function rangeDownload($file)
    {
        $fp = @fopen($file, 'rb');

        $size = filesize($file);
        // File size
        $length = $size;
        // Content length
        $start = 0;
        // Start byte
        $end = $size - 1;
        // End byte
        // Now that we've gotten so far without errors we send the accept range header
        /* At the moment we only support single ranges.
         * Multiple ranges requires some more work to ensure it works correctly
         * and comply with the spesifications: http://www.w3.org/Protocols/rfc2616/rfc2616-sec19.html#sec19.2
         *
         * Multirange support annouces itself with:
         * header('Accept-Ranges: bytes');
         *
         * Multirange content must be sent with multipart/byteranges mediatype,
         * (mediatype = mimetype)
         * as well as a boundry header to indicate the various chunks of data.
         */
        header("Accept-Ranges: 0-$length");
        // header('Accept-Ranges: bytes');
        // multipart/byteranges
        // http://www.w3.org/Protocols/rfc2616/rfc2616-sec19.html#sec19.2
        if (isset($_SERVER['HTTP_RANGE'])) {
            $c_start = $start;
            $c_end = $end;

            // Extract the range string
            list(, $range) = explode('=', $_SERVER['HTTP_RANGE'], 2);
            // Make sure the client hasn't sent us a multibyte range
            if (strpos($range, ',') !== false) {
                // (?) Shoud this be issued here, or should the first
                // range be used? Or should the header be ignored and
                // we output the whole content?
                header('HTTP/1.1 416 Requested Range Not Satisfiable');
                header("Content-Range: bytes $start-$end/$size");
                // (?) Echo some info to the client?
                exit ;
            }// fim do if
            // If the range starts with an '-' we start from the beginning
            // If not, we forward the file pointer
            // And make sure to get the end byte if spesified
            if ($range{0} == '-') {
                // The n-number of the last bytes is requested
                $c_start = $size - substr($range, 1);
            } else {
                $range = explode('-', $range);
                $c_start = $range[0];
                $c_end = (isset($range[1]) && is_numeric($range[1])) ? $range[1] : $size;
            }// fim do if
            /* Check the range and make sure it's treated according to the specs.
             * http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html
             */
            // End bytes can not be larger than $end.
            $c_end = ($c_end > $end) ? $end : $c_end;
            // Validate the requested range and return an error if it's not correct.
            if ($c_start > $c_end || $c_start > $size - 1 || $c_end >= $size) {
                header('HTTP/1.1 416 Requested Range Not Satisfiable');
                header("Content-Range: bytes $start-$end/$size");
                // (?) Echo some info to the client?
                exit ;
            }// fim do if

            $start = $c_start;
            $end = $c_end;
            $length = $end - $start + 1;
            // Calculate new content length
            fseek($fp, $start);
            header('HTTP/1.1 206 Partial Content');
        }// fim do if

        // Notify the client the byte range we'll be outputting
        header("Content-Range: bytes $start-$end/$size");
        header("Content-Length: $length");

        // Start buffered download
        $buffer = 1024 * 8;
        while (!feof($fp) && ($p = ftell($fp)) <= $end) {
            if ($p + $buffer > $end) {
                // In case we're only outputtin a chunk, make sure we don't
                // read past the length
                $buffer = $end - $p + 1;
            }// fim do if

            set_time_limit(0);
            // Reset time limit for big files
            echo fread($fp, $buffer);
            flush();
            // Free up memory. Otherwise large files will trigger PHP's memory limit.
        }// fim do while

        fclose($fp);
    }

    public function openFolder($subDirectory = '/')
    {
        if ($subDirectory == '/') {
            $dir = $this->rootDir . DIRECTORY_SEPARATOR;
        } else {
            $dir = $this->rootDir . DIRECTORY_SEPARATOR . $subDirectory;
        }

        $ls = scandir($dir);
        $resDir = array();
        $resFile = array();
        $res = array();
        $i = 0;
        $j = 0;
        foreach ($ls as $k => $v) {
            if ($v != '.' && $v != '..') {
                if (is_file($dir . DS . $v)) {
                    //$filetype = mime_content_type($dir.DS.$v);
                    //if($filetype == 'IMAGE/JPG' || $filetype == 'IMAGE/PNG' || $filetype == 'IMAGE/JPEG' || $filetype == 'IMAGE/GIF'){
                    $resFile[$i]['type'] = 'file';
                    if ($subDirectory == '/') {
                        $resFile[$i]['url'] = $v;
                    } else {
                        $resFile[$i]['url'] = substr($subDirectory . '/' . $v, 1);
                    }
                    $resFile[$i]['name'] = $v;
                    $i++;
                    //}
                } else {
                    $resDir[$j]['type'] = 'directory';
                    if ($subDirectory == '/') {
                        $resDir[$j]['url'] = $subDirectory . $v;
                    } else {
                        $resDir[$j]['url'] = $subDirectory . '/' . $v;
                    }

                    $resDir[$j]['name'] = $v;
                    $j++;
                }
            } else {
                if ($v == '.') {
                    $resDir[$j]['type'] = 'directory';
                    $resDir[$j]['url'] = '/';
                    $resDir[$j]['name'] = '.';
                } elseif ($v == '..') {
                    $resDir[$j]['type'] = 'directory';
                    $arr_dir = explode('/', $subDirectory);
                    array_pop($arr_dir);
                    $dir_url = implode('/', $arr_dir);
                    if (empty($dir_url)) {
                        $dir_url = '/';
                    }
                    $resDir[$j]['url'] = $dir_url;
                    $resDir[$j]['name'] = '..';
                }
                $j++;
            }
        }
        $k = 0;
        foreach ($resDir as $key => $val) {
            $res[$k] = $val;
            $k++;
        }
        foreach ($resFile as $key => $val) {
            $res[$k] = $val;
            $k++;
        }
        return $res;
    }

    public function getFolderSize($dir)
    {
        //$dir = rtrim(str_replace('\\', '/', $dir), '/');

        if (is_dir($dir) === true) {
            $totalSize = 0;
            $os = strtoupper(substr(PHP_OS, 0, 3));

            if ($os !== 'WIN') {
                $io = popen('/usr/bin/du -sb ' . $dir, 'r');
                if ($io !== false) {
                    $totalSize = intval(fgets($io, 80));
                    pclose($io);
                    return $totalSize;
                }
            }

            if ($os === 'WIN' && extension_loaded('com_dotnet')) {
                $obj = new \COM('scripting.filesystemobject');
                if (is_object($obj)) {
                    $ref = $obj -> getfolder($dir);
                    $totalSize = $ref -> size;
                    $obj = null;
                    return $totalSize;
                }
            }

            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir));
            foreach ($files as $file) {
                $totalSize += $file -> getSize();
            }
            return $totalSize;
        } elseif (is_file($dir) === true) {
            return filesize($dir);
        }
    }

    public function gravatar($email, $s = 64, $d = 'mm', $r = 'g', $img = false, $atts = array())
    {
        $url = 'http://www.gravatar.com/avatar/';
        $url .= md5(strtolower(trim($email)));
        $url .= "?s=$s&d=$d&r=$r";
        if ($img) {
            $url = '<img src="' . $url . '"';
            foreach ($atts as $key => $val) {
                $url .= ' ' . $key . '="' . $val . '"';
            }
            $url .= ' />';
        }
        return $url;
    }
}
