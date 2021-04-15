<?php
namespace app\libs;

class LoginLDAP{
    private $ldap_serv = 'ldap://172.20.11.2';
    private $ldap_port = "389";
    private $ldap_conex = null;
    
    public function __construct()
    {
    }
    
    public function login($user, $pass){
        $ldap_conext = connect();
    }
    
    private function connect(){
        $connect = ldap_connect($ldap_serv, $ldap_port);
        ldap_set_option($lc, LDAP_OPT_REFERRALS, 0);
        ldap_set_option($lc, LDAP_OPT_PROTOCOL_VERSION, 3);
        $ldapbind = ldap_bind($lc,$user,$pass);
        if ($ldapbind == false) {
            return false;
        }else{
            return $connect;
        }
    }
    
}
/*
$user = "mreyese@inatec.edu.ni";
$pass="Inicial1";
//in our system, we already use this account for LDAP authentication on the server above
$ldap_serv = 'ldap://172.20.11.2';
$ldap_port = '389';
$lc = ldap_connect($ldap_serv, $ldap_port);
$base_dn = "DC=inatec,DC=edu,DC=ni";
$ldap_dn = "CN=Users,DC=ad,DC=domain";
$person = "";
$filter="(|(sn=$person*)(givenname=$person*))";
$justthese = array("ou", "sn", "givenname", "mail");

ldap_set_option($lc, LDAP_OPT_REFERRALS, 0);
ldap_set_option($lc, LDAP_OPT_PROTOCOL_VERSION, 3);
$ldapbind = ldap_bind($lc,$user,$pass);
if ($ldapbind == false) {
    echo "Usuario incorrecto";
}else{
    $search = ldap_search($lc,$base_dn, $filter, $justthese);
    $entries = ldap_get_entries($lc, $search);
    exit(var_dump($entries));
}
?>*/