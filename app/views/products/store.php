<main class="container py-5">
    <?php if (!empty($data['current_offer_data'])): ?>
        <div class="mb-5 offer-banner shadow-sm" style="background-image: url('<?php echo filter_var($data['current_offer_data']->banner_image, FILTER_VALIDATE_URL) ? $data['current_offer_data']->banner_image : URLROOT . '/public' . $data['current_offer_data']->banner_image; ?>');">
            <div class="offer-banner-content">
                <h2 class="fw-bold display-6 mb-3 text-white"><?php echo $data['current_offer_data']->title; ?></h2>
                <p class="fs-5 text-light opacity-75 mb-0"><?php echo $data['current_offer_data']->short_desc; ?></p>
            </div>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Sidebar Filtros -->
        <div class="col-lg-3 d-none d-lg-block">
            <div class="bg-white p-4 rounded-4 border shadow-sm sticky-top" style="top: 100px;">
                <h5 class="fw-bold mb-4">Categorías</h5>
                <ul class="list-unstyled d-flex flex-column gap-3 mb-4">
                    <li><a href="<?php echo URLROOT; ?>/product/store" class="text-decoration-none <?php echo empty($data['current_cat']) ? 'fw-bold text-primary' : 'text-muted fw-medium'; ?>">Todas las categorías</a></li>
                    <?php foreach ($data['categories'] as $c): ?>
                        <li>
                            <a href="<?php echo URLROOT; ?>/product/store?cat=<?php echo $c->slug; ?>" class="text-decoration-none <?php echo ($data['current_cat'] == $c->slug) ? 'fw-bold text-primary' : 'text-muted fw-medium'; ?> transition-all hover-primary">
                                <?php echo $c->icon . ' ' . $c->name; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <!-- Grid de Productos -->
        <div class="col-lg-9">
            <div class="bg-white p-3 rounded-4 shadow-sm border mb-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div class="flex-grow-1" style="max-width: 400px;">
                    <input type="text" id="store-ajax-search" class="form-control bg-light border-0 py-2" placeholder="Filtrar resultados en tiempo real..." value="<?php echo $_GET['q'] ?? ''; ?>">
                </div>
                <form action="<?php echo URLROOT; ?>/product/store" method="GET" class="d-flex align-items-center gap-2 m-0">
                    <?php if (!empty($_GET['cat'])): ?><input type="hidden" name="cat" value="<?php echo $_GET['cat']; ?>"><?php endif; ?>
                    <?php if (!empty($_GET['offer'])): ?><input type="hidden" name="offer" value="<?php echo $_GET['offer']; ?>"><?php endif; ?>
                    <label class="text-muted fw-bold d-none d-md-block text-nowrap">Ordenar por:</label>
                    <select name="sort" class="form-select bg-light border-0 fw-medium py-2" onchange="this.form.submit()">
                        <option value="populares" <?php echo $data['current_sort'] == 'populares' ? 'selected' : ''; ?>>Más Populares</option>
                        <option value="precio_menor" <?php echo $data['current_sort'] == 'precio_menor' ? 'selected' : ''; ?>>Menor Precio</option>
                        <option value="precio_mayor" <?php echo $data['current_sort'] == 'precio_mayor' ? 'selected' : ''; ?>>Mayor Precio</option>
                        <option value="az" <?php echo $data['current_sort'] == 'az' ? 'selected' : ''; ?>>A - Z</option>
                    </select>
                </form>
            </div>

            <div id="store-product-grid">
                <?php if (empty($data['products'])): ?>
                    <div class="alert alert-light text-center py-5 text-muted border rounded-4 d-flex flex-column align-items-center justify-content-center" style="min-height: 300px;">
                        <i class="fas fa-box-open fa-3x mb-3 text-secondary"></i>
                        <h5 class="fw-bold">No se encontraron productos</h5>
                        <p>Intente con otra categoría o término de búsqueda.</p>
                    </div>
                <?php else: ?>
                    <div class="row g-4" id="store-items-container">
                        <?php foreach ($data['products'] as $index => $p): ?>
                            <?php
                            $price = $p->price;
                            $discount = $p->discount_percent;
                            $final_price = $price - ($price * ($discount / 100));
                            $imgSrc = filter_var($p->main_image, FILTER_VALIDATE_URL) ? $p->main_image : URLROOT . '/public' . $p->main_image;
                            ?>
                            <!-- Ajustado a col-lg-4 para mostrar exactamente 3 tarjetas por fila -->
                            <div class="col-6 col-md-6 col-lg-4 store-product-item" style="<?php echo $index >= 9 ? 'display: none;' : ''; ?>">
                                <div class="product-card p-3 shadow-sm border-0 bg-white h-100 d-flex flex-column">
                                    <?php if ($discount > 0): ?><span class="badge-discount"><?php echo number_format($discount, 0); ?>% OFF</span><?php endif; ?>

                                    <a href="<?php echo URLROOT; ?>/product/detail/<?php echo $p->id; ?>" class="product-image-container mb-3 bg-light">
                                        <img src="<?php echo $imgSrc; ?>" alt="<?php echo $p->name; ?>">
                                    </a>

                                    <!-- Contenedor que empuja los botones hacia abajo sin crear espacios gigantes -->
                                    <div class="d-flex flex-column flex-grow-1">
                                        <a href="<?php echo URLROOT; ?>/product/detail/<?php echo $p->id; ?>" class="product-title mb-1 fs-6 lh-sm"><?php echo $p->name; ?></a>

                                        <?php if ($p->free_shipping): ?>
                                            <div><span class="badge-shipping"><i class="fas fa-truck"></i> Envío gratis</span></div>
                                        <?php endif; ?>

                                        <div class="d-flex flex-column mb-2 mt-1">
                                            <?php if ($discount > 0): ?>
                                                <span class="product-old-price">$<?php echo number_format($price, 0, ',', '.'); ?></span>
                                            <?php endif; ?>
                                            <span class="product-price text-primary">$<?php echo number_format($final_price, 0, ',', '.'); ?></span>
                                        </div>

                                        <div class="text-muted small mb-3 fw-medium">
                                            <?php if ($p->stock > 0): ?>
                                                <i class="fas fa-check-circle text-success"></i> <?php echo $p->stock; ?> en stock
                                            <?php else: ?>
                                                <i class="fas fa-times-circle text-danger"></i> Agotado
                                            <?php endif; ?>
                                        </div>

                                        <!-- mt-auto ancla el botón siempre abajo sin deformar arriba -->
                                        <div class="mt-auto d-grid gap-2">
                                            <button class="btn btn-primary btn-sm fw-bold add-to-cart-btn py-2" <?php echo ($p->stock <= 0) ? 'disabled' : ''; ?> data-id="<?php echo $p->id; ?>" data-name="<?php echo htmlspecialchars($p->name, ENT_QUOTES); ?>" data-price="<?php echo $final_price; ?>" data-sku="<?php echo $p->sku; ?>">
                                                <i class="fas fa-shopping-cart"></i> Agregar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <?php if (count($data['products']) > 9): ?>
                        <div class="text-center mt-5">
                            <button id="btn-load-more" class="btn btn-outline-primary btn-lg fw-bold rounded-pill px-5">Ver Más Productos <i class="fas fa-chevron-down ms-2"></i></button>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnLoadMore = document.getElementById('btn-load-more');
        if (btnLoadMore) {
            btnLoadMore.addEventListener('click', function() {
                const hiddenItems = document.querySelectorAll('.store-product-item[style*="display: none"]');
                let shown = 0;
                for (let i = 0; i < hiddenItems.length; i++) {
                    // Mostrar en lotes de 9 (3 filas completas)
                    if (shown < 9) {
                        hiddenItems[i].style.display = 'block';
                        shown++;
                    } else break;
                }
                if (document.querySelectorAll('.store-product-item[style*="display: none"]').length === 0) {
                    btnLoadMore.style.display = 'none';
                }
            });
        }
    });
</script>