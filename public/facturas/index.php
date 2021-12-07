<?php
require dirname(__DIR__, 2) . "/vendor/autoload.php";

session_start();

use Empresa\ConexionBD;
use Empresa\Facturas;

(new Facturas)->generarFacturas(20);
$statement = (new Facturas)->read();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- BootStrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- FONTAWESOME -->
    <link href="http://localhost/FontAwesome/css/all.css" rel="stylesheet" type="text/css">
    <link rel=" preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/estilos.css" type="text/css">
    <title>Inicio Coches</title>


    <style>
        #info:hover {
            color: red;
            font-size: 110%
        }

        a {
            color: black
        }
    </style>

</head>

<body>
    <header>
        <nav>
            <ul>
                <li><a href="../index.php"><i class="fas fa-home"></i>Inicio</a></li>
                <li><a href="../clientes/index.php"><i class="fas fa-user-plus"></i>Clientes</a></li>
                <li><a href="cfactura.php"><i class="fas fa-file-invoice"></i>Añadir factura</a></li>
            </ul>
        </nav>
    </header>
    <h4>FACTURAS</h4>

    <div class="container mt-2">

        <!-- Muestra mensaje de elemento creado/eliminado -->



        <table class="table ancho">
            <?php
            if (isset($_SESSION['mensaje'])) {
                echo "<div class='alert alert-danger' role='alert'>";
                echo "<div style='text-align: center'class='alert alert-dark' role='alert'><i class='fas fa-info-square'> </i> {$_SESSION['mensaje']}</div>";
                unset($_SESSION['mensaje']);
            }

            ?>
    </div>
    <thead>
        <tr>
            <th scope="col">Info</th>
            <th scope="col">ID</th>
            <th scope="col">Fecha</th>
            <th scope="col">Imp. Neto</th>
            <th scope="col">IVA</th>
            <th scope="col">Impuestos</th>
            <th scope="col">Total</th>
            <th scope="col">Cliente</th>
            <th scope="col">Documento</th>
            <th scope="col">Opciones</th>

        </tr>
    </thead>
    <tbody>
        <?php



        while ($fila = $statement->fetch(PDO::FETCH_OBJ)) {
            //Formateamos los numeros decimales
            $neto = number_format($fila->neto, 2, ',', ' ');
            $impuestos = number_format($fila->impuestos, 2, ',', ' ');
            $total = number_format($fila->total, 2, ',', ' ');
            //Formateamos la fecha a dd/mm/yyyy
            $fecha = date_create($fila->fecha);
            $fecha_formato = date_format($fecha, "d/m/Y");

            echo <<<TXT
                    <tr>
                    <td style="vertical-align:middle;text-align:center" scope="row">
                    <a id="info" href='dfactura.php?id={$fila->id}'><i class="fas fa-info"></i></a>                    
                    </td>
                    <td style="vertical-align:middle">{$fila->id}</td>

                    <td style="vertical-align:middle">{$fecha_formato}</td>
                    <td style="vertical-align:middle">{$neto}</td>
                    <td style="vertical-align:middle">{$fila->porcentaje}%</td>
                    <td style="vertical-align:middle">{$impuestos}€</td>
                    <td style="vertical-align:middle">{$total}€</td>
                    <td style="vertical-align:middle">{$fila->id_cliente}</td>
                TXT;

            if (($fila->archivo != null) || ($fila->archivo != "")) {
                echo "<td style='vertical-align:middle'><a target='_blank' href='{$fila->archivo}'>Documento</a></td>";
            } else {
                echo "<td style='vertical-align:middle'></td>";
            }
            echo <<<TXT
                    <td style="vertical-align:middle;text-align:center">
                    <a href="bfactura.php?id={$fila->id}" class="borrar"><i class='fas fa-trash'></i></a>                   
                    <a href="efactura.php?id={$fila->id}&cliente={$fila->id_cliente}" class="borrar"><i class='fas fa-edit'></i></a>
                    <a href="cfactura.php" class="borrar"><i class="fas fa-plus"></i></a>
                    </td>           
                    </tr>
            TXT;
        }
        ?>

    </tbody>
    </table>
    </div>
</body>

</html>