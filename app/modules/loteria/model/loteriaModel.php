<?php

namespace app\modules\loteria\model;

use app\Config;
use app\core\model\Model;

class LoteriaModel extends Model
{

    public function __construct()
    {
        parent::__construct(Config::$mysql);
    }


}
