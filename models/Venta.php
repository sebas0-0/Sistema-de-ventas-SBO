<?php

/**
 * ARCHIVO: models/Venta.php
 * FUNCIÓN: Gestionar el registro de ventas y cálculos
 */

class Venta
{
   private $conn;
   private $table_name = "ventas";

   public $id;
   public $producto_id;
   public $cantidad;
   public $total_iva;
   public $fecha_venta;

   public function __construct($db)
   {
      $this->conn = $db;
   }

   // Método para registrar una venta con cálculo de IVA
   public function registrarVenta($precio_unitario)
   {
      // Lógica de negocio: Cálculo automático del total con IVA (16%)
      $subtotal = $precio_unitario * $this->cantidad;
      $iva = $subtotal * 0.16;
      $this->total_iva = $subtotal + $iva;

      $query = "INSERT INTO " . $this->table_name . " 
                  SET producto_id=:producto_id, cantidad=:cantidad, total_iva=:total_iva";

      $stmt = $this->conn->prepare($query);

      $stmt->bindParam(":producto_id", $this->producto_id);
      $stmt->bindParam(":cantidad", $this->cantidad);
      $stmt->bindParam(":total_iva", $this->total_iva);

      if ($stmt->execute()) {
         return true;
      }
      return false;
   }

   // Método para ver el historial de ventas
   public function obtenerHistorial()
   {
      $query = "SELECT v.*, p.nombre as producto_nombre 
                  FROM " . $this->table_name . " v
                  JOIN productos p ON v.producto_id = p.id
                  ORDER BY v.fecha_venta DESC";
      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      return $stmt;
   }
}
