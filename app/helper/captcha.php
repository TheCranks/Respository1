<?php

namespace app\helper;

class Captcha
{

    public static function getCaptcha()
    {

        $captcha = @imagecreatefrompng(HELPER . "background.png");

        if ($captcha) {

            $fuente_tamano = 80;
            $fuente = HELPER . 'font.ttf';
            $caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

            // El catpcha creado
            $texto = "";

            for ($i = 0; $i < 5; $i++) {
                $texto .= $caracteres[rand(0, strlen($caracteres) - 1)];
            }

            $_SESSION["captcha"] = $texto;

            $black = imagecolorallocatealpha($captcha, 0, 0, 0, 60);
            $line = imagecolorallocate($captcha, 233, 239, 239);

            imageline($captcha, 0, 0, 39, 29, $line);
            imageline($captcha, 40, 0, 64, 29, $line);

            $alto = 180;
            $x = $fuente_tamano;
            $y = ($alto / 2) + 25; $font ="Calibri";
            imagettftext($captcha, $fuente_tamano, 0, $x, $y, $black, $fuente, $texto);

            //header('Content-Disposition: Attachment;filename=captcha.png');
            return Captcha::gdImgToHTML($captcha, "png");
            //imagedestroy($captcha);
        } else {
            echo "Error Captcha";
        }
    }

    public static function validateCaptcha($texto)
    {
        if ($_SESSION["captcha"] == $texto)
            return true;
        else
            return false;
    }

    private static function gdImgToHTML($gdImg, $format = 'jpg')
    {

        // Validate Format
        if (in_array($format, array('jpg', 'jpeg', 'png', 'gif'))) {

            ob_start();

            if ($format == 'jpg' || $format == 'jpeg') {

                imagejpeg($gdImg);

            } elseif ($format == 'png') {

                imagepng($gdImg);

            } elseif ($format == 'gif') {

                imagegif($gdImg);
            }

            $data = ob_get_contents();
            ob_end_clean();

            // Check for gd errors / buffer errors
            if (!empty($data)) {

                $data = base64_encode($data);

                // Check for base64 errors
                if ($data !== false) {

                    // Success
                    return "data:image/png;base64," . $data;
                }
            }
        }

        // Failure
        return '';
    }
}

