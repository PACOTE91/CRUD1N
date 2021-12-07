<?php

namespace Empresa;

use Faker;
use PDO;
use PDOException;

class Clientes extends ConexionBD
{

    private $id;
    private $vat;
    private $nombre;
    private $telefono;
    private $codigo_postal;
    private $provincia;
    private $poblacion;
    private $pais;

    public function __construct()
    {
        parent::__construct();
    }

    public function create()
    {
        if (isset($this->pais)) {
            $sql = "INSERT INTO clientes (vat, nombre, telefono,codigo_postal,provincia,poblacion,pais) VALUES (:v,:n,:t,:c,:p,:pob,'$this->pais')";
        } else {
            $sql = "INSERT INTO clientes (vat, nombre, telefono,codigo_postal,provincia,poblacion) VALUES (:v,:n,:t,:c,:p,:pob)";
        }


        $statement = parent::$conexion->prepare($sql);
        echo $sql."<br>";
        try {
            $statement->execute([
                ':v' => $this->vat,
                ':n' => $this->nombre,
                ':t' => $this->telefono,
                ':c' => $this->codigo_postal,
                ':p' => $this->provincia,
                ':pob' => $this->poblacion
            ]);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
        parent::$conexion = null;
    }


    public function read()
    {
        if (isset($this->id)) {
            $sql = "SELECT * FROM clientes WHERE id = '$this->id'";
        } else {
            $sql = "SELECT * FROM clientes";
        }
        $statement = parent::$conexion->prepare($sql);
        try {
            $statement->execute();
        } catch (PDOException $e) {
            die($e->getMessage());
        }
        parent::$conexion = null;
        return $statement;
    }

    public function readID()
    {

        $sql = "SELECT id FROM clientes";

        $statement = parent::$conexion->prepare($sql);
        try {
            $statement->execute();
        } catch (PDOException $e) {
            die($e->getMessage());
        }
        parent::$conexion = null;
        return $statement;
    }



    public function delete($id)
    {
        $sql = "DELETE FROM clientes WHERE id = '$id'";
        $statement = parent::$conexion->prepare($sql);
        try {
            $statement->execute();
        } catch (PDOException $e) {
            die($e->getMessage());
        }
        parent::$conexion = null;
    }


    public function update()
    {
        $sql = "UPDATE clientes SET
                vat=:v,
                nombre=:n,
                telefono=:t,
                codigo_postal=:c,
                provincia=:p,
                poblacion=:pob,
                pais=:pa
                WHERE id = '$this->id'";
        $statement = parent::$conexion->prepare($sql);
        try {
            $statement->execute([
                ':v' => $this->vat,
                ':n' => $this->nombre,
                ':t' => $this->telefono,
                ':c' => $this->codigo_postal,
                ':p' => $this->provincia,
                ':pob' => $this->poblacion,
                ':pa' => $this->pais
            ]);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
        parent::$conexion = null;
    }

    public function devuelveClientes()
    {
        $sql = "SELECT id,nombre FROM clientes";

        $statement = parent::$conexion->prepare($sql);
        try {
            $statement->execute();
        } catch (PDOException $e) {
            die($e->getMessage());
        }
        parent::$conexion = null;
        return $statement;
    }

    public function existeId($id)
    {
        $sql = "SELECT * FROM clientes WHERE id=$id";
        $statement = parent::$conexion->prepare($sql);

        try {
            $statement->execute();
        } catch (PDOException $e) {
            die($e->getMessage);
        }
        parent::$conexion = null;
        return ($statement->rowCount() != 0);
    }


    public function generarClientes($cant)
    {
        if (!$this->hayClientes()) {
            $faker = Faker\Factory::create('es_ES');
            //Array Asociativo con algunas provincias Españolas y 3 municipios para cada provincia
            $localidades = array(
                "Alava" => array("Vitoria", "Llodio", "Salvatierra"),
                "Albacete" => array("Yeste", "Hellin", "Almansa"),
                "Alicante" => array("Jijona", "Elche", "Orihuela"),
                "Almeria" => array("El Ejido", "Viator", "Huercal de Almeria"),
                "Asturias" => array("Gijon", "Oviedo", "Aviles"),
                "Avila" => array("El Miron", "Arevalo", "Arenas de San Pedro"),
                "Badajoz" => array("Don Benito", "Zafra", "Almendralejo"),
                "Barcelona" => array("Montcada I Reixac", "Badalona", "Parets del Valles"),
                "Burgos" => array("Aranda de Duero", "Lerma", "Miranda de Ebro"),
                "Cuenca" => array("Tarancon", "Iniesta", "Las Mesas"),
                "Gerona" => array("Vidreras", "Celra", "Caldes de Malavella"),
                "Granada" => array("Guadix", "Baza", "Fuente Vaqueros"),
                "Guadalajara" => array("Azuqueca de Henares", "Siguenza", "Cabanillas del Campo"),
                "Guipuzcoa" => array("San Sebastian", "Eibar", "Hondarribia"),
                "Huelva" => array("Matalascañas", "Almonte", "Cartaya"),
                "Huesca" => array("Alcala del Obispo", "Jaca", "Barbastro"),
                "Jaen" => array("Ubeda", "Linares", "Baeza")
            );
            //Array para generar una letra aleatoria en el numero de identificacion fiscal
            $abc = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "K", "L", "M", "N", "O", "P", "L", "M");
            //Razones sociales para concatenar despues del testo generado por faker
            $razonessociales = array(" S.A.", " S.L.", " S.L.U.", " S.A.D ", " S. Coop.", " S.C.", " S.A.L.");

            for ($i = 0; $i < $cant; $i++) {
                $vat = $abc[array_rand($abc, 1)] . random_int(111111111, 999999999);
                //Concatenamos a la palabra generada por faker alguna de las razones sociales del array superior
                $nombre = ucfirst($faker->words(3, true)) . $razonessociales[array_rand($razonessociales, 1)];
                $telefono = random_int(111111111, 999999999);
                $codigo_postal = $faker->randomNumber(5, true);
                //Generamos un numero (0,1,2) para elegir un municipio de los 3 disponibles
                $municipio_aleatorio = $faker->numberBetween(0, 2);
                $provincia_aleatoria = array_rand($localidades, 1);
                //Cargamos una poblacion en base a la clave aleatoria (Provincia) y el 0,1 o 2 de la poblACION (POSICION EN EL ARRAY)   
                $poblacion = $localidades[$provincia_aleatoria][$municipio_aleatorio];
                $provincia = $provincia_aleatoria;


                (new Clientes)
                    ->setVat($vat)
                    ->setNombre($nombre)
                    ->setTelefono($telefono)
                    ->setCodigo_postal($codigo_postal)
                    ->setProvincia($provincia)
                    ->setPoblacion($poblacion)
                    ->create();
            }
        }
    }


    public function hayClientes()
    {

        $sql = "SELECT * FROM clientes";

        $statement = parent::$conexion->prepare($sql);
        try {
            $statement->execute();
        } catch (PDOException $e) {
            die($e->getMessage());
        }
        parent::$conexion = null;
        return ($statement->rowCount() != 0);
    }

    public function valorUnico($campo,$valor){
        $sql="SELECT * FROM clientes WHERE $campo='$valor'";
        $stmt=parent::$conexion->prepare($sql);

        try{
            $stmt->execute();            
        }catch(PDOException $e){
            die("Error al comprobar unicidad ".$e->getMessage());
        }
        parent::$conexion = null;
        return ($stmt->rowCount()==0);
    }



    /**
     * Set the value of pais
     *
     * @return  self
     */
    public function setPais($pais)
    {
        $this->pais = $pais;

        return $this;
    }

    /**
     * Set the value of poblacion
     *
     * @return  self
     */
    public function setPoblacion($poblacion)
    {
        $this->poblacion = $poblacion;

        return $this;
    }

    /**
     * Set the value of provincia
     *
     * @return  self
     */
    public function setProvincia($provincia)
    {
        $this->provincia = $provincia;

        return $this;
    }

    /**
     * Set the value of codigo_postal
     *
     * @return  self
     */
    public function setCodigo_postal($codigo_postal)
    {
        $this->codigo_postal = $codigo_postal;

        return $this;
    }

    /**
     * Set the value of vat
     *
     * @return  self
     */
    public function setVat($vat)
    {
        $this->vat = $vat;

        return $this;
    }

    /**
     * Set the value of nombre
     *
     * @return  self
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Set the value of telefono
     *
     * @return  self
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
}
