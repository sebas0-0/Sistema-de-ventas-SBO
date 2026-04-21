<?php
class Producto
{
   private $conn;
   private $table_name = "productos";

   #atributos públicos para facilitar la asignación desde el controlador
   public $id;
   public $nombre;
   public $descripcion;
   public $precio;
   public $stock;
   
   #constructor que recibe la conexión a la base de datos
   public function __construct($db)
   {
      $this->conn = $db;
   }

   #método para leer todos los productos
   public function leerTodos()
   {
      $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC";
      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      return $stmt;
   }

   #método para leer un solo producto por su ID
   public function leerUno()
   {
      $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(1, $this->id);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if ($row) {
         $this->nombre = $row['nombre'];
         $this->descripcion = $row['descripcion'];
         $this->precio = $row['precio'];
         $this->stock = $row['stock'];
      }
   }

   #método para crear un nuevo producto
   public function crear()
   {
      $query = "INSERT INTO " . $this->table_name . " SET nombre=:nombre, descripcion=:descripcion, precio=:precio, stock=:stock";
      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(":nombre", $this->nombre);
      $stmt->bindParam(":descripcion", $this->descripcion);
      $stmt->bindParam(":precio", $this->precio);
      $stmt->bindParam(":stock", $this->stock);
      return $stmt->execute();
   }

   #método para actualizar un producto existente
   public function actualizar()
   {
      $query = "UPDATE " . $this->table_name . " SET nombre=:nombre, descripcion=:descripcion, precio=:precio, stock=:stock WHERE id=:id";
      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(":nombre", $this->nombre);
      $stmt->bindParam(":descripcion", $this->descripcion);
      $stmt->bindParam(":precio", $this->precio);
      $stmt->bindParam(":stock", $this->stock);
      $stmt->bindParam(":id", $this->id);
      return $stmt->execute();
   }

   #método para eliminar un producto
   public function eliminar()
   {
      $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(1, $this->id);
      return $stmt->execute();
   }

   //Método para descontar stock tras una venta
   public function descontarStock($cantidad)
   {
      $query = "UPDATE " . $this->table_name . " SET stock = stock - :cantidad WHERE id = :id AND stock >= :cantidad";
      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(":cantidad", $cantidad);
      $stmt->bindParam(":id", $this->id);
      return $stmt->execute();
   }
}
