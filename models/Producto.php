<?php

/**
 * ARCHIVO: models/Producto.php
 * FUNCIÓN: Gestionar los datos de los productos de ropa
 */

class Producto
{
   private $conn;
   private $table_name = "productos";

   // Propiedades del producto
   public $id;
   public $nombre;
   public $descripcion;
   public $precio;
   public $stock;

   public function __construct($db)
   {
      $this->conn = $db;
   }

   // Método para obtener todos los productos (Visualización)
   public function leerTodos()
   {
      $query = "SELECT * FROM " . $this->table_name . " ORDER BY fecha_creacion DESC";
      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      return $stmt;
   }

   // Método para registrar un nuevo producto
   public function crear()
   {
      $query = "INSERT INTO " . $this->table_name . " 
                  SET nombre=:nombre, descripcion=:descripcion, precio=:precio, stock=:stock";

      $stmt = $this->conn->prepare($query);

      // Limpiar datos (Seguridad)
      $this->nombre = htmlspecialchars(strip_tags($this->nombre));
      $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));

      // Vincular parámetros
      $stmt->bindParam(":nombre", $this->nombre);
      $stmt->bindParam(":descripcion", $this->descripcion);
      $stmt->bindParam(":precio", $this->precio);
      $stmt->bindParam(":stock", $this->stock);

      if ($stmt->execute()) {
         return true;
      }
      return false;
   }
}
