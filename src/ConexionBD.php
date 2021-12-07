<?php
namespace Empresa;

use PDO;
use PDOException;

class ConexionBD{
    protected static $conexion;

    public function __construct(){
        if(self::$conexion==null){
            self::crearConexion();
        }
    }

    static function cerrar(){
        self::$conexion=null;
    }

    
    public static function crearConexion(){

        $ruta_conf=dirname(__DIR__,1).'/configuracion.ini';
        $configuracion=parse_ini_file($ruta_conf);
        $usuario=$configuracion['usuario'];
        $pass=$configuracion['pass'];
        $servidor=$configuracion['servidor'];
        $bd=$configuracion['bd'];
        $dns = "mysql:host=$servidor;dbname=$bd;charset=utf8mb4";

        try {
            self::$conexion = new PDO($dns, $usuario, $pass);
            self::$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);                                    

        } catch (PDOException $ex) {
            die("Error en la conexion!!! :" . $ex->getMessage());
        }
    }
}
