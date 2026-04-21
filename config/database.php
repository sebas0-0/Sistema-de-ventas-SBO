<?php

#configuración de la conexión a la base de datos usando PDO

class Database
{
   #atributos privados para la configuración de la conexión
   private $host = "localhost";
   private $db_name = "tienda_ropa"; // Nombre de la base de datos
   private $username = "root";       // Usuario por defecto en XAMPP
   private $password = "unam-S21759ASD";           // Contraseña por defecto en XAMPP (vacía)
   public $conn;

   #método para obtener la conexión a la base de datos
   public function getConnection()
   {
      $this->conn = null;

      #try-catch para manejar errores de conexión
      try {
         $this->conn = new PDO(
            "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
            $this->username,
            $this->password
         );
         // configura las excepciones en caso de error
         $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         // uso de UTF-8 para evitar problemas con tildes y eñes
         $this->conn->exec("set names utf8");
      } catch (PDOException $exception) {
         echo "Error de conexión: " . $exception->getMessage();
      }

      return $this->conn;
   }
}
