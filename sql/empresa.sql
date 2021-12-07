CREATE database empresa ;

USE empresa;

CREATE TABLE clientes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  vat VARCHAR(12) NOT NULL UNIQUE,
  nombre VARCHAR(150) NOT NULL UNIQUE,
  telefono INT(9) NOT NULL,
  codigo_postal INT(5) NOT NULL,
  provincia VARCHAR(50) DEFAULT NULL,
  poblacion VARCHAR(50) DEFAULT NULL,
  pais VARCHAR(50) DEFAULT 'España' NOT NULL  
); 


CREATE table facturas(
    id INT AUTO_INCREMENT PRIMARY KEY,
    fecha DATE  NOT NULL,
    neto DECIMAL(10,2) NOT NULL,
    porcentaje INT(2) NOT NULL,
    impuestos FLOAT(10,2) NOT NULL,
    total FLOAT(10,2) NOT NULL,
    archivo VARCHAR (400), 
    id_cliente INT NOT NULL,  
  CONSTRAINT fk_facturas FOREIGN KEY (id_cliente) references clientes(id) ON DELETE CASCADE ON UPDATE CASCADE
);