<?php

namespace app\helper;

class Filter
{
    public function email($email)
    {
        if (!!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "valido";
        } else {
            echo "no";
        }
    }
}

?>