<?php

 if(!isset($_GET['id'])){
     header("Location:index.php");
 }
session_start();

use Empresa\Clientes;
require dirname(__DIR__,2).'/vendor/autoload.php';

$cliente=(new Clientes)->existeId($_GET['id']);

if($cliente){ 
    $cliente=(new Clientes);
    $cliente->delete($_GET['id']);
    $_SESSION['mensaje']="Cliente ".$_GET['id']." eliminado";
}else{ 
    $_SESSION['mensaje']="El cliente no ha podido ser eliminado";
}

header("Location:index.php");
