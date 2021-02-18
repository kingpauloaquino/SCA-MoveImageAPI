<?php

class MoveImage {

    public static $originDir = "D:/ImageOrigin/";
    public static $destinationDir = "D:/ImageNewDIR/";

    public static $successData = array();
    public static $errorData = array();

    public function getFullPath(string $yardUid, string $boxUid, bool $isOrigin)
    {
        $yardDIR = $yardUid . "-" . $boxUid;
        if ($isOrigin) {
            return $this::$originDir . "/" . $yardDIR;
        }
        return $this::$originDir . "/" . $yardDIR;
    }

    public function setFullPath(string $filename, bool $isOrigin) {
        if($isOrigin) {
            return $this::$originDir . "/" . $filename;
        }
        return $this::$originDir . "/" . $filename;
    }

    public function get_images($directory)
    {
        $scanned_directory = array_diff(scandir($directory), array('..', '.'));
        return $scanned_directory;
    }

    public function created_folder($path)
    {
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
    }

    public function check_dir(string $dir)
    {
       if(file_exists($dir)) {
           return true;
       }
       return false;
    }

    public function recurse_copy($src, $dst)
    {
        if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

        $dir = opendir($src);

        @mkdir($dst, 0777);

        while (false !== ($file = readdir($dir))) {
            if ($file != '.' && $file != '..') {
                if (is_dir($src . DS . $file)) {
                    recurse_copy($src . DS . $file, $dst . DS . $file);
                } else {
                    copy($src . DS . $file, $dst . DS . $file);
                }
            }
        }

        closedir($dir);
    }
}

if(!IsSet($_GET["yard_from"])) {
    echo json_encode(["status" => 404.1, "message" => "No Yard From UID"]);
    exit();
}
if (!isset($_GET["yard_to"])) {
    echo json_encode(["status" => 404.2, "message" => "No Yard To UID"]);
    exit();
}
if (!isset($_GET["box_from"])) {
    echo json_encode(["status" => 404.3, "message" => "No Box From UID"]);
    exit();
}
if (!isset($_GET["box_to"])) {
    echo json_encode(["status" => 404.4, "message" => "No Box To UID"]);
    exit();
}

$yard_from = $_GET["yard_from"];
$yard_to = $_GET["yard_to"];

$box_from = $_GET["box_from"];
$box_to = $_GET["box_to"];

$image = new MoveImage();
$originFullPath = $image->getFullPath($yard_from, $box_from, true);
$destinationFullPath = $image->getFullPath($yard_to, $box_to, false);

$res = $image->check_dir($originFullPath);
if(!$res) {
    echo json_encode(["status" => 404.5, "message" => "[Origin] - Yard ID and Box ID did not found."]);
    exit();
}

// var_dump($originFullPath);
// var_dump($destinationFullPath);
$image->recurse_copy($originFullPath, $destinationFullPath);
echo json_encode(["status" => 200, "message" => "All images should be copied, and you should see them now."]);