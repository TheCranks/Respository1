<?php
/*
 * Archivo          : Model.php
 * Nombre lógico    : Model
 * Producto         : core
 * Fecha de creación: 2016_05_10
 * Autor(es)        : Gunter Torrez
 * **************************************************************************
 * Propósito : Encargado de poder a disposición de los modulos del sistemas los
 * metodos necesarios para ejecutar consultas en Postgresql
 * **************************************************************************
 * CONTROL DE CAMBIOS
 * Fecha       No. Tarea (HD)       Autor(es)            Descripción
 */

namespace app\core\model;

//use app\libs\PHPMailer;
use mysqli;
use PDO;
use PDOException;
use app\core\libs\SqlFormatter\SqlFormatter;

/**
 * @todo Agregar mas documentacion acerca de la clase
 */
class Model
{
    private $conexion = null;
    private $prepare = null;
    private $transaction = 0;
    public $error = '';
    public $query_exec = "";

    /**
     * Crea la conexion con la base de datos
     * @param $server Ip del servidor
     * @param $db Base de datos
     * @param $user Usuario con permisos de conexion
     * @param $pass Clave del usuario
     */
    public function __construct($db = array())
    {

        //$server = $db['host'];
        //$base = $db['db'];
        /*$user = "root";
        $pass = "";*/
        $user = $db['user'];
        $pass = $db['pass'];
        $config = $db['config'];
        $dsn = $this->makeDSN($config);
        $this->conectar($dsn, $user, $pass);


    }


    public function makeDSN($config = null)
    {
        $dsn = $config['driver'] . ':';
        unset($config['driver']);
        foreach ($config as $key => $value)
        {
            $dsn .= $key . '=' . $value . ';';
        }
        return $dsn;
    }



    /**
     * Destruye la conexion con la base de datos
     */
    public function __destruct()
    {
        $this->conexion = null;
        $this->prepare = null;
        $this->transaction = 0;
        $this->error = '';
    }

    /**
     * Inicia una transaccion con la base de datos
     * @link http://php.net/manual/es/pdo.begintransaction.php Se basa en el ejemplo
     * de transacciones que comento  "steve at fancyguy dot com"
     * @return boolean Verdadero si es correcto, <br />Falso en caso de error
     */
    public function beginTransaction()
    {
        if (!$this->transaction++) {
            return $this->conexion->beginTransaction();
        }

        $this->conexion->exec('SAVEPOINT trans'.$this->transaction);
        return $this->transaction >= 0;
    }

    /**
     * Vincula los valores a una consulta preparada<br />
     * En caso de que la consulta no requiera parametros ejecutar "execute"
     * directamente
     * @param array $values  Valores a reemplazar en la consulta preparada <br/>
     * En los arreglos el orden de los indice es el que define el orden en el
     * reemplazo
     * @param int $tipo 1: bindValue <br />
     * 2: bindParam
     * @return boolean
     */
    public function bind($values = array(), $tipo = 1)
    {
        //$this->escapa($values);
        $sql = str_split($this->prepare->queryString);
        $contParam = 1;
        for($i = 0; $i < sizeof($sql);$i++){
            if($sql[$i] == '?'){
                if(sizeof($values)>=$contParam){
                    $this->query_exec .= "'".$values[$contParam]."'";
                    $contParam++;
                }else{
                    $this->query_exec .= $sql[$i];
                }

            }else{
                $this->query_exec .= $sql[$i];
            }

        }
        //exit(var_dump('?',$this->prepare->queryString, $values));
        if (!empty($this->prepare)) {
            if (1 == $tipo) {
                foreach ($values as $key => $param) {
                    $this->prepare->bindValue($key, utf8_encode(utf8_decode($param)));
                }

            } else {
                foreach ($values as $key => $param) {
                    $this->prepare->bindParam($key, utf8_encode(utf8_decode($param)));
                }
            }
            return true;
        }
        return false;
    }

    /**
     * Consigna una transacción
     * @link http://php.net/manual/es/pdo.begintransaction.php Se basa en el ejemplo
     * de transacciones que comento  "steve at fancyguy dot com"
     * @return boolean
     */
    public function commit()
    {
        if (!--$this->transaction) {
            return $this->conexion->commit();
        }
        return $this->transaction >= 0;
    }

    /**
     * Conexi&oacute;n con la base de datos <br />
     * En caso de error lo muestra y sale de la aplicacion
     * @param string $server Ip del servidor
     * @param string $db Nombre de la base de datos
     * @param string $user Nombre del usuario para conectarse
     * @param string $pass Clave del usuario para conectarse
     * @todo Revisar si es necesario salir de la aplicacion cuando no hay
     * conexion con la base de datos
     */
    private function conectar($dsn, $user, $pass)
    {

        try{
            $this->conexion = new PDO($dsn, $user, $pass, array(PDO::ATTR_ERRMODE =>PDO::ERRMODE_EXCEPTION));
            // set the PDO error mode to exception
            //$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        Catch(PDOException $e){
            echo "Error al conectarse al servidor";
            exit();
        }
    }

    /**
     * Envia el correo de error a los desarrolladores
     * @copyright (c) 2015, 2015-10-30
     * @param String $msg Mensaje de error
     * @todo No utilizada, hay que poner bien la ruta para enviar correos
     */
    public function enviar($msg)
    {
        /*
        $obj_mail = new PHPMailer();
        $obj_mail->Mailer = "smtp";
        //$obj_mail->Host = "mail.inatec.edu.ni";

        $obj_mail->From = "INATEC";
        $obj_mail->FromName = "INATEC - SERVICIOS EN LINEA - DESARROLLO";
        $obj_mail->Timeout = 30;

        $obj_mail->AddAddress("omartinez@inatec.edu.ni");
        $obj_mail->Subject = "Error en el Sistema";
        $obj_mail->Body = $msg;
        $obj_mail->IsHTML(true);*/

       // $obj_mail->Send();
    }

    /**
     * Escapa los caracteres especiales de un arreglo a UTF8  para
     * mostrarlo en una respuesta de JSON<br/>
     * Recursiva
     * @param Array|String $data Conjunto de datos a escapar
     * @param String $tipo Por el momento solo se ha definido json
     */
    public function escapa($data, $tipo = 'json')
    {

        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->escapa($value);
            }
            return $data;
        } else {

            if ('json' == $tipo and is_string($data)) {
                return utf8_encode($data);
            } else {
                return $data;
            }
        }
    }

    /**
     * Replaces any parameter placeholders in a query with the value of that
     * parameter. Useful for debugging. Assumes anonymous parameters from
     * $params are are in the same order as specified in $query
     *
     * @param string $query The sql query with parameter placeholders
     * @param array $params The array of substitution parameters
     * @return string The interpolated query
     */
    public function interpolateQuery( $params) {
        $query = $this->prepare->queryString;
        $keys = array();
        $values = $params;
        # build a regular expression for each parameter
        foreach ($params as $key => $value) {
            if (is_string($key)) {
                $keys[] = '/:'.$key.'/';
            } else {
                $keys[] = '/[?]/';
            }
            if (is_array($value))
                $values[$key] = implode(',', $value);
            if (is_null($value))
                $values[$key] = 'NULL';
        }
        // Walk the array to see if we can add single-quotes to strings
        array_walk($values, create_function('&$v, $k', 'if (!is_numeric($v) && $v!="NULL") $v = "\'".$v."\'";'));
        $query = preg_replace($keys, $values, $query, 1, $count);
        return $query;
    }

    /**
     * Ejecuta una sentencia preparada
     * @link http://php.net/manual/es/pdostatement.execute.php
     * @return boolean
     */
    public function execute()
    {
        if (!empty($this->prepare)) {
            try {
                $this->prepare->execute();

            } catch (PDOException $e) {
                $this->error = "Error en la ejecucion: ({$e->errorInfo[0]})<br />".
                    '<pre>'.$this->prepare->queryString.'</pre><br />'.
                    $e->errorInfo[2];

                $this->showError($this->error);
            }
            return true;
        }

        return false;
    }

    /**
     * Obtiene la siguiente fila de un conjunto de resultados
     * @link http://php.net/manual/es/pdostatement.fetch.php
     * @param $int $tipo <br />
     * 1. FETCH_ASSOC <br />
     * 2. FETCH_NUM (Array)<br />
     * 3. FETCH_OBJ <br />
     * 4. FETCH_BOTH Predeterminado
     */
    public function fetch($tipo = 4)
    {

        if (!empty($this->prepare)) {
            if (1 == $tipo) {
                $datos = $this->prepare->fetch(PDO::FETCH_ASSOC);
            } elseif (2 == $tipo) {
                $datos = $this->prepare->fetch(PDO::FETCH_NUM);
            } elseif (3 == $tipo) {
                $datos = $this->prepare->fetch(PDO::FETCH_OBJ);
            } else {
                $datos = $this->prepare->fetch();
            }
            return $datos;
        }
        return array();
    }

    /**
     * Devuelve un array que contiene todas las filas del conjunto de resultados
     * @link http://php.net/manual/es/pdostatement.fetchall.php
     * @param int $all Define el tipo de retorno de los datos: <br />
     * 1: Por defecto <br />
     * 2: Arreglo asociativo
     *
     * @return array
     */
    public function fetchAll($all = 1)
    {
        if (!empty($this->prepare)) {
            if (1 == $all) {
                return $this->prepare->fetchAll();
            } elseif (2 == $all) {
                return $this->prepare->fetchAll(PDO::FETCH_ASSOC);
            }
        }
        return array();
    }

    /**
     * Obtiene la siguiente fila de un conjunto de resultados, con el formato de
     * FETCH_NUM
     * @link  http://php.net/manual/es/pdostatement.fetch.php
     * @return array
     */
    public function fetchArray()
    {
        if (!empty($this->prepare)) {
            return $this->prepare->fetch(PDO::FETCH_NUM);
        }
        return array();
    }

    public function fetchObject()
    {
        if (!empty($this->prepare)) {
            return $this->prepare->fetchObject();
        }
        return null;
    }

    /**
     * Devuelve una única columna de la siguiente fila de un conjunto de resultados
     * @link http://php.net/manual/es/pdostatement.fetchcolumn.php
     * @return Object
     */
    public function fetchRow()
    {
        if (!empty($this->prepare)) {
            return $this->prepare->fetchColumn();
        }
        return array();
    }

    /**
     * Devuelve el número de columnas de un conjunto de resultados
     * @link http://php.net/manual/es/pdostatement.columncount.php
     * @return integer
     */
    public function numField()
    {
        if (!empty($this->prepare)) {
            return $this->prepare->columnCount();
        }
        return -1;
    }

    /**
     * Devuelve el número de filas afectadas por la última sentencia SQL
     * @link http://php.net/manual/es/pdostatement.rowcount.php
     * @return integer
     */
    public function numRow()
    {
        if (!empty($this->prepare)) {
            return $this->prepare->rowCount();
        }

        return -1;
    }

    /**
     * Prepara una sentencia para su ejecución
     * @link http://php.net/manual/es/pdo.prepare.php
     * @param string $sql Consulta a preparar
     * @return boolean
     */
    public function prepare($sql)
    {
        if (!empty($sql)) {
            $sql = SqlFormatter::format($sql, false);
            try {
                $this->prepare = $this->conexion->prepare($sql);
            } catch (PDOException $e) {
                $this->error = "Error en la consulta: ({$e->errorInfo[0]})<br />".
                    '<pre>'.$sql.'</pre><br />'.
                    $e->errorInfo[2];

                $this->showError($this->error);
            }
        }

        return false;
    }

    /**
     * Revierte una transacción
     * @link http://php.net/manual/es/pdo.rollback.php
     * @return boolean
     */
    public function rollBack()
    {
        if (--$this->transaction) {
            $this->conexion->exec('ROLLBACK TO trans'.$this->transaction + 1);
            return true;
        }
        return $this->conexion->rollBack();
    }

    /**
     * Genera el mensaje de error a imprimir en pantalla o enviar por correo
     * @param string $error Error
     * @copyright (c) 2015, 2015-10-30
     * @return String
     */
    public function showError($error)
    {

        if(isset($_POST['ruta'])){
            $ruta = $_POST['url'];
        }
        else{
            $ruta = "";
        }
        $ip = $_SERVER['REMOTE_ADDR'];
        $cliente = gethostbyaddr($_SERVER['REMOTE_ADDR']);
        $file = basename($ruta);
        $fecha = date('d/m/Y H:m:s');

        $msg_enviar = "<h2><u>Error en: Desarrollo - Servicios en Linea</u></h2>";
        $msg_enviar .="<u>Fallo la consulta a la base de datos</u><br />";
        $msg_enviar .= "<u>Fecha del error</u>: {$fecha} <br />";
        $msg_enviar .= "<u>URL del error</u>: {$ruta}<br />";
        $msg_enviar .= "<u>Archivo</u>: {$file}<br />";
        $msg_enviar .= "<u>IP</u>: {$ip}<br />";
        $msg_enviar .= "<u>Nombre de la PC del usuario</u>: {$cliente}<br />";
        $msg_enviar .= "<u>Descripci&oacute;n</u>: <br />";
        $msg_enviar .= str_replace("\r\n", "<br />", $error);
        $msg_enviar .= "<br><u>$this->query_exec</u>";

        //return $this->enviar($msg_enviar);

        echo $msg_enviar;
        return "error";
    }
}

/**
 * eof
 */