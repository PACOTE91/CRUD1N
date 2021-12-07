<?php

namespace Empresa;

use Faker;
use PDO;
use PDOException;

class Facturas extends ConexionBD
{

    private $id;
    private $fecha;
    private $neto;
    private $porcentaje;
    private $impuestos;
    private $archivo;

    private $total;
    private $id_cliente;

    public function __construct()
    {
        parent::__construct();
    }


    public function create()
    {
        $sql = "INSERT INTO facturas (fecha,neto, porcentaje,impuestos,archivo,total,id_cliente)
              VALUES (NOW(),:n,:p,:i,:a,:t,:id)";
        $statement = parent::$conexion->prepare($sql);
        try {
            $statement->execute([
                ':n' => $this->neto,
                ':p' => $this->porcentaje,
                ':i' => $this->impuestos,
                ':t' => $this->total,
                ':id' => $this->id_cliente,
                ':a' => $this->archivo

            ]);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
        parent::$conexion = null;
    }


    public function read()
    {
        if (isset($this->id)) {
            $sql = "SELECT * FROM facturas WHERE id = '$this->id'";
        } else {
            $sql = "SELECT * FROM facturas";
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



    public function delete($id)
    {
        $sql = "DELETE FROM facturas WHERE id = '$id'";
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
        $sql = "UPDATE facturas SET
                neto=:n,
                porcentaje=:p,
                impuestos=:i,
                total=:t,
                id_cliente=:d,
                archivo=:a   
                WHERE id = $this->id";
        $statement = parent::$conexion->prepare($sql);
        try {
            $statement->execute([
                ':n' => $this->neto,
                ':p' => $this->porcentaje,
                ':i' => $this->impuestos,
                ':t' => $this->total,
                ':d' => $this->id_cliente,
                ':a' => $this->archivo
            ]);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
        parent::$conexion = null;
    }

    public function hayFacturas()
    {
        $sql = "SELECT * FROM facturas";

        $statement = parent::$conexion->prepare($sql);
        try {
            $statement->execute();
        } catch (PDOException $e) {
            die($e->getMessage());
        }
        parent::$conexion = null;
        return ($statement->rowCount() != 0);
    }

    public function existeId($id)
    {
        $sql = "SELECT * FROM facturas WHERE id=$id";
        $statement = parent::$conexion->prepare($sql);

        try {
            $statement->execute();
        } catch (PDOException $e) {
            die($e->getMessage());
        }
        parent::$conexion = null;
        return ($statement->rowCount() != 0);
    }

    public function detallefactura($id)
    {
        $sql = "SELECT facturas.*, clientes.nombre,clientes.vat 
        FROM facturas,clientes WHERE clientes.id=facturas.id_cliente AND facturas.id=$id";

        $statement = parent::$conexion->prepare($sql);

        try {
            $statement->execute();
        } catch (PDOException $e) {
            die($e->getMessage());
        }
        parent::$conexion = null;
        return $statement=$statement->fetch(PDO::FETCH_OBJ);
    }


    
    public function filtrar($valor){
        $sql="SELECT * FROM facturas WHERE id_cliente=$valor";
        $statement = parent::$conexion->prepare($sql);

        try {
            $statement->execute();
        } catch (PDOException $e) {
            die($e->getMessage());
        }
        parent::$conexion = null;
        return $statement;
    }

    public function generarFacturas($cant)
    {
        if (!$this->hayFacturas()) {
            $faker = Faker\Factory::create('es_ES');
            $clientes = (new Clientes)->readID();
            $array = array();
            while ($fila = $clientes->fetch(PDO::FETCH_OBJ)) {
                array_push($array, $fila->id);
            }

            for ($i = 0; $i < $cant; $i++) {
                $neto = $faker->randomFloat(2, 50, 99999);
                $porcentaje = $faker->randomElement($iva = array(4, 10, 21));
                $impuestos = $neto * ($porcentaje / 100);
                $total = $neto + $impuestos;
                $id_cliente = $array[array_rand($array, 1)];
                $archivo = "http://localhost/pdo/empresa/public/subidas/default.pdf";
                (new Facturas)
                    ->setNeto($neto)
                    ->setPorcentaje($porcentaje)
                    ->setImpuestos($impuestos)
                    ->setArchivo($archivo)
                    ->setTotal($total)
                    ->setId_cliente($id_cliente)
                    ->create();
            }
        }
    }


    /**
     * Set the value of neto
     *
     * @return  self
     */
    public function setNeto($neto)
    {
        $this->neto = $neto;

        return $this;
    }

    /**
     * Set the value of porcentaje
     *
     * @return  self
     */
    public function setPorcentaje($porcentaje)
    {
        $this->porcentaje = $porcentaje;

        return $this;
    }

    /**
     * Set the value of impuestos
     *
     * @return  self
     */
    public function setImpuestos($impuestos)
    {
        $this->impuestos = $impuestos;

        return $this;
    }



    /**
     * Set the value of id_cliente
     *
     * @return  self
     */
    public function setId_cliente($id_cliente)
    {
        $this->id_cliente = $id_cliente;

        return $this;
    }

    /**
     * Set the value of total
     *
     * @return  self
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Set the value of archivo
     *
     * @return  self
     */
    public function setArchivo($archivo)
    {
        $this->archivo = $archivo;

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
