<main class="container py-5">

    <!-- Alerta Global Informativa -->
    <div class="alert alert-info border-0 rounded-4 shadow-sm mb-5 d-flex align-items-center gap-3">
        <i class="fas fa-store fs-3 text-info"></i>
        <div>
            <h5 class="fw-bold mb-1">El pago no se realiza en línea</h5>
            <p class="mb-0">Tu pedido queda reservado con un código. Acércate a nuestro punto físico para confirmarlo y pagarlo.</p>
        </div>
    </div>

    <!-- Banner Principal con Carrusel (Swiper) que trae las Ofertas del Panel -->
    <div class="swiper promoSwiper mb-5 rounded-4 shadow-sm overflow-hidden">
        <div class="swiper-wrapper">
            <?php if (!empty($data['offers'])): foreach ($data['offers'] as $off): ?>
                    <?php $bannerSrc = filter_var($off->banner_image, FILTER_VALIDATE_URL) ? $off->banner_image : URLROOT . '/public' . $off->banner_image; ?>
                    <div class="swiper-slide">
                        <div class="promo-banner-home" style="background: linear-gradient(135deg, rgba(26,115,232,0.85), rgba(79,172,254,0.85)), url('<?php echo $bannerSrc; ?>') center/cover;">
                            <div class="row align-items-center w-100">
                                <div class="col-md-8 position-relative z-1">
                                    <h2 class="mb-3 text-white"><?php echo $off->title; ?></h2>
                                    <p class="fs-5 mb-4 opacity-75 text-white"><?php echo $off->short_desc; ?></p>
                                    <a href="<?php echo URLROOT; ?>/product/store?offer=<?php echo $off->slug; ?>" class="btn btn-light btn-lg fw-bold text-primary rounded-pill px-5">Ver Oferta</a>
                                </div>
                                <div class="col-md-4 text-end d-none d-md-block position-relative z-1">
                                    <i class="fas fa-tags fa-10x text-white opacity-25"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach;
            else: ?>
                <div class="swiper-slide">
                    <div class="promo-banner-home">
                        <div class="row align-items-center w-100">
                            <div class="col-md-8 position-relative z-1">
                                <h2 class="mb-3">¡Hasta 30% OFF en Regreso a Clases!</h2>
                                <p class="fs-5 mb-4 opacity-75 text-white">Organiza tu oficina y colegio con precios especiales.</p>
                                <a href="<?php echo URLROOT; ?>/product/ofertas" class="btn btn-light btn-lg fw-bold text-primary rounded-pill px-5">Ver Ofertas</a>
                            </div>
                            <div class="col-md-4 text-end d-none d-md-block position-relative z-1">
                                <i class="fas fa-school fa-10x opacity-25"></i>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <div class="swiper-pagination"></div>
    </div>

    <!-- Explora por Categorías Swiper -->
    <section class="mb-5">
        <h3 class="fw-bold mb-4">Explora por categoría</h3>
        <div class="swiper categorySwiper">
            <div class="swiper-wrapper pb-2">
                <div class="swiper-slide"><a href="<?php echo URLROOT; ?>/product/store?cat=cuadernos" class="category-card">
                        <div class="category-icon">📓</div>Cuadernos
                    </a></div>
                <div class="swiper-slide"><a href="<?php echo URLROOT; ?>/product/store?cat=escritura" class="category-card">
                        <div class="category-icon">🖊️</div>Escritura
                    </a></div>
                <div class="swiper-slide"><a href="<?php echo URLROOT; ?>/product/store?cat=arte-y-dibujo" class="category-card">
                        <div class="category-icon">🎨</div>Arte y Dibujo
                    </a></div>
                <div class="swiper-slide"><a href="<?php echo URLROOT; ?>/product/store?cat=papel-y-resmas" class="category-card">
                        <div class="category-icon">📄</div>Papel y Resmas
                    </a></div>
                <div class="swiper-slide"><a href="<?php echo URLROOT; ?>/product/store?cat=morrales" class="category-card">
                        <div class="category-icon">🎒</div>Morrales
                    </a></div>
                <div class="swiper-slide"><a href="<?php echo URLROOT; ?>/product/store?cat=tecnologia" class="category-card">
                        <div class="category-icon">💻</div>Tecnología
                    </a></div>
                <div class="swiper-slide"><a href="<?php echo URLROOT; ?>/product/store" class="category-card">
                        <div class="category-icon">🗂️</div>Oficina
                    </a></div>
            </div>
        </div>
    </section>

    <!-- Ofertas del Día y Destacados -->
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <h3 class="fw-bold mb-0">Los más pedidos</h3>
            <a href="<?php echo URLROOT; ?>/product/store" class="text-decoration-none fw-semibold text-primary">Ir a la tienda <i class="fas fa-chevron-right small"></i></a>
        </div>

        <div class="row g-4">
            <?php foreach ($data['featured_products'] as $p): ?>
                <?php
                $price = $p->price;
                $discount = $p->discount_percent;
                $final_price = $price - ($price * ($discount / 100));
                $imgSrc = filter_var($p->main_image, FILTER_VALIDATE_URL) ? $p->main_image : URLROOT . '/public' . $p->main_image;
                ?>
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="product-card p-3 shadow-sm border-0 bg-white h-100 d-flex flex-column">

                        <?php if ($discount > 0): ?>
                            <span class="badge-discount"><?php echo number_format($discount, 0); ?>% OFF</span>
                        <?php endif; ?>

                        <a href="<?php echo URLROOT; ?>/product/detail/<?php echo $p->id; ?>" class="product-image-container mb-3 bg-light">
                            <img src="<?php echo $imgSrc; ?>" alt="<?php echo $p->name; ?>">
                        </a>

                        <div class="d-flex flex-column flex-grow-1">
                            <a href="<?php echo URLROOT; ?>/product/detail/<?php echo $p->id; ?>" class="product-title mb-1 fs-6 lh-sm"><?php echo $p->name; ?></a>

                            <?php if ($p->free_shipping): ?>
                                <div><span class="badge-shipping"><i class="fas fa-truck me-1"></i> Envío gratis</span></div>
                            <?php endif; ?>

                            <div class="d-flex flex-column mb-2 mt-1">
                                <?php if ($discount > 0): ?>
                                    <span class="product-old-price">$<?php echo number_format($price, 0, ',', '.'); ?></span>
                                <?php endif; ?>
                                <span class="product-price text-primary">$<?php echo number_format($final_price, 0, ',', '.'); ?></span>
                            </div>

                            <div class="text-muted small mb-3 fw-medium">
                                <?php if ($p->stock > 0): ?>
                                    <i class="fas fa-check-circle text-success"></i> <?php echo $p->stock; ?> disponibles
                                <?php else: ?>
                                    <i class="fas fa-times-circle text-danger"></i> Agotado
                                <?php endif; ?>
                            </div>

                            <!-- Contenedor anclado abajo para los botones -->
                            <div class="mt-auto d-grid gap-2">
                                <button class="btn btn-primary btn-sm fw-bold add-to-cart-btn py-2"
                                    <?php echo ($p->stock <= 0) ? 'disabled' : ''; ?>
                                    data-id="<?php echo $p->id; ?>"
                                    data-name="<?php echo htmlspecialchars($p->name, ENT_QUOTES); ?>"
                                    data-price="<?php echo $final_price; ?>"
                                    data-sku="<?php echo $p->sku; ?>">
                                    <i class="fas fa-cart-plus"></i> Agregar
                                </button>
                                <a href="https://api.whatsapp.com/send?phone=573000000000&text=Hola,%20me%20interesa%20este%20producto:%20<?php echo urlencode($p->name); ?>" target="_blank" class="btn btn-outline-success btn-sm fw-bold">
                                    <i class="fab fa-whatsapp"></i> Consultar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var catSwiper = new Swiper('.categorySwiper', {
            slidesPerView: 2.2,
            spaceBetween: 15,
            freeMode: true,
            grabCursor: true,
            breakpoints: {
                576: {
                    slidesPerView: 3.5
                },
                768: {
                    slidesPerView: 5.5
                },
                1024: {
                    slidesPerView: 7.5
                },
            }
        });

        var promoSwiper = new Swiper('.promoSwiper', {
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true
            },
        });
    });
</script>