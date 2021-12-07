<?php


require dirname(__DIR__, 2) . "/vendor/autoload.php";

use Empresa\Clientes;

session_start();

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

function toNull($variable)
{
    if ($variable == "") {
        $variable = null;
        return $variable;
    }
}

if (isset($_POST['enviar'])) {
    global $error;

    $vat = ucfirst(trim($_POST['vat']));
    $nombre = ucfirst(trim($_POST['nombre']));
    $telefono = ucfirst(trim($_POST['telefono']));
    $cp = ucfirst(trim($_POST['cp']));
    $provincia = ucfirst(trim($_POST['provincia']));
    $poblacion = ucfirst(trim($_POST['poblacion']));
    $pais = ucfirst(trim($_POST['pais']));

    if(!(new Clientes)->valorUnico('nombre',$nombre)){
        $error=true;
        $_SESSION["nombre"] = "Este nombre de empresa ya existe";

    }else if(!(new Clientes)->valorUnico('vat', $vat)){
        $error = true;
        $_SESSION["vat"] = "Este nombre de VAT ya existe";

    }


    hay_Error($nombre, "nombre");
    hay_Error($telefono, "telefono");
    hay_Error($cp, "cp");
    hay_Error($vat, "vat");

    $pais = toNull($pais);
    $poblacion = toNull($poblacion);
  

    if (!$error) {
        (new Clientes)
            ->setVat($vat)
            ->setNombre($nombre)
            ->setTelefono($telefono)
            ->setCodigo_postal($cp)
            ->setProvincia($provincia)
            ->setPoblacion($poblacion)
            ->setPais($pais)
            ->create();

        header("Location:index.php");
        $_SESSION["mensaje"] = "Cliente creado";
    } else {
        header("Location:ccliente.php");
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
        <title>Crear Clientes</title>
    </head>

    <body>
        <h2>CREAR CLIENTE</h2>
        <header>
            <nav>
                <ul>
                    <li><a href="../index.php"><i class="fas fa-home"></i>Inicio</a></li>
                </ul>
            </nav>
        </header>
        <div class="card-group">
            <div class="card">
                <h5 class="card-title">NUEVO CLIENTE                </h5>
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/c/ce/Plus_font_awesome.svg/1200px-Plus_font_awesome.svg.png" alt="...">


                <form action="ccliente.php" method="post" enctype="multipart/form-data">

                    <div class="form-group">
                        <label for="vat" class="obligatorio">VAT/CIF</label>
                        <input type="text" required class="form-control" id="vat" name="vat" placeholder="CIF/VAT">
                        <?php if (isset($_SESSION['vat'])) {
                            echo <<<TXT
                                    <div class="alert alert-primary" role="alert">
                                    Error en el campo VAT/CIF
                                    </div>
                                    TXT;
                            unset($_SESSION['vat']);
                        } ?>
                    </div>

                    <div class="form-group">
                        <label for="nombre" class="obligatorio">Nombre</label>
                        <input type="text" required class="form-control" id="nombre" name="nombre" placeholder="Nombre">
                        <?php
                        if (isset($_SESSION['nombre'])) {
                            echo <<<TXT
                                    <div class="alert alert-primary" role="alert">
                                    Error en el campo Nombre
                                    </div>
                                    TXT;
                            unset($_SESSION['nombre']);
                        } ?>
                    </div>
                    <div class="form-group">
                        <label for="telefono" class="obligatorio">Teléfono</label>
                        <input type="text" required class="form-control" id="telefono" name="telefono" placeholder="Telefono">
                        <?php
                        if (isset($_SESSION['telefono'])) {
                            echo <<<TXT
                                    <div class="alert alert-primary" role="alert">
                                    Error en el campo Telefono
                                    </div>
                                    TXT;
                            unset($_SESSION['telefono']);
                        }
                        ?>
                    </div>

                    <div class="form-group">

                        <label for="pais">Pais</label>
                        <input type="text" class="form-control" id="pais" name="pais" name="pais" placeholder="Pais">
                    </div>

                    <div class="form-group">
                        <?php
                        if (isset($_SESSION['cp'])) {
                            echo <<<TXT
                                    <div class="alert alert-primary" role="alert">
                                    Error en el campo Codigo Postal
                                    </div>
                                    TXT;
                            unset($_SESSION['cp']);
                        }
                        ?>
                        <label for="cp" class="obligatorio">Codigo Postal</label>
                        <input type="number" required class="form-control" id="cp" name="cp" placeholder="Codigo Postal">
                    </div>
                    <div class="form-group">
                        <label for="provincia">Provincia</label>
                        <input type="text" class="form-control" id="provincia" name="provincia" placeholder="Provincia">
                    </div>
                    <div class="form-group">
                        <label for="poblacion">Poblacion</label>
                        <input type="text" class="form-control" id="poblacion" name="poblacion" placeholder="Población">
                    </div>

                    <div class="form-group">
                        <input type="submit" name="enviar" value="Enviar">
                        <input type="reset" name="borrar" value="Borrar">
                    </div>

                </form>
            </div>
        </div>
    </body>

    </html>
<?php } ?>