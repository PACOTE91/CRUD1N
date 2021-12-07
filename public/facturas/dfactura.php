<?php
require dirname(__DIR__, 2) . "/vendor/autoload.php";

session_start();

use Empresa\ConexionBD;
use Empresa\Facturas;

if (!isset($_GET['id'])) {
    header("Location:index.php");
}

$obj = (new Facturas)->detallefactura($_GET['id']);



$documento = $obj->archivo;


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
    <?php
    if ($documento != "" || $documento != null) {
        echo "<link rel='stylesheet' href='../css/pdf.css' type='text/css'>";
    } else {
        echo "<link rel='stylesheet' href='../css/estilos.css' type='text/css'>";
    }
    ?>
    <title>Inicio Coches</title>



</head>

<body>
    <header>
        <nav>
            <ul>
                <li><a href="../index.php"><i class="fas fa-home"></i>Inicio</a></li>
                <li><a href="../clientes/index.php"><i class="fas fa-file-invoice"></i>Clientes</a></li>
                <li><a href="cfactura.php"><i class="fas fa-user-plus"></i>Añadir factura</a></li>
            </ul>
        </nav>
    </header>
    <h4>DETALLES FACTURA <?php echo $obj->id ?></h4>

    <main>

        <section>
            <div>
                <div>
                    <div class="card">
                        <h5 class="card-header">DETALLE FACTURA</h5>
                        <div class="card-body">
                            <p class="card-text"><b>Cliente</b> <a id="filtro_nom" href="filtro.php?id=<?php echo $obj->id_cliente ?>" class="borrar"><?php echo $obj->nombre ?></p></a>
                            <p class="card-text"><b>CIF</b> <?php echo $obj->vat ?></p>
                            <p class="card-text"><b>Importe Neto</b> <?php echo $obj->neto ?>€</p>
                            <p class="card-text"><b>% Impuestos</b> <?php echo $obj->porcentaje ?> %</p>
                            <p class="card-text"><b>Importe Impuestos</b> <?php echo $obj->impuestos ?>€</p>
                            <p class="card-text"><b>Importe Total</b> <?php echo $obj->total ?>€</p>
                        </div>
                    </div>
                </div>
                <?php
                if (isset($documento) && $documento != "") {
                    echo <<< TXT
                
                <iframe id="documento" scrolling="auto" name="descFrame" src="$documento" width="80%" height="80%" frameborder="4"></iframe> 
                TXT;
                }
                ?>
            </div>
        </section>

    </main>


</body>

</html>