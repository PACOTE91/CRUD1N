<?php

 if(!isset($_GET['id'])){
     header("Location:index.php");
 }
session_start();
use Empresa\Facturas;
require dirname(__DIR__,2).'/vendor/autoload.php';

$factura=(new Facturas)->existeId($_GET['id']);

if($factura){ 
    $factura=(new Facturas);
    $factura->delete($_GET['id']);
    $_SESSION['mensaje']="Factura ".$_GET['id']." eliminada";
}else{ 
    $_SESSION['mensaje']="La factura no ha podido ser eliminado";
}

header("Location:index.php");
