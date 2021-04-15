<?php

namespace app\libs;

use app\libs\WPReport;

/**
 * Description of imprimir <br />
 * Nombre lógico    : xxxxxxxxxxxxxxxxxxxx <br />
 * Fecha de creación: yyyy_mm_dd <br />
 * CONTROL DE CAMBIOS<br />
 * Fecha       No. Tarea (HD) Autor(es)            Descripción<br />
 * yyyy_mm_dd  9999999999     xxxxxxxxxxxxxxxxxxxx xxxxxxxxxxxxxxxxxxxx
 * <br />
 *
 * @package    (nombre del paquete)
 * @author     Gunter Torres <gtorres@inatec.edu.ni><email>
 * @copyright  (año) (nombre)
 * @license    (link a licencia)
 * @link       (enlace)
 * @version    numero
 */
class Imprimir extends WPReport
{

    public function __construct()
    {
        set_time_limit(0);
        parent::__construct();
    }

    public function exec($ruta,  $param = [], $format = 'pdf', $responder=true)
    {
        $this->client->setRequestTimeout(1000);
        try {

            if (empty($param)) {
                $reporte = $this->client->reportService()->runReport($ruta);
            } else {
                $reporte = $this->client->reportService()->runReport($ruta, $format, null, null, $param);
            }
        } catch (RESTRequestException $e) {
            
            echo 'RESTRequestException:';
            echo 'Exception message:   ', $e->getMessage(), "\n";
            echo 'Set parameters:      ', $e->parameters, "\n";
            echo 'Expected status code:', $e->expectedStatusCodes, "\n";
            echo 'Error code:          ', $e->errorCode, "\n";
        }


        if($responder){
            if ($format !== 'html') {
                echo $this->prepareForDownload($reporte, $format);
            } else {
                echo $reporte;
            }
            exit();
        }else{
            return $reporte;
        }
    }
}
