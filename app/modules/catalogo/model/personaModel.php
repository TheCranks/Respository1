<?php

namespace app\modules\catalogo\model;

use app\config;
use app\core\model\Model;

class PersonaModel extends Model
{

    public function __construct()
    {
        parent::__construct(Config::$mysql);
    }

    public function grabaPersonal($nombre, $apellidos, $direccion, $celular, $cedula, $sexo, $correo, $id_usuario)
    {
        $query = "insert into tb_personas(nombres, apellidos, correo, telefono, direccion, cedula, sexo,
 fecha_grabacion, usuario_grabacion) values(upper(?), upper(?), ?, ?, upper(?), ?, ?, NOW(),?)";
        $this->prepare($query);
        $this->bind([1 => $nombre, $apellidos, $correo, $celular, $direccion, $cedula, $sexo, $id_usuario]);
        $this->execute();
        return $this->numRow();
    }

    public function editarPersonal($nombre, $apellidos, $direccion, $celular, $cedula, $sexo, $correo, $id_usuario, $id_personal)
    {
        $query = "update tb_personas set nombres = upper(?), apellidos = upper(?), correo = ?, telefono =?, 
direccion = upper(?), cedula = ?, sexo=?, fecha_modificacion = now(), usuario_modificacion = ? where id_persona = ?";
        $this->prepare($query);
        $this->bind([1 => $nombre, $apellidos, $correo, $celular, $direccion, $cedula, $sexo, $id_usuario, $id_personal]);
        $this->execute();
        return $this->numRow();

    }

    public function listarPersonal()
    {
        $query = "select id_persona, nombres, apellidos, cedula, direccion, telefono, correo, sexo from tb_personas 
where estado = 1";
        $this->prepare($query);
        $this->execute();
        return $this->fetchAll();
    }



}
