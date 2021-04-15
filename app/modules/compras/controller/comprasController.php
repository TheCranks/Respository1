<?php

namespace app\modules\compras\controller;

use app\helper\session;
use app\modules\compras\view\comprasView;
use app\modules\compras\model\comprasModel;
use app\libs\Imprimir;


class ComprasController{

    private $comprasView;
    private $comprasModel;
    private $imprimir;
    private $sistema;

    public function __construct(){
        $this->comprasView = new ComprasView();
        $this->comprasModel = new  ComprasModel();
        $this->imprimir = new Imprimir;
        $this->sistema = 'fonda';
    }

    public function nueva(){
        $fecha = date('d/m/Y');
        $this->comprasView->nueva(array("fecha"=>$fecha,'sistema'=>$this->sistema));
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