<?php

namespace app\modules\admin\model;

use app\config;
use app\core\model\Model;

class AdminModel extends Model
{

    public function __construct()
    {
        parent::__construct(Config::$mysql);
    }

    public function obtieneUsuarios($user, $password){
        $query = "select * from tb_usuarios where usuario = ? and pass = ?";
        $this->prepare($query);
        $this->bind([1=>$user, 2=>$password]);
        $this->execute();
        return $this->fetchAll();
    }

}
