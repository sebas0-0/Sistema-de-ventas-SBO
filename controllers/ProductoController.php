<?php
class ProductoController
{
   #atributos privados para la conexión a la base de datos y el modelo de producto
   private $db;
   private $producto;

   #constructor que inicializa la conexión a la base de datos y el modelo de producto
   public function __construct()
   {
      $database = new Database();
      $this->db = $database->getConnection();
      $this->producto = new Producto($this->db);
   }

   #método para mostrar la lista de productos
   public function index()
   {
      $stmt = $this->producto->leerTodos();
      $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
      require_once 'views/productos/index.php';
   }

   #método para mostrar el formulario de creación de un nuevo producto
   public function nuevo()
   {
      require_once 'views/productos/nuevo.php';
   }

   #método para guardar un nuevo producto en la base de datos
   public function guardar()
   {
      if ($_POST) {
         $this->producto->nombre = $_POST['nombre'];
         $this->producto->descripcion = $_POST['descripcion'];
         $this->producto->precio = $_POST['precio'];
         $this->producto->stock = $_POST['stock'];
         if ($this->producto->crear()) header("Location: index.php?c=Producto&a=index");
      }
   }

   #método para mostrar el formulario de edición de un producto existente
   public function editar()
   {
      $this->producto->id = $_GET['id'];
      $this->producto->leerUno();
      require_once 'views/productos/editar.php';
   }

   #método para actualizar un producto existente en la base de datos
   public function actualizar()
   {
      if ($_POST) {
         $this->producto->id = $_POST['id'];
         $this->producto->nombre = $_POST['nombre'];
         $this->producto->descripcion = $_POST['descripcion'];
         $this->producto->precio = $_POST['precio'];
         $this->producto->stock = $_POST['stock'];
         if ($this->producto->actualizar()) header("Location: index.php?c=Producto&a=index");
      }
   }

   #método para eliminar un producto de la base de datos
   public function eliminar()
   {
      $this->producto->id = $_GET['id'];
      if ($this->producto->eliminar()) header("Location: index.php?c=Producto&a=index");
   }
}
