<?php
#archivo principal que se encarga de enrutar las solicitudes a los controladores correspondientes

// se configuran los errores 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// los archivos base que de van a requerir para la conexión a la base de datos y los modelos 
require_once 'config/database.php';
require_once 'models/Producto.php';
require_once 'models/Venta.php';

// aqui esta la lógica de Enrutamiento Simple
// se determina la acción quiere realizar el usuario
$controller = isset($_GET['c']) ? $_GET['c'] : 'Producto';
$action = isset($_GET['a']) ? $_GET['a'] : 'index';

// se carga del Controlador correspondiente
$controllerPath = 'controllers/' . $controller . 'Controller.php';

// se verifica que el archivo del controlador exista y ejecutar la acción solicitada
if (file_exists($controllerPath)) {
   require_once $controllerPath;
   $controllerName = $controller . 'Controller';
   $objController = new $controllerName();

   // se ejecuta el método solicitado
   if (method_exists($objController, $action)) {
      $objController->$action();
      // se muestra el mensaje de éxito si se redirige desde una acción exitosa
   } else {
      echo "Error: La acción '$action' no existe.";
   }
} else {
   echo "Error: El controlador '$controller' no existe.";
}
