<?php require_once '../app/views/layout/header.php'; ?>
<main class="container py-5 text-center" style="min-height: 60vh; display: flex; flex-direction: column; justify-content: center; align-items: center;">
    <h1 class="display-1 fw-bold text-primary mb-3">404</h1>
    <h3 class="fw-bold text-dark mb-4">Página no encontrada</h3>
    <p class="text-muted fs-5 mb-5 max-w-500 mx-auto">Lo sentimos, la página que estás buscando no existe, ha sido eliminada o cambió de nombre.</p>
    <a href="<?php echo URLROOT; ?>/" class="btn btn-primary btn-lg fw-bold px-5 rounded-pill"><i class="fas fa-home me-2"></i> Volver al Inicio</a>
</main>
<?php require_once '../app/views/layout/footer.php'; ?>