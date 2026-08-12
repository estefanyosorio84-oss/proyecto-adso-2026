<main class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>" class="text-decoration-none">Inicio</a></li>
            <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>/product/store" class="text-decoration-none">Tienda</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $data['product']->name; ?></li>
        </ol>
    </nav>
    <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm border mb-5">
        <div class="row g-5">
            <div class="col-md-6">
                <?php
                $p = $data['product'];
                $imgSrc = filter_var($p->main_image, FILTER_VALIDATE_URL) ? $p->main_image : URLROOT . '/public' . $p->main_image;
                $price = $p->price;
                $discount = $p->discount_percent;
                $final_price = $price - ($price * ($discount / 100));
                $gallery = json_decode($p->gallery, true) ?? [];
                ?>
                <div class="position-relative bg-light rounded-4 overflow-hidden d-flex justify-content-center align-items-center mb-3" style="height: 500px;">
                    <?php if ($discount > 0): ?>
                        <span class="position-absolute top-0 start-0 m-3 badge bg-warning text-dark fs-6 rounded-pill px-3 py-2"><?php echo number_format($discount, 0); ?>% OFF</span>
                    <?php endif; ?>
                    <img id="main-product-image" src="<?php echo $imgSrc; ?>" alt="<?php echo $p->name; ?>" class="img-fluid object-fit-cover" style="max-height: 100%; width: 100%;">
                </div>
                <!-- Galería Miniaturas -->
                <?php if (!empty($gallery)): ?>
                    <div class="d-flex gap-2 overflow-auto py-2">
                        <img src="<?php echo $imgSrc; ?>" class="gallery-thumbnail active w-25" onclick="changeMainImage(this, '<?php echo $imgSrc; ?>')">
                        <?php foreach ($gallery as $gImg): ?>
                            <?php $gSrc = filter_var($gImg, FILTER_VALIDATE_URL) ? $gImg : URLROOT . '/public' . $gImg; ?>
                            <img src="<?php echo $gSrc; ?>" class="gallery-thumbnail w-25" onclick="changeMainImage(this, '<?php echo $gSrc; ?>')">
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="col-md-6">
                <div class="mb-2 text-muted fw-semibold">SKU: <span id="display-sku"><?php echo $p->sku; ?></span> | Categoría: <span class="text-primary"><?php echo $p->category_name; ?></span></div>
                <h1 class="fw-bold text-dark mb-3"><?php echo $p->name; ?></h1>

                <!-- Estrellas Promedio Dinámico -->
                <div class="d-flex align-items-center gap-2 mb-4 text-warning">
                    <?php
                    $avg = empty($data['reviews']) ? 0 : array_sum(array_column($data['reviews'], 'rating')) / count($data['reviews']);
                    for ($i = 1; $i <= 5; $i++):
                        if ($i <= round($avg)): echo '<i class="fas fa-star"></i>';
                        else: echo '<i class="far fa-star"></i>';
                        endif;
                    endfor;
                    ?>
                    <span class="text-muted small ms-2">(<?php echo count($data['reviews']); ?> reseñas | <?php echo $p->sales_count; ?> vendidos)</span>
                </div>

                <div class="mb-4">
                    <?php if ($discount > 0): ?>
                        <h4 class="text-muted text-decoration-line-through mb-1">$<?php echo number_format($price, 0, ',', '.'); ?></h4>
                    <?php endif; ?>
                    <h2 id="display-price" class="fw-bold text-primary display-5 m-0">$<?php echo number_format($final_price, 0, ',', '.'); ?></h2>
                    <?php if ($p->free_shipping): ?>
                        <div class="mt-2 text-success fw-bold"><i class="fas fa-truck"></i> Envío gratis disponible</div>
                    <?php endif; ?>
                </div>

                <!-- SELECTOR DE VARIACIONES LÓGICO -->
                <?php if (!empty($data['variations'])): ?>
                    <div class="mb-4 p-3 bg-light rounded border">
                        <label class="fw-bold mb-2 text-dark">Opciones disponibles:</label>
                        <select id="variation-select" class="form-select border-0 shadow-sm py-2 fw-medium">
                            <option value="base" data-price="<?php echo $final_price; ?>" data-sku="<?php echo $p->sku; ?>" data-stock="<?php echo $p->stock; ?>" data-name="<?php echo htmlspecialchars($p->name); ?>">Presentación Normal - $<?php echo number_format($final_price, 0, ',', '.'); ?></option>
                            <?php foreach ($data['variations'] as $var): ?>
                                <option value="<?php echo $var->id; ?>" data-price="<?php echo $var->price; ?>" data-sku="<?php echo $var->sku; ?>" data-stock="<?php echo $var->stock; ?>" data-name="<?php echo htmlspecialchars($p->name . ' (' . $var->name . ')'); ?>">
                                    <?php echo htmlspecialchars($var->name); ?> - $<?php echo number_format($var->price, 0, ',', '.'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>

                <p class="text-secondary fs-5 mb-4"><?php echo $p->short_desc; ?></p>

                <div class="d-flex align-items-center gap-3 mb-4">
                    <label class="fw-bold mb-0">Cantidad:</label>
                    <input type="number" id="qty-input-<?php echo $p->id; ?>" class="form-control text-center fw-bold" value="1" min="1" max="<?php echo $p->stock; ?>" style="width: 80px;" <?php echo ($p->stock <= 0) ? 'disabled' : ''; ?>>
                    <span id="display-stock" class="text-muted small">(<?php echo $p->stock; ?> disponibles)</span>
                </div>
                <div class="d-grid gap-3">
                    <button class="btn btn-primary btn-lg fw-bold py-3 add-to-cart-btn"
                        <?php echo ($p->stock <= 0) ? 'disabled' : ''; ?>
                        data-id="<?php echo $p->id; ?>"
                        data-name="<?php echo htmlspecialchars($p->name); ?>"
                        data-price="<?php echo $final_price; ?>"
                        data-sku="<?php echo $p->sku; ?>">
                        <i class="fas fa-shopping-cart me-2"></i> Añadir al carrito
                    </button>

                    <a href="https://api.whatsapp.com/send?phone=573000000000&text=Hola,%20me%20interesa:%20<?php echo urlencode($p->name); ?>" target="_blank" class="btn btn-outline-success btn-lg fw-bold py-3"><i class="fab fa-whatsapp me-2"></i> Comprar vía WhatsApp</a>
                </div>
                <div class="mt-5 border-top pt-4">
                    <h5 class="fw-bold mb-3">Descripción Detallada</h5>
                    <p class="text-muted lh-lg"><?php echo nl2br($p->long_desc); ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Módulo de Reseñas -->
    <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm border mb-5">
        <h3 class="fw-bold mb-4">Reseñas de Clientes</h3>
        <div class="row g-5">
            <div class="col-md-7">
                <?php if (empty($data['reviews'])): ?>
                    <p class="text-muted">No hay reseñas para este producto aún. ¡Sé el primero en calificarlo!</p>
                <?php else: ?>
                    <?php foreach ($data['reviews'] as $rev): ?>
                        <div class="mb-4 border-bottom pb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <strong class="text-dark"><?php echo $rev->first_name . ' ' . $rev->last_name; ?></strong>
                                <small class="text-muted"><?php echo date('d/m/Y', strtotime($rev->created_at)); ?></small>
                            </div>
                            <div class="text-warning mb-2">
                                <?php for ($i = 1; $i <= 5; $i++): echo $i <= $rev->rating ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                                endfor; ?>
                            </div>
                            <p class="text-muted m-0"><?php echo htmlspecialchars($rev->comment); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="col-md-5">
                <h5 class="fw-bold mb-3">Deja tu reseña</h5>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <form action="<?php echo URLROOT; ?>/product/addReview" method="POST">
                        <input type="hidden" name="product_id" value="<?php echo $p->id; ?>">
                        <div class="mb-3">
                            <label class="form-label text-muted fw-semibold">Calificación</label>
                            <select name="rating" class="form-select bg-light border-0" required>
                                <option value="5">5 Estrellas - Excelente</option>
                                <option value="4">4 Estrellas - Muy Bueno</option>
                                <option value="3">3 Estrellas - Bueno</option>
                                <option value="2">2 Estrellas - Regular</option>
                                <option value="1">1 Estrella - Malo</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted fw-semibold">Comentario</label>
                            <textarea name="comment" class="form-control bg-light border-0" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 fw-bold">Enviar Reseña</button>
                    </form>
                <?php else: ?>
                    <div class="alert alert-warning border-0 rounded-3">Debe <a href="<?php echo URLROOT; ?>/auth/login" class="fw-bold">iniciar sesión</a> para dejar una reseña.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>
<script>
    function changeMainImage(element, src) {
        document.getElementById('main-product-image').src = src;
        document.querySelectorAll('.gallery-thumbnail').forEach(el => el.classList.remove('active'));
        element.classList.add('active');
    }

    // Lógica del Cambio de Variaciones
    const variationSelect = document.getElementById('variation-select');
    if (variationSelect) {
        variationSelect.addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            const newPrice = parseFloat(selected.dataset.price);
            const newSku = selected.dataset.sku;
            const newStock = parseInt(selected.dataset.stock);
            const newName = selected.dataset.name;

            // Formatear precio
            document.getElementById('display-price').innerText = '$' + newPrice.toLocaleString('es-CO');
            document.getElementById('display-sku').innerText = newSku;
            document.getElementById('display-stock').innerText = '(' + newStock + ' disponibles)';

            // Actualizar maximo y valor de input cantidad
            const qtyInput = document.getElementById('qty-input-<?php echo $p->id; ?>');
            qtyInput.max = newStock;
            if (qtyInput.value > newStock) qtyInput.value = newStock > 0 ? 1 : 0;

            // Actualizar boton carrito
            const btnCart = document.querySelector('.add-to-cart-btn');
            btnCart.dataset.price = newPrice;
            btnCart.dataset.sku = newSku;
            btnCart.dataset.name = newName;

            if (newStock <= 0) {
                btnCart.disabled = true;
                btnCart.innerHTML = 'Agotado Temporalmente';
            } else {
                btnCart.disabled = false;
                btnCart.innerHTML = '<i class="fas fa-shopping-cart me-2"></i> Añadir al carrito';
            }
        });
    }
</script>