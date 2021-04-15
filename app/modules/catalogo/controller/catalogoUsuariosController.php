<?php

namespace app\modules\catalogo\controller;

use app\helper\session;
use app\modules\catalogo\model\usuariosModel;
use app\modules\catalogo\view\catalogoView;

class CatalogoUsuariosController{

    private $catalogoView;
    private $usuarioModel;

    public function __construct(){
        $this->catalogoView = new CatalogoView();
        $this->usuarioModel = new UsuariosModel();
    }

    public function usuarios(){
        $this->catalogoView->usuarios();
    }

    private function init($user){
        $datosUsuario = $this->usuarioModel->obtieneUsuario($user);
        $_SESSION['id_usuario'] = $datosUsuario['id_usuario'];
        $_SESSION['nombres'] = $datosUsuario['nombres'];
        $_SESSION['apellidos'] = $datosUsuario['apellidos'];
        $_SESSION['correo'] = $datosUsuario['correo'];
        $_SESSION['id_rol'] = $datosUsuario['id_rol'];
        $_SESSION['nombre_rol'] = $datosUsuario['nombre_rol'];
    }

    public function iniciaSession(){
        $user = $_POST['usuario'];
        $password= $_POST['pass'];
        $resultado = $this->usuarioModel->iniciaSession($user, $password);
        if ($resultado==1){
            //Inicia variables de sesion
            $this->init($user);
        }
        echo json_encode($resultado);
    }

    private function destructSession(){
        unset($_SESSION['id_usuario']);
        unset($_SESSION['nombres']);
        unset($_SESSION['apellidos']);
        unset($_SESSION['correo']);
        unset($_SESSION['id_rol']);
        unset($_SESSION['nombre_rol']);
    }

    public function cerrarSession(){
        session_unset();
        $this->destructSession();

        Header('Location: /jusmar/');
    }

    public function guardaUsuario(){
        $id_usuario = $_POST['id_usuario'];
        $usuario= $_POST['usuario'];
        $pass= $_POST['pass'];
        $id_personal= $_POST['id_personal'];
        $activo= $_POST['activo'];
        $rol= $_POST['id_rol'];
        $session_usuario = Session::get('id_usuario');
        $error = false;
        $mensaje = 'Registro guardado correctamente.';
        $existeUsuario = $this->usuarioModel->verificaUsuario($usuario,$id_usuario);
        if ($existeUsuario){
            $error = true;
            $mensaje = 'Este nombre de usuario ya esta en uso';
        }
        else{
            if ($id_usuario){
                $hashPass = password_hash($pass, PASSWORD_BCRYPT);

                $resultado = $this->usuarioModel->actualizarUsuario($usuario, $hashPass,
                    $rol,$session_usuario, $id_usuario, $activo);
                if ($resultado==0){
                    $error = true;
                    $mensaje = 'Hubo un error al actualizar al usuario.';
                }

            }
            else{
                $hashPass = password_hash($pass, PASSWORD_BCRYPT);
                $resultado = $this->usuarioModel->guardarUsuario($usuario, $hashPass, $id_personal, $rol,$session_usuario);
                if ($resultado==0){
                    $error = true;
                    $mensaje = 'Hubo un error al insertar el registro de usuario';
                }
            }
        }
        echo json_encode(array('error'=>$error, 'mensaje'=>$mensaje));
    }

    public function listarUsuarios(){
        echo json_encode($this->usuarioModel->listarUsuarios());
    }

}

?>