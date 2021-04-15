<?php

namespace app\modules\facturacion\controller;

use app\helper\session;
use app\modules\facturacion\view\FacturacionView;
use app\modules\facturacion\model\FacturacionModel;
use app\libs\Imprimir;


class FacturacionController{

    private $facturacionView;
    private $facturacionModel;
    private $imprimir;
    private $sistema;

    public function __construct(){
        $this->facturacionView = new FacturacionView();
        $this->facturacionModel = new  FacturacionModel();
        $this->imprimir = new Imprimir;
        $this->sistema='fonda';
    }

    public function nueva(){
        $fecha = date('d/m/Y');
        $this->facturacionView->nueva(array("fecha"=>$fecha));
    }

    public function agregaDetalle(){
        $id_factura = $_POST['id_factura'];
        $id_cliente= $_POST['id_cliente'];
        $iva= $_POST['iva'];
        $pagaCon= $_POST['pagaCon'];
        $id_usuario = $_SESSION['id_usuario'];

        $error = false;
        $mensaje = "Operación Realizada con éxito";


        $detalleFactura = $_POST['detalleFactura'];
        if ($id_factura){
            //guardar detalle nada mas.
        }
        else{
            $result = $this->facturacionModel->guardaDetalleFactura($id_usuario,$iva,$id_cliente, $detalleFactura, $pagaCon);
            if($result['error']){
                $error = true;
                $mensaje = $result['mensaje'];
            }
            else{
                $id_factura = $result['id_factura'];

            }
        }

        echo json_encode(array('mensaje'=>$mensaje, 'error'=>$error, "id_factura"=>$id_factura));

    }



    public function listarFacturas(){
        $fecha = date('d/m/Y');
        $this->facturacionView->listar(array("fecha"=>$fecha));
    }

    public function obtenerFacturas(){
        echo json_encode($this->facturacionModel->obtenerFacturas());
    }

    public function imprimir(){
        $id_factura = $_POST['factura'];
        $this->imprimir->exec('/reports/rpt_factura', [
            "id_factura"=>$id_factura]);
    }
}

?>