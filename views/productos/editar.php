<?php include dirname(__DIR__) . '/layout/header.php'; ?>
<!-- vista para editar un producto existente, con un formulario prellenado con los datos actuales del producto -->
<div class="card shadow-sm mx-auto" style="max-width: 500px; border: 2px solid #000;">
   <div class="card-body p-4">
      <h3 class="text-center mb-4" style="letter-spacing: 2px; font-weight: bold;">EDITAR PRENDA</h3>

      <form action="index.php?c=Producto&a=actualizar" method="POST">
         <!-- Campo oculto para el ID -->
         <input type="hidden" name="id" value="<?= $this->producto->id ?>">

         <div class="mb-3">
            <label class="form-label fw-bold">NOMBRE DE LA PRENDA:</label>
            <input type="text" name="nombre" class="form-control rounded-0" value="<?= $this->producto->nombre ?>" required>
         </div>

         <div class="mb-3">
            <label class="form-label fw-bold">DESCRIPCIÓN:</label>
            <textarea name="descripcion" class="form-control rounded-0" rows="3"><?= $this->producto->descripcion ?></textarea>
         </div>

         <div class="mb-3">
            <label class="form-label fw-bold">PRECIO UNITARIO:</label>
            <input type="number" step="0.01" name="precio" class="form-control rounded-0" value="<?= $this->producto->precio ?>" onkeydown="return event.keyCode !== 69" required>
         </div>

         <div class="mb-3">
            <label class="form-label fw-bold">STOCK ACTUAL (REPONER):</label>
            <input type="number" name="stock" class="form-control rounded-0" value="<?= $this->producto->stock ?>" onkeydown="return event.keyCode !== 69" required>
            <small class="text-muted">Modifica este valor para actualizar el inventario disponible.</small>
         </div>

         <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-custom w-100 py-2">ACTUALIZAR</button>
            <a href="index.php?c=Producto&a=index" class="btn btn-outline-dark w-100 py-2 rounded-0">CANCELAR</a>
         </div>
      </form>
   </div>
</div>
<?php include dirname(__DIR__) . '/layout/footer.php'; ?>