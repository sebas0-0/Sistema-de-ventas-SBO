<?php
class VentaController
{
   #atributos privados para la conexión a la base de datos y los modelos de venta y producto
   private $db;
   private $venta;
   private $producto;

   #constructor que inicializa la conexión a la base de datos y los modelos de venta y producto
   public function __construct()
   {
      $database = new Database();
      $this->db = $database->getConnection();
      $this->venta = new Venta($this->db);
      $this->producto = new Producto($this->db);
   }

   #método para mostrar el historial de ventas con sus detalles
   public function index()
   {
      $stmt = $this->venta->obtenerHistorial();
      $ventas = $stmt->fetchAll(PDO::FETCH_ASSOC);
      foreach ($ventas as &$venta) {
         $venta['detalles'] = $this->venta->obtenerDetallesVenta($venta['id']);
      }
      require_once 'views/ventas/index.php';
   }

   #método para mostrar el formulario de nueva venta con la lista de productos disponibles
   public function nueva()
   {
      $stmt = $this->producto->leerTodos();
      $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
      require_once 'views/ventas/nueva.php';
   }

   #método para registrar una nueva venta con sus detalles y actualizar el stock de los productos
   public function registrar()
   {
      if ($_POST && isset($_POST['prod_ids'])) {
         $ids = $_POST['prod_ids'];
         $cants = $_POST['prod_cants'];
         $total_final = $_POST['total_final'];

         // se valida que los id´s de productos sean válidos
         $ids = array_unique(array_filter($ids, 'is_numeric'));
         if (empty($ids)) {
            die('Error: No se recibieron IDs válidos de productos.');
         }

         $productos_a_vender = [];
         for ($i = 0; $i < count($ids); $i++) {
            // se busca el precio actual para guardarlo en el detalle
            $query = "SELECT precio FROM productos WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$ids[$i]]);
            $p = $stmt->fetch(PDO::FETCH_ASSOC);

            # se prepara el array de productos a vender con su id, la cantidad y precio actual
            $productos_a_vender[] = [
               'id' => $ids[$i],
               'cantidad' => $cants[$i],
               'precio' => $p['precio']
            ];
         }

         # se valida que la cantidad solicitada no exceda el stock disponible para cada producto
         foreach ($productos_a_vender as $producto) {
            $query = "SELECT stock FROM productos WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$producto['id']]);
            $stock_actual = $stmt->fetchColumn();

            # si la cantidad solicitada excede el stock disponible, se redirige al formulario de nueva venta con un mensaje de error
            if ($producto['cantidad'] > $stock_actual) {
               $_SESSION['error'] = 'La cantidad solicitada excede el stock disponible para el producto: ' . $producto['id'];
               header("Location: index.php?c=Venta&a=nueva");
               exit;
            }
         }

         # si todo es válido, se registra la venta completa y se actualiza el stock de los productos
         if ($this->venta->registrarVentaCompleta($productos_a_vender, $total_final)) {
            header("Location: index.php?c=Venta&a=index&success=ok");
         } else {
            header("Location: index.php?c=Venta&a=nueva&error=fail");
         }
      }
   }

   #método para eliminar (anular) una venta, lo que implica eliminar la cabecera y los detalles, y restaurar el stock de los productos
   public function eliminar()
   {
      if (isset($_GET['id'])) {
         $this->venta->setId($_GET['id']);
         if ($this->venta->eliminar()) {
            header("Location: index.php?c=Venta&a=index&success=anulada");
         } else {
            header("Location: index.php?c=Venta&a=index&error=error_anular");
         }
      }
   }
}
