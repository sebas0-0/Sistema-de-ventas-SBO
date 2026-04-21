<!DOCTYPE html>
<html lang="es">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Sistema de Ventas de Ropa</title>
   <!-- Bootstrap para la estructura base -->
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

   <style>
      /* Paleta de colores: Blanco, Negro y Grises */
      body {
         background-color: #f8f9fa;
         color: #212529;
      }

      .navbar {
         background-color: #000000 !important;
         border-bottom: 2px solid #333;
      }

      .navbar-brand,
      .nav-link {
         color: #ffffff !important;
         text-transform: uppercase;
         font-weight: 600;
         letter-spacing: 1px;
      }

      .nav-link:hover {
         color: #adb5bd !important;
      }

      .btn-custom {
         background-color: #000000;
         color: #ffffff;
         border: 2px solid #000000;
         transition: all 0.3s ease;
         font-weight: bold;
         text-transform: uppercase;
         border-radius: 0;
      }

      .btn-custom:hover {
         background-color: #ffffff;
         color: #000000;
         border: 2px solid #000000;
      }

      .table-dark {
         background-color: #000000 !important;
      }

      .card {
         border-radius: 0;
         border: 1px solid #dee2e6;
      }
   </style>
</head>

<body>
   <nav class="navbar navbar-expand-lg navbar-dark mb-4">
      <div class="container">
         <a class="navbar-brand" href="index.php">Tienda de Ropa</a>
         <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
         </button>
         <div class="collapse navbar-collapse" id="navbarNav">
            <div class="navbar-nav ms-auto">
               <a class="nav-link" href="index.php?c=Producto&a=index">Productos</a>
               <a class="nav-link" href="index.php?c=Producto&a=nuevo">Nuevo Producto</a>
               <a class="nav-link" href="index.php?c=Venta&a=index">Ventas</a>
               <a class="nav-link" href="index.php?c=Venta&a=nueva">Registrar Venta</a>
            </div>
         </div>
      </div>
   </nav>
   <div class="container pb-5">