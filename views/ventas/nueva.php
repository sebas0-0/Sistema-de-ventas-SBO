<?php include dirname(__DIR__) . '/layout/header.php'; ?>
<h2 class="mb-4" style="letter-spacing: 2px; font-weight: bold;">REGISTRAR VENTA</h2>

<?php if (isset($_SESSION['error'])): ?>
   <div class="alert alert-danger">
      <?= $_SESSION['error']; ?>
      <?php unset($_SESSION['error']); ?>
   </div>
<?php endif; ?>

<div class="row g-4">
   <!-- Lado Izquierdo: Selector de Productos -->
   <div class="col-md-5">
      <div class="card shadow-sm" style="border: 2px solid #000;">
         <div class="card-body p-4">
            <h4 class="mb-4" style="letter-spacing: 1px;">SELECCIÓN</h4>
            <div class="mb-3">
               <label class="form-label fw-bold">PRENDA:</label>
               <select id="select_producto" class="form-select rounded-0">
                  <?php foreach ($productos as $p): ?>
                     <?php if ($p['stock'] > 0): ?>
                        <option value="<?= $p['id'] ?>" data-precio="<?= $p['precio'] ?>" data-nombre="<?= $p['nombre'] ?>" data-stock="<?= $p['stock'] ?>">
                           <?= strtoupper($p['nombre']) ?> ($<?= $p['precio'] ?>)
                        </option>
                     <?php endif; ?>
                  <?php endforeach; ?>
               </select>
            </div>
            <div class="mb-3">
               <label class="form-label fw-bold">CANTIDAD:</label>
               <input type="number" id="input_cantidad" class="form-control rounded-0" min="1" value="1" onkeydown="return event.keyCode !== 69">
            </div>
            <button type="button" onclick="agregarAlCarrito()" class="btn btn-custom w-100 py-2">AGREGAR A LA LISTA</button>
         </div>
      </div>
   </div>

   <!-- Lado Derecho: Detalle de la Venta -->
   <div class="col-md-7">
      <form action="index.php?c=Venta&a=registrar" method="POST">
         <div class="card shadow-sm" style="border: 1px solid #dee2e6;">
            <div class="card-body p-4">
               <h4 class="mb-4" style="letter-spacing: 1px;">DETALLE DE COMPRA</h4>
               <table class="table align-middle" id="tabla_carrito">
                  <thead>
                     <tr class="text-muted" style="font-size: 0.8rem;">
                        <th>PRENDA</th>
                        <th>PRECIO</th>
                        <th>CANT.</th>
                        <th>SUBTOTAL</th>
                        <th></th>
                     </tr>
                  </thead>
                  <tbody><!-- Dinámico con JS --></tbody>
               </table>
               <hr class="my-4">
               <div class="d-flex justify-content-between align-items-center">
                  <span class="text-muted">TOTAL (IVA 16% INC.)</span>
                  <h3 class="mb-0" style="font-weight: bold;"><span id="total_venta">$0.00</span></h3>
                  <input type="hidden" name="total_final" id="input_total_final">
               </div>
               <button type="submit" id="btn_finalizar" class="btn btn-custom w-100 mt-4 py-3" disabled>FINALIZAR VENTA</button>
            </div>
         </div>
      </form>
   </div>
</div>

<p class="text-muted">Stock disponible: <span id="stock_disponible">0</span></p>

<!-- Script para manejar el carrito de compras y la interacción del formulario -->
<script>
   // Carrito de compras en memoria
   let carrito = [];
   <!-- Actualizar stock disponible al cambiar de producto -->
   document.getElementById('select_producto').addEventListener('change', function() {
      const selectedOption = this.options[this.selectedIndex];
      const stockDisponible = selectedOption ? selectedOption.getAttribute('data-stock') : 0;
      document.getElementById('stock_disponible').textContent = stockDisponible;
   });
   // Inicializar stock disponible al cargar la página
   function agregarAlCarrito() {
      const select = document.getElementById('select_producto');
      const option = select.options[select.selectedIndex];
      if (!option) return;
      // Obtener datos del producto seleccionado
      const id = select.value;
      const nombre = option.getAttribute('data-nombre');
      const precio = parseFloat(option.getAttribute('data-precio'));
      const stock = parseInt(option.getAttribute('data-stock'));
      const cantidad = parseInt(document.getElementById('input_cantidad').value);
      // Validar cantidad
      if (cantidad > stock) {
         alert("STOCK INSUFICIENTE. DISPONIBLE: " + stock);
         return;
      }
      // Verificar si el producto ya está en el carrito
      const existe = carrito.find(item => item.id === id);
      if (existe) {
         if ((existe.cantidad + cantidad) > stock) {
            alert("NO PUEDE SUPERAR EL STOCK DISPONIBLE");
            return;
         }
         existe.cantidad += cantidad;
      } else {
         carrito.push({
            id,
            nombre,
            precio,
            cantidad
         });
      }

      renderizarCarrito();
   }
   // Función para renderizar el carrito en la tabla
   function renderizarCarrito() {
      const tbody = document.querySelector('#tabla_carrito tbody');
      tbody.innerHTML = '';
      let total = 0;
      // Se recorre el carrito para mostrar cada producto agregado y calcular el total
      carrito.forEach((item, index) => {
         let subtotal = item.precio * item.cantidad;
         total += subtotal;
         tbody.innerHTML += `
            <tr class="border-bottom">
               <td class="fw-bold">${item.nombre.toUpperCase()} <input type="hidden" name="prod_ids[]" value="${item.id}"></td>
               <td>$${item.precio.toFixed(2)}</td>
               <td>${item.cantidad} <input type="hidden" name="prod_cants[]" value="${item.cantidad}"></td>
               <td class="fw-bold">$${subtotal.toFixed(2)}</td>
               <td class="text-end"><button type="button" class="btn btn-sm text-danger border-0" onclick="eliminarDelCarrito(${index})">✕</button></td>
            </tr>`;
      });
      // Se calcula el total con IVA incluido y se actualiza el DOM
      let totalConIva = total * 1.16;
      document.getElementById('total_venta').innerText = '$' + totalConIva.toFixed(2);
      document.getElementById('input_total_final').value = totalConIva.toFixed(2);
      document.getElementById('btn_finalizar').disabled = carrito.length === 0;
   }
   // Función para eliminar un producto del carrito
   function eliminarDelCarrito(index) {
      carrito.splice(index, 1);
      renderizarCarrito();
   }
</script>
<?php include dirname(__DIR__) . '/layout/footer.php'; ?>