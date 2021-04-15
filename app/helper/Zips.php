<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 13/6/2017
 * Time: 4:36 PM
 */

namespace app\helper;


use Exception;
use ZipArchive;

class Zips
{
    public static function createZip($files,$file,$path,$send){
        try {

            unlink($path.$file);
            $zip = new ZipArchive();
            $zip->open($path.$file, ZipArchive::CREATE);
            for($i=0;$i<sizeof($files);$i++){
                $zip->addFile($path.$files[$i],$files[$i]);
            }

            $zip->close();

            if($send) {
                if($send) {

                    header('Content-Description: File Transfer');
                    header('Content-Type: application/zip');
                    header('Content-Disposition: attachment; filename=' . urlencode(basename($path . $file)));
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                    header('Pragma: public');

                    $response = readfile($path . $file);

                    /*header('Content-Length:' . strlen($response));
                    ob_clean();
                    flush();
                    print $response;*/
                    //print $response;
                    exit;
                }else{
                    return $response = readfile($path . $file);
                }
            }
        } catch (Exception $ex) {
            return false;
        }

    }

    private static function prepareForDownload($data, $format)
    {
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Description: File Transfer');
        //header('Content-Disposition: attachment; filename=report.' . $format);
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . strlen($data));
        /*if (isset($this->mime_types[$format])) {
            header('Content-Type: application/zip');
        } else {
            header('Content-Type: application/octet-stream');
        }*/
        header('Content-Type: application/zip');
        readfile($data);
    }
}