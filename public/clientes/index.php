<?php
require dirname(__DIR__, 2) . "/vendor/autoload.php";

session_start();


use Empresa\ConexionBD;
use Empresa\Clientes;

(new Clientes)->generarClientes(20);
$statement = (new Clientes)->read();
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
            font-size: 120%
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
                <li><a href="../facturas/index.php"><i class="fas fa-file-invoice"></i>Facturas</a></li>
                <li><a href="ccliente.php"><i class="fas fa-user-plus"></i>Añadir cliente</a></li>
            </ul>
        </nav>
    </header>
    <h4>CLIENTES</h4>

    <div class="container mt-2">

        <!-- Muestra mensaje de elemento creado/eliminado -->



        <table class="table ancho">
            <?php
            if (isset($_SESSION['mensaje'])) {
                echo "<div class='alert alert-danger' role='alert'>";
                echo "<div style='text-align: center' class='alert alert-dark' role='alert'><i class='fas fa-info-square'> </i> {$_SESSION['mensaje']}</div>";
                unset($_SESSION['mensaje']);
            }

            ?>
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">VAT</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Teléfono</th>
                    <th scope="col">C.P.</th>
                    <th scope="col">Poblacion</th>
                    <th scope="col">Provincia</th>
                    <th scope="col">Pais</th>
                    <th scope="col">Opciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($fila = $statement->fetch(PDO::FETCH_OBJ)) {
                    echo <<<TXT
                    <tr>
                    <td style="vertical-align:middle;text-align:center" scope="row">{$fila->id}</td>
                    <td style="vertical-align:middle">{$fila->vat}</td>
                    <td style="vertical-align:middle">{$fila->nombre}</td>
                    <td style="vertical-align:middle">{$fila->telefono}</td>
                    <td style="vertical-align:middle">{$fila->codigo_postal}</td>
                    <td style="vertical-align:middle">{$fila->poblacion}</td>
                    <td style="vertical-align:middle">{$fila->provincia}</td>
                    <td style="vertical-align:middle">{$fila->pais}</td>                    
                    <td style="vertical-align:middle;text-align:center">

                    <a href="bcliente.php?id={$fila->id}" class="borrar"><i class='fas fa-trash'></i></a>

                    <a href=" ecliente.php?id={$fila->id}" class="borrar"><i class='fas fa-edit'></i></a>
                    <a href="ccliente.php" class="borrar"><i class="fas fa-plus"></i></a>
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