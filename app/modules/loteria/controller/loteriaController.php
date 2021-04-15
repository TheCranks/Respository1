<?php

namespace app\modules\loteria\controller;

use app\helper\session;
use app\modules\loteria\view\loteriaView;
use app\modules\loteria\model\loteriaModel;
use app\libs\Imprimir;


class LoteriaController{

    private $vwloteria;
    private $loteriaModel;
    private $imprimir;

    public function __construct(){
        $this->vwloteria = new LoteriaView();
        $this->loteriaModel = new  LoteriaModel();
        $this->imprimir = new Imprimir;
    }

    public function vwVenta(){
        $this->vwloteria->index();
    }

    public function agregaDetalle(){
        $id_proveedor= $_POST['id_proveedor'];
        $id_usuario = $_SESSION['id_usuario'];

        $error = false;
        $mensaje = "Operación Realizada con éxito";


        $detalleFactura = $_POST['detalleFactura'];

        $result = $this->comprasModel->guardaDetalleFactura($id_usuario,$id_proveedor, $detalleFactura);
        if($result['error']){
            $error = true;
            $mensaje = $result['mensaje'];
        }

        echo json_encode(array('mensaje'=>$mensaje, 'error'=>$error));

    }



    public function listarFacturas(){
        $fecha = date('d/m/Y');
        $this->comprasView->listar(array("fecha"=>$fecha));
    }

    public function obtenerCompras(){
        echo json_encode($this->comprasModel->obtenerCompras());
    }

    public function imprimir(){
        $id_factura = $_POST['factura'];
        $this->imprimir->exec('/reports/rpt_factura', [
            "id_factura"=>$id_factura]);
    }
}

?>