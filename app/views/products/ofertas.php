<?php require_once '../app/views/layout/header.php'; ?>
<main class="container py-5">
    <div class="text-center mb-5">
        <h1 class="fw-bold text-primary display-5">Ofertas Especiales</h1>
        <p class="text-muted fs-5">Descubre descuentos exclusivos por tiempo limitado.</p>
    </div>
    <?php if (empty($data['offers'])): ?>
        <div class="alert alert-light text-center py-5 border rounded-4">
            <i class="fas fa-tags fa-3x text-muted mb-3"></i>
            <h4 class="fw-bold text-dark">Actualmente no hay campañas activas</h4>
            <p class="text-muted">Vuelve pronto para ver nuestras promociones.</p>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($data['offers'] as $offer): ?>
                <div class="col-lg-6">
                    <?php $bannerUrl = filter_var($offer->banner_image, FILTER_VALIDATE_URL) ? $offer->banner_image : URLROOT . '/public' . $offer->banner_image; ?>
                    <a href="<?php echo URLROOT; ?>/product/store?offer=<?php echo $offer->slug; ?>" class="text-decoration-none">
                        <div class="offer-banner shadow-sm transition-all hover-shadow" style="background-image: url('<?php echo $bannerUrl; ?>');">
                            <div class="offer-banner-content">
                                <span class="badge bg-warning text-dark fs-6 mb-3 px-3 py-2 rounded-pill fw-bold"> ¡<?php echo number_format($offer->discount_percent, 0); ?>% DE DESCUENTO!</span>
                                <h2 class="fw-bold display-6 mb-3 text-white"><?php echo $offer->title; ?></h2>
                                <p class="fs-5 text-light opacity-75 mb-0"><?php echo $offer->short_desc; ?></p>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>
<?php require_once '../app/views/layout/footer.php'; ?>