<?php

namespace app\modules\catalogo\model;

use app\config;
use app\core\model\Model;

class UsuariosModel extends Model
{

    public function __construct(){
        parent::__construct(Config::$mysql);
    }


    public function verificaUsuario($usuario, $id_usuario = '')
    {
        $param = "";
        if ($id_usuario!=""){
            $param = " and id_usuario <> $id_usuario";
        }
        $query = "select * from main_db.tb_usuarios where usuario = ? $param";
        $this->prepare($query);
        $this->bind([1 => $usuario]);
        $this->execute();
        return $this->fetch();
    }

    public function iniciaSession($user, $password){
        $query = "select usuario, pass from tb_usuarios where usuario = ? and estado = 1 limit 1";
        $this->prepare($query);
        $this->bind([1=>$user]);
        $this->execute();
        $datoBD = $this->fetch();
        if ($datoBD){
            $pass_bd = $datoBD['pass'];
            return password_verify ( $password , $pass_bd);
        }
        else{
            return -1;
        }
    }

    public function obtieneUsuario($usuario){
        $query = "SELECT usuario.id_usuario, usuario.usuario, rol as id_rol, 
                    case rol when 1 then 'Administrador' else 'Usuario' end as nombre_rol,
                     nombres, apellidos, correo FROM tb_usuarios usuario 
                  inner join tb_personas personas on personas.id_persona = usuario.id_persona 
                  where usuario.usuario = ?";
        $this->prepare($query);
        $this->bind([1=>$usuario]);
        $this->execute();
        return $this->fetch();
    }


    public function guardarUsuario($usuario, $hashPass, $id_personal, $rol, $session_usuario)
    {
        $query = 'insert into tb_usuarios (usuario, pass, id_persona, rol, fecha_grabacion, usuario_grabacion)
values (?, ?, ?, ?, now(), ?)';
        $this->prepare($query);
        $this->bind([1 => $usuario, $hashPass, $id_personal, $rol, $session_usuario]);
        $this->execute();
        return $this->numRow();
    }

    public function listarUsuarios(){
        $query = "SELECT
	usuarios.id_usuario,
	concat(personal.nombres ,personal.apellidos) as nombres,
	usuarios.usuario,
	usuarios.pass,
	usuarios.rol ,
	personal.id_persona,
	usuarios.estado
FROM
	tb_usuarios usuarios
	INNER JOIN tb_personas personal ON personal.id_persona = usuarios.id_persona";
        $this->prepare($query);
        $this->execute();
        return $this->fetchAll();
    }

    public function actualizarUsuario($usuario, $hashPass,$rol,$session_usuario, $id_usuario, $activo){
        $query = "update tb_usuarios set usuario = ?, pass = ?, rol = ?, fecha_modificacion = now(), 
                  usuario_modificacion= ?, estado = ? where id_usuario = ?";
        $this->prepare($query);
        $this->bind([1=>$usuario, $hashPass, $rol, $session_usuario, $activo, $id_usuario]);
        $this->execute();
        return $this->numRow();

    }


}
