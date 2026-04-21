<?php include dirname(__DIR__) . '/layout/header.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-4">
   <h2 style="letter-spacing: 2px; font-weight: bold;">CATÁLOGO DE ROPA</h2>
   <a href="index.php?c=Producto&a=nuevo" class="btn btn-custom">AÑADIR PRENDA</a>
</div>

<div class="table-responsive">
   <table class="table table-hover bg-white border">
      <thead class="table-dark">
         <tr class="text-center">
            <th>ID</th>
            <th>NOMBRE</th>
            <th>PRECIO</th>
            <th>STOCK</th>
            <th>ESTADO</th>
            <th>ACCIONES</th>
         </tr>
      </thead>
      <tbody>
         <?php foreach ($productos as $p): ?>
            <tr class="text-center align-middle">
               <td>#<?= $p['id'] ?></td>
               <td class="fw-bold"><?= strtoupper($p['nombre']) ?></td>
               <td>$<?= number_format($p['precio'], 2) ?></td>
               <td><?= $p['stock'] ?></td>
               <td>
                  <?php if ($p['stock'] <= 0): ?>
                     <span class="badge bg-danger rounded-0">AGOTADO</span>
                  <?php else: ?>
                     <span class="badge bg-dark rounded-0">DISPONIBLE</span>
                  <?php endif; ?>
               </td>
               <td>
                  <div class="btn-group">
                     <a href="index.php?c=Producto&a=editar&id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-dark rounded-0">EDITAR</a>
                     <a href="index.php?c=Producto&a=eliminar&id=<?= $p['id'] ?>" class="btn btn-sm btn-dark rounded-0" onclick="return confirm('¿Eliminar esta prenda?')">ELIMINAR</a>
                  </div>
               </td>
            </tr>
         <?php endforeach; ?>
      </tbody>
   </table>
</div>
<?php include dirname(__DIR__) . '/layout/footer.php'; ?>