<?php

namespace app\modules\catalogo\controller;

use app\helper\session;
use app\modules\catalogo\model\articulosModel;
use app\modules\catalogo\view\catalogoView;

class ArticulosController{

    private $catalogoView;
    private $articulosModel;
    private $sistema;

    public function __construct(){
        $this->catalogoView = new CatalogoView();
        $this->articulosModel = new ArticulosModel();
        $this->sistema='fonda';
    }

    public function articulos(){

        $unidadMedidas = $this->articulosModel->obtieneUnidadMedidas();

        $presentaciones = $this->articulosModel->obtienePresentaciones();


        $this->catalogoView->articulos(array("unidadMedidas"=>$unidadMedidas, "presentaciones"=>$presentaciones));
    }

    public function listarArticulos(){
        echo json_encode($this->articulosModel->listarArticulos());
    }
    
    public function guardarArticulo(){
        $descripcion = $_POST['descripcion'];
        $preciocosto = $_POST['preciocosto'];
        $precioVenta = $_POST['precioVenta'];
        $existenciaMinima = $_POST['existenciaMinima'];
        $unidadMedida = $_POST['unidadMedida'];
        $presentacion = $_POST['presentacion'];
        $existencia = $_POST['existencia'];
        $idArticulo = $_POST['idArticulo'];
        $id_usuario = Session::get('id_usuario');
        $error = false;
        $mensaje = "Operacion realizada con exito.";
        $existe = $this->articulosModel->verificaArticulo($descripcion, $unidadMedida, $presentacion, $idArticulo);
        if ($existe){
            $error = true;
            $mensaje='Ya existe el articulo ingresado.';
        }
        else{
            if ($idArticulo){
                $resultado = $this->articulosModel->actualizarArticulo($descripcion, $unidadMedida, $presentacion, $preciocosto, $precioVenta,
                    $existencia, $existenciaMinima, $id_usuario, $idArticulo);
                if (!$resultado){
                    $error = true;
                    $mensaje = "Hubo un error al actualizar el articulo.";
                }
            }
            else{
                $resultado = $this->articulosModel->guardaArticulo($descripcion, $unidadMedida, $presentacion, $preciocosto, $precioVenta,
                    $existencia, $existenciaMinima, $id_usuario);
                if (!$resultado){
                    $mensaje = 'Hubo un error al insertar el articulo.';
                    $error = true;
                }
            }
        }
        echo json_encode(array('error'=>$error, 'mensaje'=>$mensaje));

    }

    public function eliminarArticulo(){
        $id_articulo = $_POST['id_articulo'];

        $id_usuario = Session::get('id_usuario');

        $res = $this->articulosModel->eliminaArticulo($id_articulo, $id_usuario);
        $error = false;
        $mensaje = "Articulo eliminado correctamente.";
        if (!$res){
            $error = true;
            $mensaje = 'Hubo un error al eliminar el registro';
        }
        echo json_encode(array("mensaje"=>$mensaje,"error"=>$error));
    }
}

?>