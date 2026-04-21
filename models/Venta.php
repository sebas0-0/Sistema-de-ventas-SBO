<?php
class Venta
{
   #atributos privados para la conexión y el nombre de la tabla
   private $conn;
   private $table_name = "ventas";

   private $id; // Propiedad para almacenar el ID de la venta

   #constructor que recibe la conexión a la base de datos
   public function __construct($db)
   {
      $this->conn = $db;
   }

   #métodos getter y setter para el ID de la venta
   public function setId($id)
   {
      $this->id = $id;
   }

   public function getId()
   {
      return $this->id;
   }

   #método para registrar una venta completa (cabecera + detalles)
   public function registrarVentaCompleta($productos_seleccionados, $total_con_iva)
   {
      # try-catch para manejar errores y asegurar la integridad de los datos
      try {
         // se inicia la transacción o se guarda o no se guarda
         $this->conn->beginTransaction();

         // insertar Cabecera (La venta general)
         $query_v = "INSERT INTO " . $this->table_name . " SET total_iva = :total";
         $stmt_v = $this->conn->prepare($query_v);
         $stmt_v->bindParam(":total", $total_con_iva);
         $stmt_v->execute();
         $venta_id = $this->conn->lastInsertId();

         # Validar que la cabecera se haya insertado correctamente y que se obtuvo el ID de la venta
         if (!$stmt_v->rowCount()) {
            throw new Exception('Error al registrar la cabecera de la venta.');
         }

         if (!$venta_id) {
            throw new Exception('Error al obtener el ID de la venta.');
         }

         // se inserta los detalles y se desconta el Stock de cada producto
         foreach ($productos_seleccionados as $item) {
            // Guardar en detalle_ventas
            $query_d = "INSERT INTO detalle_ventas SET venta_id=:v_id, producto_id=:p_id, cantidad=:cant, precio_unitario=:prec";
            $stmt_d = $this->conn->prepare($query_d);
            $stmt_d->execute([
               ":v_id" => $venta_id,
               ":p_id" => $item['id'],
               ":cant" => $item['cantidad'],
               ":prec" => $item['precio']
            ]);

            // Descontar Stock en la tabla productos
            $query_s = "UPDATE productos SET stock = stock - :cant WHERE id = :id";
            $stmt_s = $this->conn->prepare($query_s);
            $stmt_s->execute([":cant" => $item['cantidad'], ":id" => $item['id']]);
         }

         $this->conn->commit(); // Confirmar cambios
         return true;
      } catch (Exception $e) {
         $this->conn->rollBack(); // Cancelar todo si algo falla
         return false;
      }
   }

   #método para obtener el historial de ventas (cabeceras)
   public function obtenerHistorial()
   {
      $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC";
      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      return $stmt;
   }

   #método para obtener los detalles de una venta específica (productos, cantidades, precios)
   public function obtenerDetallesVenta($venta_id)
   {
      $query = "SELECT dv.producto_id, p.nombre, dv.cantidad, dv.precio_unitario 
                FROM detalle_ventas dv 
                INNER JOIN productos p ON dv.producto_id = p.id 
                WHERE dv.venta_id = ?";
      $stmt = $this->conn->prepare($query);
      $stmt->execute([$venta_id]);
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
   }

   #método para eliminar una venta (reintegrar stock y eliminar registros)
   public function eliminar()
   {
      try {
         $this->conn->beginTransaction();

         // se obtienen los productos y cantidades de esta venta para devolverlos al stock
         $query_d = "SELECT producto_id, cantidad FROM detalle_ventas WHERE venta_id = ?";
         $stmt_d = $this->conn->prepare($query_d);
         $stmt_d->execute([$this->id]);
         $detalles = $stmt_d->fetchAll(PDO::FETCH_ASSOC);

         // se reintegra el stock a cada producto
         foreach ($detalles as $item) {
            $query_s = "UPDATE productos SET stock = stock + :cant WHERE id = :id";
            $stmt_s = $this->conn->prepare($query_s);
            $stmt_s->execute([
               ":cant" => $item['cantidad'],
               ":id" => $item['producto_id']
            ]);
         }

         // se elimina la venta (La tabla detalle_ventas se limpia sola por el CASCADE del SQL)
         $query_v = "DELETE FROM " . $this->table_name . " WHERE id = ?";
         $stmt_v = $this->conn->prepare($query_v);
         $stmt_v->execute([$this->id]);

         $this->conn->commit();
         return true;
      } catch (Exception $e) {
         $this->conn->rollBack();
         return false;
      }
   }
}
