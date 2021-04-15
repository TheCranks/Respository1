<?php

namespace app\helper;

class Hash
{
    public static function getHash($password)
    {
        $hash = password_hash($password, PASSWORD_BCRYPT); 
        return $hash;
    }

    public static function verifyHash($value, $hash)
    {
        if (password_verify($value, $hash)) {
            return 1;
        } else {
            return 0;
        }
    }

    public static function random(){
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $code = substr(str_shuffle( $chars ), 0, 10);
        return $code;
    }
}