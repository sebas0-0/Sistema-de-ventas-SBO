<?php include dirname(__DIR__) . '/layout/header.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-4">
   <h2 style="letter-spacing: 2px; font-weight: bold;">HISTORIAL DE TRANSACCIONES</h2>
   <a href="index.php?c=Venta&a=nueva" class="btn btn-custom">NUEVA VENTA</a>
</div>

<div class="table-responsive">
   <table class="table table-hover bg-white border">
      <thead class="table-dark">
         <tr class="text-center">
            <th>FOLIO</th>
            <th>FECHA Y HORA</th>
            <th>TOTAL (IVA INC.)</th>
            <th>ACCIONES</th>
         </tr>
      </thead>
      <tbody>
         <?php foreach ($ventas as $v): ?>
            <tr class="text-center align-middle">
               <td class="fw-bold">#<?= $v['id'] ?></td>
               <td><?= $v['fecha_venta'] ?></td>
               <td class="fs-5"><strong>$<?= number_format($v['total_iva'], 2) ?></strong></td>
               <td>
                  <a href="index.php?c=Venta&a=eliminar&id=<?= $v['id'] ?>"
                     class="btn btn-sm btn-outline-dark rounded-0"
                     onclick="return confirm('¿ESTÁ SEGURO DE ANULAR ESTA VENTA? EL STOCK SERÁ REINTEGRADO.')">
                     ANULAR VENTA
                  </a>
               </td>
            </tr>
            <tr>
               <td colspan="4">
                  <ul>
                     <?php foreach ($v['detalles'] as $detalle): ?>
                        <li><?= $detalle['nombre'] ?> - Cantidad: <?= $detalle['cantidad'] ?> - Precio Unitario: $<?= number_format($detalle['precio_unitario'], 2) ?></li>
                     <?php endforeach; ?>
                  </ul>
               </td>
            </tr>
         <?php endforeach; ?>
         <?php if (empty($ventas)): ?>
            <tr>
               <td colspan="4" class="text-center py-4 text-muted">NO SE HAN REGISTRADO VENTAS TODAVÍA.</td>
            </tr>
         <?php endif; ?>
      </tbody>
   </table>
</div>
<?php include dirname(__DIR__) . '/layout/footer.php'; ?>