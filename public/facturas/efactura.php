<?php

session_start();

use Empresa\Facturas;
use Empresa\Clientes;

require dirname(__DIR__, 2) . "/vendor/autoload.php";

if (!isset($_GET['id']) || (!isset($_GET['cliente']))) {
    header("Location:index.php");
}

$id = $_GET['id'];
$cliente = $_GET['cliente'];
$statement = (new Facturas)->setId($id)->read();

$array = $statement->fetch(PDO::FETCH_OBJ);

$mime = ["application/pdf", "application/vnd.ms-powerpoint
", "application/msword
"];

$error = false;


function hay_Error($variable, $campo)
{
    global $error;
    $variable = trim($variable);
    if ($variable == "") {
        $error = true;
        $_SESSION["$campo"] = "Error en el campo " . $campo;
    }
}


if (isset($_POST['enviar'])) {
    global $error;

    $importe_neto = $_POST['importe'];
    $porcentaje = ucfirst(trim($_POST['impuestos']));
    $cliente = ucfirst($_POST['cliente']);


    $documento_actual = $_POST['url'];

    $documento = null;

    hay_Error($cliente, "cliente");
    hay_Error($importe_neto, "importe");
    hay_Error($porcentaje, "impuestos");



    if (!$error) {
        $importe_impuestos = intval($importe_neto) * intval($porcentaje) / 100;
        $total = intval($importe_impuestos) + intval($importe_neto);
        if (isset($_FILES['archivo']) && !empty($_FILES['archivo']['name'])) {
            //Para no arrastrar $_FILES este será nuestro array con archivos subidos
            $archivo = $_FILES['archivo'];

            //Nombre actual del archivo subido
            $nombre_documento = $archivo['name'];

            //Tipo myme del archivo subido
            $tipo = $archivo['type'];

            //Ruta temporal al archivo
            $ruta_documento = $archivo['tmp_name'];

            //Directorio de subida, será la ruta padre + /subidas/
            $directorio = dirname(__DIR__) . "/subidas/";

            //Esta será la ruta fisica a nuestro archivo subido (asignamos) un id
            $ruta_al_archivo = $directorio . uniqid() . $nombre_documento;

            //Este será el nombre del archivo en destino
            $nombre_archivo = basename($ruta_al_archivo);


            if (in_array($tipo, $mime)) {
                //Si se ha movido el archivo
                if (move_uploaded_file($ruta_documento, $ruta_al_archivo)) {
                    //Nuestro documento es la url del servidor concatenado el nombre
                    $documento = "http://localhost/pdo/empresa/public/subidas/" . $nombre_archivo;
                } else {
                    $_SESSION['error'] = "NO se ha podido mover el archivo";
                    header("Location:efactura.php?id=$id&cliente=$cliente");
                }
            } else {
                $_SESSION['error'] = "NO es un archivo admitido";
                header("Location:efactura.php?id=$id&cliente=$cliente");
            }
        }

        if ($documento == null || $documento == "") {
            $documento = $documento_actual;
        }

        $directorio = dirname(__DIR__) . "/subidas/" . basename($documento_actual);
        var_dump($directorio);
        if ($_POST['borrar'] == "on") {
            if (basename($documento_actual) != "default.pdf") {
                unlink($directorio);
                echo "Enrado";
            }
            $documento = null;

        }


        (new Facturas)
            ->setId($id)
            ->setNeto($importe_neto)
            ->setPorcentaje($porcentaje)
            ->setImpuestos($importe_impuestos)
            ->setArchivo($documento)
            ->setTotal($total)
            ->setId_cliente($cliente)
            ->update();

        header("Location:index.php");
    } else {
        header("Location:efactura.php?id=$id&cliente=$cliente");
    }
} else {
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
        <title>Crear Factura</title>
    </head>

    <body>
        <header>
            <nav>
                <ul>
                    <li><a href="../index.php"><i class="fas fa-home"></i>Inicio</a></li>
                </ul>
            </nav>
        </header>
        <h2>EDITAR FACTURA</h2>
        <div class="card-group">

            <div class="card">
                <h5 class="card-title">EDITAR FACTURA<br>
                </h5>
                <img src="https://cdn-icons-png.flaticon.com/512/1449/1449943.png" class="card-img-top" alt="...">

                <form action="efactura.php?id=<?php echo $id ?>&cliente=<?php echo $cliente ?>" method="POST" enctype="multipart/form-data">

                    <div class="form-group">

                        <label style="display:block;" for="id">ID</label>
                        <input type="text" disabled name="id" class="form-control" value="<?php echo $array->id ?>">
                    </div>

                    <div class="form-group">

                        <label style="display:block;" for="id">Fecha Creacion</label>
                        <input type="text" disabled name="fecha" class="form-control" value="<?php echo $array->fecha ?>">
                    </div>

                    <div class="form-group">
                        <?php
                        if (isset($_SESSION['cliente'])) {
                            echo <<<TXT
                                    <div class="alert alert-primary" role="alert">
                                    Error en el campo Cliente
                                    </div>
                                    TXT;
                            unset($_SESSION['cliente']);
                        } ?>
                        <label style="display:block;" for="cliente" class="obligatorio">Cliente</label>
                        <select class="form-control" id="cliente" required name="cliente">
                            <?php
                            $statement = (new Clientes)->devuelveClientes();
                            while ($fila = $statement->fetch(PDO::FETCH_OBJ)) {
                                if ($fila->id == $cliente) {
                                    echo "<option selected value='{$fila->id}'>{$fila->nombre}</option>";
                                } else {
                                    echo "<option value='{$fila->id}'>{$fila->nombre}</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <?php
                        if (isset($_SESSION['importe'])) {
                            echo <<<TXT
                                    <div class="alert alert-primary" role="alert">
                                    Error en el campo Importe
                                    </div>
                                    TXT;
                            unset($_SESSION['importe']);
                        } ?>
                        <label for="importe" class="obligatorio">Importe Neto</label>
                        <input type="text" required class="form-control" id="importe" value="<?php echo $array->neto . "€" ?>" name="importe" placeholder="Importe Neto">
                    </div>
                    <div class="form-group">
                        <?php
                        if (isset($_SESSION['impuestos'])) {
                            echo <<<TXT
                                    <div class="alert alert-primary" role="alert">
                                    Error en el porcentaje introducido
                                    </div>
                                    TXT;
                            unset($_SESSION['impuestos']);
                        } ?>
                        <label for="impuestos" class="obligatorio">Impuestos</label>
                        <input type="number" class="form-control" id="impuestos" min="0" max="100" value="<?php echo intval($array->porcentaje) ?>" required name="impuestos" placeholder="Impuestos (0-100)">

                    </div>

                    <div class="form-group">

                        <label style="display:block;" for="id">IVA Devengado</label>
                        <input type="text" disabled name="id" class="form-control" value="<?php echo $array->impuestos . "€" ?>">
                    </div>

                    <div class="form-group">
                        <label style="display:block;" for="id">Total</label>
                        <input type="text" disabled name="id" class="form-control" value="<?php echo $array->total . "€" ?>">
                    </div>


                    <div class="form-group">
                        <label style="display:block;" for="id">Factura Actual</label>
                        <input type="hidden" name="url" class="form-control" value="<?php echo $array->archivo ?>">

                        <?php
                        if ($array->archivo != "" || $array->archivo != null) {
                            echo <<<TXT
                                        <object data="{$array->archivo}" type="application/pdf">
                                        <div>No online PDF viewer installed</div>
                                         </object>

                                        <div class="form-group">
                                         <label style="display:block;" for="exampleCheck1">Eliminar archivo <input id="borrame" type="checkbox" name="borrar" onclick="ocultar()"  id="exampleCheck1">   
                                         </label>                                                                             
                                        </div>
                            TXT;
                        } else {
                            echo "No hay factura asignada todavia";
                        }
                        ?>


                    </div>

                    <div id="subida" class="form-group">
                        <?php
                        if (isset($_SESSION['error'])) {
                            echo <<<TXT
                                    <div class="alert alert-primary" role="alert">
                                    {$_SESSION['error']}
                                    </div>
                                    TXT;
                            unset($_SESSION['error']);
                        } ?>
                        <label for="archivo">Subir archivo (opcional)</label>
                        <input type="file" class="form-control" name="archivo" id="archivo">
                    </div>


                    <div class="form-group">
                        <input type="submit" name="enviar" value="Enviar">
                        <input type="reset" name="borrar" value="Borrar">
                    </div>

                </form>

               

                <?php
                        if ($array->archivo != "" || $array->archivo != null) {
                            echo<<<EOD
                            <script>
                            var borrame = document.getElementById("borrame");
                            var subida = document.getElementById("subida");

                            function ocultar() {
                                if (borrame.checked == true) {
                                    subida.style.display = "none";
                                } else {
                                    subida.style.display = "block";
                                }
                            }
                                </script>
                            EOD;
                        }
                        ?>
            </div>

        </div>
    </body>

    </html>
<?php } ?>