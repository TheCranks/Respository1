<?php

namespace app\modules\catalogo\controller;

use app\helper\session;
use app\modules\catalogo\model\personaModel;
use app\modules\catalogo\view\catalogoView;

class CatalogoPersonalController{

    private $catalogoView;
    private $personalModel;
    public function __construct(){
        $this->catalogoView = new CatalogoView();
        $this->personalModel = new PersonaModel();
    }

    public function personal(){
        $this->catalogoView->personal();
    }

    public function guardaPersonal(){
        $nombre = $_POST['nombrePersonal'];
        $apellidos = $_POST['apellidoPersonal'];
        $direccion = $_POST['direccionPersonal'];
        $celular = $_POST['celularPersonal'];
        $cedula = $_POST['cedulaPersonal'];
        $sexo = $_POST['sexoPersonal'];
        $correo = $_POST['correoPersonal'];
        $idPersonal = $_POST['idPersonal'];

        $id_usuario = Session::get('id_usuario');
        $this->personalModel->beginTransaction();
        if ($idPersonal){
            $result = $this->personalModel->editarPersonal($nombre, $apellidos, $direccion, $celular, $cedula, $sexo, $correo,$id_usuario, $idPersonal);
        }
        else{
            $result = $this->personalModel->grabaPersonal($nombre, $apellidos, $direccion, $celular, $cedula, $sexo, $correo,$id_usuario);
        }

        if ($result==1){
            $this->personalModel->commit();
            $error = false;
            $mensaje = 'Registro ingresado correctamente.';
        }
        else{
            $this->personalModel->rollback();
            $error = true;
            $mensaje = "Hubo un error al ingresar el personal";
        }
        echo json_encode(array('error'=>$error, 'mensaje'=>$mensaje));
    }

    public function listarPersonal(){
        echo json_encode($this->personalModel->listarPersonal());
    }

}

?>