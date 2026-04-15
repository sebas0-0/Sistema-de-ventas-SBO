<?php

/**
 * ARCHIVO: index.php
 * FUNCIÓN: Front Controller (Punto de entrada único)
 */

// 1. Configuración de errores (Útil durante el desarrollo)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. Requerir archivos base (Conexión y Modelos)
require_once 'config/database.php';
require_once 'models/Producto.php';
require_once 'models/Venta.php';

// 3. Lógica de Enrutamiento Simple
// Determinamos qué acción quiere realizar el usuario
$controller = isset($_GET['c']) ? $_GET['c'] : 'Producto';
$action = isset($_GET['a']) ? $_GET['a'] : 'index';

// 4. Carga del Controlador correspondiente
$controllerPath = 'controllers/' . $controller . 'Controller.php';

if (file_exists($controllerPath)) {
   require_once $controllerPath;
   $controllerName = $controller . 'Controller';
   $objController = new $controllerName();

   // Ejecutamos la acción (método) solicitada
   if (method_exists($objController, $action)) {
      $objController->$action();
   } else {
      echo "Error: La acción '$action' no existe.";
   }
} else {
   echo "Error: El controlador '$controller' no existe.";
}
