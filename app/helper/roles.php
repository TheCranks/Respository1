<?php

namespace app\helper;


class Roles
{
    protected static $roles = array();

    public static function set()
    {
        if (Session::get('roles')){
            self::$roles = Session::get('roles');
        }
    }

    public static function get($modulo, $rol)
    {
        self::set();
        foreach (self::$roles as $key => $item) {
            if ($key == $modulo) {
                foreach (self::$roles[$key] as $i => $items) {
                    if (is_array($items)) {
                        if (in_array($rol, $items)){
                            return true;
                        }
                    } else {
                        if ($items[$i] == $rol) {
                            return true;
                        }
                    }
                }
            }
        }
        return false;
    }
}
