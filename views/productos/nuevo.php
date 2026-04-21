<?php include dirname(__DIR__) . '/layout/header.php'; ?>
<div class="card shadow-sm mx-auto" style="max-width: 500px; border: 2px solid #000;">
   <div class="card-body p-4">
      <h3 class="text-center mb-4" style="letter-spacing: 2px; font-weight: bold;">NUEVO PRODUCTO</h3>
      <form action="index.php?c=Producto&a=guardar" method="POST">
         <div class="mb-3">
            <label class="form-label fw-bold">NOMBRE DE LA PRENDA:</label>
            <input type="text" name="nombre" class="form-control rounded-0" placeholder="Ej. Camisa de Lino" required>
         </div>
         <div class="mb-3">
            <label class="form-label fw-bold">DESCRIPCIÓN:</label>
            <textarea name="descripcion" class="form-control rounded-0" rows="3"></textarea>
         </div>
         <div class="mb-3">
            <label class="form-label fw-bold">PRECIO UNITARIO:</label>
            <input type="number" step="0.01" name="precio" class="form-control rounded-0" onkeydown="return event.keyCode !== 69" required>
         </div>
         <div class="mb-3">
            <label class="form-label fw-bold">STOCK INICIAL:</label>
            <input type="number" name="stock" class="form-control rounded-0" onkeydown="return event.keyCode !== 69" required>
         </div>
         <button type="submit" class="btn btn-custom w-100 py-2">GUARDAR PRODUCTO</button>
         <a href="index.php?c=Producto&a=index" class="btn btn-link w-100 mt-2 text-dark text-decoration-none">CANCELAR</a>
      </form>
   </div>
</div>
<?php include dirname(__DIR__) . '/layout/footer.php'; ?>