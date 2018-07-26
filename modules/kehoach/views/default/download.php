<?php
/**
 * Created by PhpStorm.
 * User: HT Dev
 * Date: 31/08/2017
 * Time: 16:51:27
 */

    $filename = '../bao_cao_san_xuat.xls';


header('Pragma: public');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Cache-Control: private', false); // required for certain browsers
header('Content-Type: application/pdf');

header('Content-Disposition: attachment; filename="' . basename($filename) . '";');
header('Content-Transfer-Encoding: binary');
header('Content-Length: ' . filesize($filename));

readfile($filename);
unlink($filename);
exit;