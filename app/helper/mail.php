<?php
/*
 * Archivo          : mail.php
 * Nombre lógico    : Mail
 * Producto         : core
 * Fecha de creación: 2016_05_10
 * Autor(es)        : Maurel Reyes
 * **************************************************************************
 * Propósito : Encargado de poder a disposición de los modulos del sistemas los
 * metodos necesarios para el envio de correo electronico
 * **************************************************************************
 * CONTROL DE CAMBIOS
 * Fecha       No. Tarea (HD)       Autor(es)            Descripción
 */

namespace app\helper;

use app\core\view\View;
use app\libs\phpmailer;


class Mail
{
    public static function sendEmail($texto, $to, $asunt, $file = null, $fileName = null)
    {
        $obj_mail = new phpmailer();
        $obj_mail->Mailer = "smtp";
        $obj_mail->Host = "mail.inatec.edu.ni";

        $obj_mail->From = "noreplay@inatec.edu.ni";
        $obj_mail->FromName = "INATEC";
        //$obj_mail->Timeout = 30;

        $obj_mail->AddAddress($to);
        $obj_mail->Subject = $asunt;
        $obj_mail->Body = $texto;
        $obj_mail->IsHTML(true);
        if ($fileName != null) {
            $obj_mail->AddStringAttachment($file, $fileName);
            //$obj_mail->AddAttachment($file);
        }
        $rtn_mail = $obj_mail->Send();

        if ($rtn_mail) {//if(mail($to, $asunt, $texto, $cabeceras)){
            return true;
        } else
            return false;
    }

    public static function renderEmail($value, $params = array(), $to, $asunt, $file = null, $fileName = null)
    {
        $view = new View();
        $view->cargador(MSG);
        $view->crearEntorno();
        $html = $view->render($value . '.twig', $params);
        self::sendEmail(utf8_decode($html), $to, $asunt, $file, $fileName);
    }
}

?>