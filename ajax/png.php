<?php

if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) 
    || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest')
) {
    exit;
}
error_reporting(E_ALL);
ini_set('display_errors', 1);
require dirname(dirname(__FILE__)).'/config.php';

$imgdata = filter_input(INPUT_POST, 'imgdata', FILTER_SANITIZE_SPECIAL_CHARS);
$filename = filter_input(INPUT_POST, 'filename', FILTER_SANITIZE_SPECIAL_CHARS);

if ($imgdata && $filename) {
    $maindir = $_CONFIG['qrcodes_dir'].'/';
    $savedir = '../'.$maindir;
    $basename = basename($filename, '.png');

    if (file_exists($savedir.$basename.'.svg')) {
        $basename = basename($filename, '.svg');

        if (!file_exists($savedir.$filename)) {
            if (!file_exists($imgdata)) {
                $content = file_get_contents($imgdata);
            }
            if (!$content) {
                exit('error');
            }
            file_put_contents($savedir.$filename, $content);
        }
        echo $maindir.$filename;
        exit;
    }
    exit('error');
}
exit('error');
