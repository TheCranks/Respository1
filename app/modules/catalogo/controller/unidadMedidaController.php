<?php

namespace app\modules\catalogo\controller;

use app\helper\session;
use app\modules\catalogo\model\unidadMedidaModel;
use app\modules\catalogo\view\catalogoView;

class UnidadMedidaController{

    private $umModel;
    private $catalogoView;
    public function __construct()
    {
        $this->umModel = new  UnidadMedidaModel();
        $this->catalogoView = new CatalogoView();

    }

    public  function unidadMedida(){
        $this->catalogoView->unidadMedida();
    }

    public function guardaUnidadMedida(){
        $id_um = $_POST['id_um'];
        $descripcion= $_POST['descripcion'];
        $abreviatura= $_POST['abreviatura'];
        $error = false;
        $mensaje = 'Operacion realizada correctamente.';
        $id_usuario = Session::get('id_usuario');
        $existe = $this->umModel->compruebaUM($descripcion, $id_um);
        if ($existe){
            $error = true;
            $mensaje = "Ya existe una unidad de medida con esta descripcion";
        }
        else{
            if ($id_um){
                $resultado = $this->umModel->editaUM($descripcion,$id_um, $id_usuario,$abreviatura);
                if (!$resultado){
                    $error = true;
                    $mensaje = "Hubo un error al actualizar el registro";
                }
            }
            else{
                $resultado = $this->umModel->guardaUM($descripcion, $id_usuario, $abreviatura);
                if (!$resultado){
                    $error = true;
                    $mensaje = "Hubo un error al grabar el registro";
                }
            }

        }

        echo json_encode(array("error"=>$error, "mensaje"=>$mensaje));
    }

    public function listarUnidadesMedidas(){
        echo json_encode($this->umModel->listarUnidadMedida());
    }




}
