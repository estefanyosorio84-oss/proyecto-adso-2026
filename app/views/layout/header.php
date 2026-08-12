<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['title'] ?? SITENAME; ?></title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/style.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand d-flex align-items-center gap-2" href="<?php echo URLROOT; ?>/">
                <div class="bg-primary text-white rounded p-2 lh-1"><i class="fas fa-book-open"></i></div>
                <span>Papeleria todo arte</span>
            </a>
            <!-- Botón Menú Móvil -->
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainMenu">
                <!-- Buscador AJAX Header -->
                <div class="search-container mx-auto my-3 my-lg-0 px-lg-4">
                    <form action="<?php echo URLROOT; ?>/product/store" method="GET" class="d-flex w-100 m-0">
                        <div class="input-group">
                            <input type="text" name="q" id="ajax-search-input" class="form-control rounded-start-pill bg-light border-0 py-2" placeholder="Buscar cuadernos, lápices y más..." autocomplete="off">
                            <button class="btn btn-primary rounded-end-pill px-4" type="submit"><i class="fas fa-search"></i></button>
                        </div>
                    </form>
                    <div id="search-results" class="search-dropdown hidden border"></div>
                </div>
                <!-- Menú Derecho -->
                <ul class="navbar-nav ms-auto align-items-center gap-3">
                    <li class="nav-item">
                        <a class="nav-link text-warning fw-bold" href="<?php echo URLROOT; ?>/product/ofertas"><i class="fas fa-tag"></i> Ofertas</a>
                    </li>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php if (in_array($_SESSION['role'], ['Administrador', 'Vendedor'])): ?>
                            <li class="nav-item"><a class="nav-link" href="<?php echo URLROOT; ?>/admin"><i class="fas fa-cogs"></i> Panel</a></li>
                        <?php endif; ?>
                        <li class="nav-item"><a class="nav-link text-danger" href="<?php echo URLROOT; ?>/auth/logout"><i class="fas fa-sign-out-alt"></i> Salir</a></li>
                    <?php else: ?>
                        <li class="nav-item d-none d-lg-block">
                            <a href="<?php echo URLROOT; ?>/auth/register" class="text-muted small text-decoration-none fw-semibold">Crea tu cuenta</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary px-4 fw-bold" href="<?php echo URLROOT; ?>/auth/login">Ingresar</a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item ms-2">
                        <a class="nav-link position-relative fs-5 text-dark" href="<?php echo URLROOT; ?>/cart">
                            <i class="fas fa-shopping-cart"></i>
                            <span id="cart-counter" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">0</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Submenú Desktop -->
    <div class="bg-white border-bottom d-none d-lg-block shadow-sm">
        <div class="container">
            <ul class="nav py-2 gap-4" style="font-size: 0.95rem;">
                <li class="nav-item"><a href="<?php echo URLROOT; ?>/product/store" class="nav-link px-0 text-primary fw-bold"><i class="fas fa-bars"></i> Todas las Categorías</a></li>
                <li class="nav-item"><a href="<?php echo URLROOT; ?>/product/store?cat=cuadernos" class="nav-link px-0">Cuadernos</a></li>
                <li class="nav-item"><a href="<?php echo URLROOT; ?>/product/store?cat=escritura" class="nav-link px-0">Escritura</a></li>
                <li class="nav-item"><a href="<?php echo URLROOT; ?>/product/store?cat=arte-y-dibujo" class="nav-link px-0">Arte y Dibujo</a></li>
                <li class="nav-item"><a href="<?php echo URLROOT; ?>/product/store?cat=papel-y-resmas" class="nav-link px-0">Papel y Resmas</a></li>
                <li class="nav-item ms-auto"><a href="<?php echo URLROOT; ?>/ayuda" class="nav-link px-0 text-muted"><i class="fas fa-question-circle"></i> Ayuda y Contacto</a></li>
            </ul>
        </div>
    </div>