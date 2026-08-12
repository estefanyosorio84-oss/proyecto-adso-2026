<div class="container py-5" style="max-width: 900px;">
    <h2 class="fw-bold text-primary mb-4">Finalizar Compra</h2>

    <?php if (!isset($_SESSION['user_id'])): ?>
        <!-- ALERTA Y FORMULARIO SI NO ESTÁ REGISTRADO -->
        <div class="alert alert-warning border-0 rounded-4 shadow-sm mb-4">
            <h5 class="fw-bold"><i class="fas fa-exclamation-circle"></i> Registro Obligatorio</h5>
            <p class="mb-0">Para generar su código de pago y reservar sus productos físicos, debe iniciar sesión o crear una cuenta.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="bg-white p-4 rounded-4 shadow-sm border">
                    <h4 class="fw-bold mb-3">Iniciar Sesión</h4>
                    <form action="<?php echo URLROOT; ?>/auth/login" method="POST">
                        <input type="email" name="email" class="form-control bg-light border-0 mb-3" placeholder="Correo Electrónico" required>
                        <input type="password" name="password" class="form-control bg-light border-0 mb-3" placeholder="Contraseña" required>
                        <button type="submit" class="btn btn-primary w-100 fw-bold">Ingresar</button>
                    </form>
                </div>
            </div>
            <div class="col-md-6">
                <div class="bg-white p-4 rounded-4 shadow-sm border">
                    <h4 class="fw-bold mb-3">Registrarse</h4>
                    <form action="<?php echo URLROOT; ?>/auth/register" method="POST">
                        <div class="row g-2 mb-3">
                            <div class="col-6"><input type="text" name="first_name" class="form-control bg-light border-0" placeholder="Nombre" required></div>
                            <div class="col-6"><input type="text" name="last_name" class="form-control bg-light border-0" placeholder="Apellido" required></div>
                        </div>
                        <input type="text" name="cedula" class="form-control bg-light border-0 mb-3" placeholder="Cédula" required>
                        <input type="text" name="phone" class="form-control bg-light border-0 mb-3" placeholder="Teléfono" required>
                        <input type="email" name="email" class="form-control bg-light border-0 mb-3" placeholder="Correo" required>
                        <input type="text" name="username" class="form-control bg-light border-0 mb-3" placeholder="Usuario" required>
                        <input type="password" name="password" class="form-control bg-light border-0 mb-3" placeholder="Contraseña" required>
                        <button type="submit" class="btn btn-outline-primary w-100 fw-bold">Crear Cuenta</button>
                    </form>
                </div>
            </div>
        </div>

    <?php else: ?>
        <!-- FORMULARIO DE CHECKOUT PARA USUARIO LOGUEADO -->
        <div class="row g-4">
            <div class="col-md-7">
                <div class="bg-white p-4 rounded-4 shadow-sm border">
                    <h4 class="fw-bold mb-4">Datos de Facturación y Envío</h4>
                    <form id="checkout-form">
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-semibold">Nombre Completo</label>
                            <input type="text" class="form-control bg-light border-0" value="<?php echo $_SESSION['first_name']; ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-semibold">Dirección de Envío</label>
                            <input type="text" id="shipping_address" class="form-control bg-light border-0" placeholder="Ej: Calle 123 #45-67, Barrio, Ciudad" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-semibold">Datos de Facturación (Opcional)</label>
                            <textarea id="billing_data" class="form-control bg-light border-0" rows="2" placeholder="NIT, Razón Social, etc."></textarea>
                        </div>
                        <div class="alert alert-info border-0 mt-4 rounded">
                            <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Al generar el pedido, el sistema le entregará un código. Debe acercarse a nuestro punto físico con este código para realizar el pago y retirar los productos.
                        </div>
                        <button type="button" id="confirm-reservation-btn" class="btn btn-primary w-100 py-3 fw-bold fs-5 mt-2">Generar Código de Pedido</button>
                    </form>
                </div>
            </div>

            <div class="col-md-5">
                <div class="bg-white p-4 rounded-4 shadow-sm border h-100">
                    <h4 class="fw-bold mb-4">Resumen del Carrito</h4>
                    <div id="checkout-items" class="mb-4"></div>

                    <div class="mb-3">
                        <div class="input-group">
                            <input type="text" id="coupon_code" class="form-control bg-light border-0" placeholder="Código de Cupón">
                            <button class="btn btn-outline-secondary" id="apply-coupon-btn" type="button">Aplicar</button>
                        </div>
                        <div id="coupon-feedback" class="form-text text-success d-none"></div>
                    </div>
                    <div id="coupon-summary" class="mb-3 d-none">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Descuento:</span>
                            <strong class="text-danger">-$<span id="checkout-discount">0</span></strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Total luego de cupón:</span>
                            <strong class="text-primary">$<span id="checkout-final-total">0</span></strong>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center border-top pt-3">
                        <h5 class="fw-bold text-muted m-0">Total a Pagar:</h5>
                        <h3 class="fw-bold text-primary m-0">$<span id="checkout-total">0</span></h3>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>

</script>