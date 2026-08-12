<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['title']; ?></title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/style.css">
</head>

<body class="bg-light">
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 admin-sidebar p-3 d-flex flex-column">
                <a href="<?php echo URLROOT; ?>/" class="d-flex align-items-center mb-4 text-decoration-none px-2">
                    <div class="bg-primary text-white rounded p-2 lh-1 me-2"><i class="fas fa-book-open"></i></div>
                    <span class="fs-5 fw-bold text-dark">Papeleria todo arte</span>
                </a>

                <nav class="nav flex-column mb-auto">
                    <a class="admin-link <?php echo $data['view'] == 'dashboard' ? 'active' : ''; ?>" href="?view=dashboard"><i class="fas fa-chart-line me-2"></i> Resumen</a>
                    <a class="admin-link <?php echo $data['view'] == 'orders' ? 'active' : ''; ?>" href="?view=orders"><i class="fas fa-shopping-bag me-2"></i> Pedidos</a>
                    <a class="admin-link <?php echo $data['view'] == 'products' ? 'active' : ''; ?>" href="?view=products"><i class="fas fa-box me-2"></i> Productos</a>

                    <?php if ($data['role'] == 'Administrador'): ?>
                        <hr class="my-2">
                        <a class="admin-link <?php echo $data['view'] == 'variations' ? 'active' : ''; ?>" href="?view=variations"><i class="fas fa-layer-group me-2"></i> Variaciones</a>
                        <a class="admin-link <?php echo $data['view'] == 'categories' ? 'active' : ''; ?>" href="?view=categories"><i class="fas fa-tags me-2"></i> Categorías</a>
                        <a class="admin-link <?php echo $data['view'] == 'offers' ? 'active' : ''; ?>" href="?view=offers"><i class="fas fa-percent me-2"></i> Ofertas</a>
                        <a class="admin-link <?php echo $data['view'] == 'coupons' ? 'active' : ''; ?>" href="?view=coupons"><i class="fas fa-ticket-alt me-2"></i> Cupones</a>
                        <a class="admin-link <?php echo $data['view'] == 'users' ? 'active' : ''; ?>" href="?view=users"><i class="fas fa-users me-2"></i> Usuarios</a>
                        <a class="admin-link <?php echo $data['view'] == 'messages' ? 'active' : ''; ?>" href="?view=messages"><i class="fas fa-envelope me-2"></i> Mensajes (Ayuda)</a>
                    <?php endif; ?>
                </nav>

                <div class="mt-4 border-top pt-3">
                    <div class="px-3 mb-3 small fw-bold text-primary"><i class="fas fa-user-circle"></i> <?php echo $_SESSION['first_name']; ?> (<?php echo $data['role']; ?>)</div>
                    <a href="<?php echo URLROOT; ?>/" class="btn btn-outline-primary w-100 mb-2 fw-bold" target="_blank">Ver Tienda</a>
                    <a href="<?php echo URLROOT; ?>/auth/logout" class="btn btn-danger w-100 fw-bold">Cerrar Sesión</a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 p-4 p-md-5">

                <!-- MÓDULO: DASHBOARD -->
                <?php if ($data['view'] == 'dashboard'): ?>
                    <h3 class="fw-bold mb-4">Sistema de Gestión</h3>
                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <div class="admin-card p-4 text-center">
                                <h6 class="text-muted fw-bold">Productos</h6>
                                <h1 class="fw-bold text-primary m-0"><?php echo count($data['products']); ?></h1>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="admin-card p-4 text-center">
                                <h6 class="text-muted fw-bold">Pedidos</h6>
                                <h1 class="fw-bold text-success m-0"><?php echo count($data['orders']); ?></h1>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="admin-card p-4 text-center">
                                <h6 class="text-muted fw-bold">Ofertas</h6>
                                <h1 class="fw-bold text-warning m-0"><?php echo count($data['offers']); ?></h1>
                            </div>
                        </div>
                    </div>
                    <div class="row g-4">
                        <div class="col-12">
                            <div class="admin-card p-4">
                                <h5 class="fw-bold mb-4">Estadísticas de Pedidos (últimos 7 días)</h5>
                                <div class="chart-container" style="position: relative; height:300px; width:100%;">
                                    <canvas id="salesChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- MÓDULO: PEDIDOS -->
                <?php elseif ($data['view'] == 'orders'): ?>
                    <div class="admin-card p-4">
                        <h4 class="fw-bold mb-4">Gestión de Pedidos</h4>
                        <div class="table-responsive">
                            <table class="table align-middle datatable">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Cliente (Email)</th>
                                        <th>Total</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data['orders'] as $o): ?>
                                        <tr>
                                            <td class="fw-bold"><?php echo $o->order_code; ?></td>
                                            <td><?php echo $o->email; ?></td>
                                            <td class="fw-bold text-primary">$<?php echo number_format($o->total_amount, 0, ',', '.'); ?></td>
                                            <td><span class="badge-status status-<?php echo str_replace(' ', '', strtolower($o->status)); ?>"><?php echo $o->status; ?></span></td>
                                            <td>
                                                <form action="<?php echo URLROOT; ?>/admin/updateOrderStatus" method="POST" class="d-flex gap-2">
                                                    <input type="hidden" name="order_id" value="<?php echo $o->id; ?>">
                                                    <select name="status" class="form-select form-select-sm" style="width: 150px;">
                                                        <option value="Por confirmar pago" <?php echo $o->status == 'Por confirmar pago' ? 'selected' : ''; ?>>Pendiente</option>
                                                        <option value="Pagado" <?php echo $o->status == 'Pagado' ? 'selected' : ''; ?>>Pagado</option>
                                                        <option value="Cancelado" <?php echo $o->status == 'Cancelado' ? 'selected' : ''; ?>>Cancelado</option>
                                                    </select>
                                                    <button type="submit" class="btn btn-primary btn-sm fw-bold">Actualizar</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- MÓDULO: PRODUCTOS -->
                <?php elseif ($data['view'] == 'products'): ?>
                    <?php if ($data['role'] == 'Administrador'): ?>
                        <!-- Formulario Dinámico de Crear/Editar Productos -->
                        <div class="admin-card p-4 mb-4" id="form-container-product">
                            <h4 class="fw-bold mb-4" id="title-product">Crear Nuevo Producto</h4>
                            <form action="<?php echo URLROOT; ?>/admin/addProduct" method="POST" enctype="multipart/form-data" id="form-product" class="row g-3">
                                <input type="hidden" name="id" id="prod_id" value="">
                                <input type="hidden" name="current_main_image" id="prod_current_main_image" value="">
                                <input type="hidden" name="current_gallery" id="prod_current_gallery" value="">

                                <div class="col-12"><label class="form-label text-muted fw-bold">Nombre</label><input type="text" name="name" id="prod_name" class="form-control" required></div>
                                <div class="col-md-6"><label class="form-label text-muted fw-bold">SKU</label><input type="text" name="sku" id="prod_sku" class="form-control" required></div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted fw-bold">Categoría</label>
                                    <select name="category_id" id="prod_category" class="form-select" required>
                                        <option value="">Seleccione Categoría</option>
                                        <?php foreach ($data['categories'] as $c): ?><option value="<?php echo $c->id; ?>"><?php echo $c->name; ?></option><?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-4"><label class="form-label text-muted fw-bold">Precio ($)</label><input type="number" name="price" id="prod_price" class="form-control" required></div>
                                <div class="col-md-4"><label class="form-label text-muted fw-bold">Descuento (%)</label><input type="number" name="discount_percent" id="prod_discount" class="form-control" value="0"></div>
                                <div class="col-md-4"><label class="form-label text-muted fw-bold">Stock Inicial</label><input type="number" name="stock" id="prod_stock" class="form-control" required></div>

                                <div class="col-md-6">
                                    <label class="form-label text-muted fw-bold">Imagen Principal (URL o Archivo)</label>
                                    <input type="url" name="main_image_url" class="form-control mb-2" placeholder="URL Opcional / Dejar vacío al editar">
                                    <input type="file" name="main_image_file" class="form-control" accept=".png, .jpg, .jpeg, .webp">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted fw-bold">Añadir Imágenes (Galería)</label>
                                    <input type="file" name="gallery_files[]" class="form-control" accept=".png, .jpg, .jpeg, .webp" multiple>
                                    <small class="text-muted">Ctrl + Clic para subir múltiples imágenes.</small>
                                </div>
                                <div class="col-12" id="prod_status_wrapper" style="display:none;">
                                    <label class="form-label text-muted fw-bold">Estado</label>
                                    <select name="status" id="prod_status" class="form-select">
                                        <option value="Publicado">Publicado</option>
                                        <option value="Borrador">Borrador</option>
                                    </select>
                                </div>
                                <div class="col-12"><label class="form-label text-muted fw-bold">Descripción Corta</label><textarea name="short_desc" id="prod_short_desc" class="form-control" rows="2" required></textarea></div>
                                <div class="col-12"><label class="form-label text-muted fw-bold">Descripción Larga</label><textarea name="long_desc" id="prod_long_desc" class="form-control" rows="4" required></textarea></div>

                                <div class="col-12 mt-4 d-flex gap-2">
                                    <button type="submit" id="btn-submit-product" class="btn btn-primary fw-bold py-2 px-4">Guardar Producto</button>
                                    <button type="button" id="btn-cancel-product" class="btn btn-secondary d-none fw-bold py-2 px-4" onclick="resetFormProduct()">Cancelar Edición</button>
                                </div>
                            </form>
                        </div>
                    <?php endif; ?>

                    <div class="admin-card p-4">
                        <h4 class="fw-bold mb-4">Lista de Productos</h4>
                        <div class="table-responsive">
                            <table class="table align-middle datatable">
                                <thead>
                                    <tr>
                                        <th>Img</th>
                                        <th>Nombre</th>
                                        <th>SKU</th>
                                        <th>Precio</th>
                                        <th>Stock</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data['products'] as $p): ?>
                                        <tr>
                                            <td><img src="<?php echo filter_var($p->main_image, FILTER_VALIDATE_URL) ? $p->main_image : URLROOT . '/public' . $p->main_image; ?>" width="40" height="40" class="rounded object-fit-cover border"></td>
                                            <td class="fw-bold text-dark"><?php echo $p->name; ?></td>
                                            <td class="text-muted"><?php echo $p->sku; ?></td>
                                            <td class="fw-bold text-primary">$<?php echo number_format($p->price, 0, ',', '.'); ?></td>
                                            <td><?php echo $p->stock; ?></td>
                                            <td><span class="badge-status status-<?php echo strtolower($p->status); ?>"><?php echo $p->status; ?></span></td>
                                            <td>
                                                <?php if ($data['role'] == 'Administrador'): ?>
                                                    <div class="d-flex gap-2">
                                                        <button class="btn btn-warning btn-sm btn-edit-product"
                                                            data-id="<?php echo $p->id; ?>"
                                                            data-name="<?php echo htmlspecialchars($p->name, ENT_QUOTES); ?>"
                                                            data-sku="<?php echo $p->sku; ?>"
                                                            data-cat="<?php echo $p->category_id; ?>"
                                                            data-price="<?php echo $p->price; ?>"
                                                            data-discount="<?php echo $p->discount_percent; ?>"
                                                            data-stock="<?php echo $p->stock; ?>"
                                                            data-status="<?php echo $p->status; ?>"
                                                            data-short="<?php echo htmlspecialchars($p->short_desc, ENT_QUOTES); ?>"
                                                            data-long="<?php echo htmlspecialchars($p->long_desc, ENT_QUOTES); ?>"
                                                            data-image="<?php echo htmlspecialchars($p->main_image, ENT_QUOTES); ?>"
                                                            data-gallery="<?php echo htmlspecialchars($p->gallery, ENT_QUOTES); ?>">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <a href="<?php echo URLROOT; ?>/admin/deleteEntity/product/<?php echo $p->id; ?>" class="btn btn-danger btn-sm btn-red"><i class="fas fa-trash"></i></a>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="text-muted small fw-bold"><i class="fas fa-lock"></i> Solo Lectura</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- MÓDULO: MENSAJES DE CONTACTO/AYUDA -->
                <?php elseif ($data['view'] == 'messages' && $data['role'] == 'Administrador'): ?>
                    <div class="admin-card p-4">
                        <h4 class="fw-bold mb-4">Mensajes de Ayuda / Contacto</h4>
                        <div class="table-responsive">
                            <table class="table align-middle datatable">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>Asunto</th>
                                        <th>Mensaje</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data['messages'] as $m): ?>
                                        <tr>
                                            <td class="text-muted small"><?php echo $m->created_at; ?></td>
                                            <td class="fw-bold"><?php echo $m->name; ?></td>
                                            <td><a href="mailto:<?php echo $m->email; ?>"><?php echo $m->email; ?></a></td>
                                            <td><?php echo $m->subject; ?></td>
                                            <td><?php echo $m->message; ?></td>
                                            <td>
                                                <?php if ($m->status == 'Unread'): ?>
                                                    <a href="<?php echo URLROOT; ?>/admin/readMessage/<?php echo $m->id; ?>" class="badge bg-warning text-dark text-decoration-none p-2">Marcar Leído</a>
                                                <?php else: ?>
                                                    <span class="badge bg-success p-2"><i class="fas fa-check"></i> Leído</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- MÓDULO: CATEGORÍAS -->
                <?php elseif ($data['view'] == 'categories' && $data['role'] == 'Administrador'): ?>
                    <!-- Formulario Dinámico Categoría -->
                    <div class="admin-card p-4 mb-4" id="form-container-category">
                        <h4 class="fw-bold mb-4" id="title-category">Nueva Categoría</h4>
                        <form action="<?php echo URLROOT; ?>/admin/addCategory" method="POST" id="form-category" class="row g-3">
                            <input type="hidden" name="id" id="cat_id" value="">
                            <div class="col-md-4"><label class="form-label text-muted fw-bold">Nombre</label><input type="text" name="name" id="cat_name" class="form-control" required></div>
                            <div class="col-md-4"><label class="form-label text-muted fw-bold">Icono (SVG o Emoji)</label><input type="text" name="icon" id="cat_icon" class="form-control" required></div>
                            <div class="col-md-4">
                                <label class="form-label text-muted fw-bold">Tipo</label>
                                <select name="is_main" id="cat_main" class="form-select">
                                    <option value="1">Principal (Menú)</option>
                                    <option value="0">Secundaria</option>
                                </select>
                            </div>
                            <div class="col-12 mt-4 d-flex gap-2">
                                <button type="submit" id="btn-submit-category" class="btn btn-primary fw-bold py-2 px-4">Crear Categoría</button>
                                <button type="button" id="btn-cancel-category" class="btn btn-secondary d-none fw-bold py-2 px-4" onclick="resetFormCategory()">Cancelar Edición</button>
                            </div>
                        </form>
                    </div>
                    <div class="admin-card p-4">
                        <h4 class="fw-bold mb-4">Categorías Guardadas</h4>
                        <div class="table-responsive">
                            <table class="table align-middle datatable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Slug</th>
                                        <th>Principal</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data['categories'] as $c): ?>
                                        <tr>
                                            <td><?php echo $c->id; ?></td>
                                            <td class="fw-bold text-dark"><?php echo $c->icon . ' ' . $c->name; ?></td>
                                            <td class="text-muted"><?php echo $c->slug; ?></td>
                                            <td><span class="badge-status <?php echo $c->is_main ? 'status-publicado' : 'status-borrador'; ?>"><?php echo $c->is_main ? 'Sí' : 'No'; ?></span></td>
                                            <td>
                                                <button class="btn btn-warning btn-sm btn-edit-cat"
                                                    data-id="<?php echo $c->id; ?>"
                                                    data-name="<?php echo htmlspecialchars($c->name, ENT_QUOTES); ?>"
                                                    data-icon="<?php echo htmlspecialchars($c->icon, ENT_QUOTES); ?>"
                                                    data-main="<?php echo $c->is_main; ?>"><i class="fas fa-edit"></i></button>
                                                <a href="<?php echo URLROOT; ?>/admin/deleteEntity/category/<?php echo $c->id; ?>" class="btn btn-danger btn-sm btn-red"><i class="fas fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- MÓDULO: VARIACIONES -->
                <?php elseif ($data['view'] == 'variations' && $data['role'] == 'Administrador'): ?>
                    <!-- Formulario Dinámico Variaciones -->
                    <div class="admin-card p-4 mb-4" id="form-container-variation">
                        <h4 class="fw-bold mb-4" id="title-variation">Agregar Variación a Producto</h4>
                        <form action="<?php echo URLROOT; ?>/admin/addVariation" method="POST" id="form-variation" class="row g-3">
                            <input type="hidden" name="id" id="var_id" value="">
                            <div class="col-md-6" id="var_prod_wrapper">
                                <label class="form-label text-muted fw-semibold">Producto Padre</label>
                                <select name="product_id" id="var_prod_id" class="form-select bg-light border-0">
                                    <option value="">Seleccione Producto</option>
                                    <?php foreach ($data['products'] as $p): ?><option value="<?php echo $p->id; ?>"><?php echo $p->name; ?></option><?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6"><label class="form-label text-muted fw-semibold">Nombre Variación</label><input type="text" name="name" id="var_name" class="form-control bg-light border-0" placeholder="Ej: Caja x 12" required></div>
                            <div class="col-md-4"><label class="form-label text-muted fw-semibold">SKU</label><input type="text" name="sku" id="var_sku" class="form-control bg-light border-0" required></div>
                            <div class="col-md-4"><label class="form-label text-muted fw-semibold">Precio Variación ($)</label><input type="number" name="price" id="var_price" class="form-control bg-light border-0" required></div>
                            <div class="col-md-4"><label class="form-label text-muted fw-semibold">Stock</label><input type="number" name="stock" id="var_stock" class="form-control bg-light border-0" required></div>
                            <div class="col-12 mt-4 d-flex gap-2">
                                <button type="submit" id="btn-submit-variation" class="btn btn-primary fw-bold py-2 px-4">Guardar Variación</button>
                                <button type="button" id="btn-cancel-variation" class="btn btn-secondary d-none fw-bold py-2 px-4" onclick="resetFormVariation()">Cancelar Edición</button>
                            </div>
                        </form>
                    </div>
                    <div class="admin-card p-4">
                        <h4 class="fw-bold mb-4">Lista de Variaciones</h4>
                        <div class="table-responsive">
                            <table class="table align-middle datatable">
                                <thead>
                                    <tr>
                                        <th>Producto Padre</th>
                                        <th>Variación</th>
                                        <th>SKU</th>
                                        <th>Precio</th>
                                        <th>Stock</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data['variations'] as $v): ?>
                                        <tr>
                                            <td class="text-muted"><?php echo $v->p_name; ?></td>
                                            <td class="fw-bold text-dark"><?php echo $v->name; ?></td>
                                            <td><?php echo $v->sku; ?></td>
                                            <td class="text-primary fw-bold">$<?php echo number_format($v->price, 0, ',', '.'); ?></td>
                                            <td><?php echo $v->stock; ?></td>
                                            <td>
                                                <button class="btn btn-warning btn-sm btn-edit-var"
                                                    data-id="<?php echo $v->id; ?>"
                                                    data-name="<?php echo htmlspecialchars($v->name, ENT_QUOTES); ?>"
                                                    data-sku="<?php echo $v->sku; ?>"
                                                    data-price="<?php echo $v->price; ?>"
                                                    data-stock="<?php echo $v->stock; ?>"><i class="fas fa-edit"></i></button>
                                                <a href="<?php echo URLROOT; ?>/admin/deleteEntity/variation/<?php echo $v->id; ?>" class="btn btn-danger btn-sm btn-red"><i class="fas fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- MÓDULO: OFERTAS -->
                <?php elseif ($data['view'] == 'offers' && $data['role'] == 'Administrador'): ?>
                    <!-- Formulario Dinámico Ofertas -->
                    <div class="admin-card p-4 mb-4" id="form-container-offer">
                        <h4 class="fw-bold mb-4" id="title-offer">Nueva Campaña de Oferta</h4>
                        <form action="<?php echo URLROOT; ?>/admin/addOffer" method="POST" enctype="multipart/form-data" id="form-offer" class="row g-3">
                            <input type="hidden" name="id" id="off_id" value="">
                            <input type="hidden" name="current_banner" id="off_current_banner" value="">
                            <div class="col-md-6"><label class="form-label text-muted fw-bold">Nombre Campaña</label><input type="text" name="name" id="off_name" class="form-control" required></div>
                            <div class="col-md-6"><label class="form-label text-muted fw-bold">Descuento (%)</label><input type="number" name="discount" id="off_discount" class="form-control" required></div>
                            <div class="col-12"><label class="form-label text-muted fw-bold">Título Promocional</label><input type="text" name="title" id="off_title" class="form-control" required></div>
                            <div class="col-12"><label class="form-label text-muted fw-bold">Descripción Corta</label><textarea name="short_desc" id="off_short" class="form-control" rows="2" required></textarea></div>
                            <div class="col-md-6"><label class="form-label text-muted fw-bold">Fecha Inicio</label><input type="datetime-local" name="start" id="off_start" class="form-control" required></div>
                            <div class="col-md-6"><label class="form-label text-muted fw-bold">Fecha Fin</label><input type="datetime-local" name="end" id="off_end" class="form-control" required></div>
                            <div class="col-12">
                                <label class="form-label text-muted fw-bold">Imagen Banner (URL o Archivo)</label>
                                <input type="url" name="banner_image_url" class="form-control mb-2" placeholder="Dejar vacío al editar si no se cambia">
                                <input type="file" name="banner_image_file" class="form-control">
                            </div>
                            <div class="col-12 mt-4 d-flex gap-2">
                                <button type="submit" id="btn-submit-offer" class="btn btn-primary w-100 fw-bold py-3">Activar Oferta</button>
                                <button type="button" id="btn-cancel-offer" class="btn btn-secondary w-100 d-none fw-bold py-3" onclick="resetFormOffer()">Cancelar Edición</button>
                            </div>
                        </form>
                    </div>
                    <div class="admin-card p-4">
                        <h4 class="fw-bold mb-4">Ofertas Activas</h4>
                        <div class="table-responsive">
                            <table class="table align-middle datatable">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Descuento</th>
                                        <th>Inicio</th>
                                        <th>Fin</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data['offers'] as $o): ?>
                                        <tr>
                                            <td class="fw-bold text-dark"><?php echo $o->name; ?></td>
                                            <td class="fw-bold text-warning"><?php echo $o->discount_percent; ?>%</td>
                                            <td><?php echo $o->start_date; ?></td>
                                            <td><?php echo $o->end_date; ?></td>
                                            <td>
                                                <button class="btn btn-warning btn-sm btn-edit-offer"
                                                    data-id="<?php echo $o->id; ?>"
                                                    data-name="<?php echo htmlspecialchars($o->name, ENT_QUOTES); ?>"
                                                    data-discount="<?php echo $o->discount_percent; ?>"
                                                    data-title="<?php echo htmlspecialchars($o->title, ENT_QUOTES); ?>"
                                                    data-short="<?php echo htmlspecialchars($o->short_desc, ENT_QUOTES); ?>"
                                                    data-start="<?php echo date('Y-m-d\TH:i', strtotime($o->start_date)); ?>"
                                                    data-end="<?php echo date('Y-m-d\TH:i', strtotime($o->end_date)); ?>"
                                                    data-banner="<?php echo htmlspecialchars($o->banner_image, ENT_QUOTES); ?>"><i class="fas fa-edit"></i></button>
                                                <a href="<?php echo URLROOT; ?>/admin/deleteEntity/offer/<?php echo $o->id; ?>" class="btn btn-danger btn-sm btn-red"><i class="fas fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- MÓDULO: CUPONES -->
                <?php elseif ($data['view'] == 'coupons' && $data['role'] == 'Administrador'): ?>
                    <!-- Formulario Dinámico Cupones -->
                    <div class="admin-card p-4 mb-4" id="form-container-coupon">
                        <h4 class="fw-bold mb-4" id="title-coupon">Generar Cupón</h4>
                        <form action="<?php echo URLROOT; ?>/admin/addCoupon" method="POST" id="form-coupon" class="row g-3">
                            <input type="hidden" name="id" id="coup_id" value="">
                            <div class="col-md-3"><label class="form-label text-muted fw-semibold">Código</label><input type="text" name="code" id="coup_code" class="form-control bg-light border-0" required></div>
                            <div class="col-md-3"><label class="form-label text-muted fw-semibold">Descuento (%)</label><input type="number" name="discount" id="coup_discount" class="form-control bg-light border-0" required></div>
                            <div class="col-md-3"><label class="form-label text-muted fw-semibold">Inicio</label><input type="datetime-local" name="start" id="coup_start" class="form-control bg-light border-0" required></div>
                            <div class="col-md-3"><label class="form-label text-muted fw-semibold">Fin</label><input type="datetime-local" name="end" id="coup_end" class="form-control bg-light border-0" required></div>
                            <div class="col-12 mt-4 d-flex gap-2">
                                <button type="submit" id="btn-submit-coupon" class="btn btn-primary w-100 fw-bold py-3">Crear Cupón</button>
                                <button type="button" id="btn-cancel-coupon" class="btn btn-secondary w-100 d-none fw-bold py-3" onclick="resetFormCoupon()">Cancelar Edición</button>
                            </div>
                        </form>
                    </div>
                    <div class="admin-card p-4">
                        <h4 class="fw-bold mb-4">Cupones Creados</h4>
                        <div class="table-responsive">
                            <table class="table align-middle datatable">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Descuento</th>
                                        <th>Inicio</th>
                                        <th>Fin</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data['coupons'] as $c): ?>
                                        <tr>
                                            <td class="fw-bold text-dark"><?php echo $c->code; ?></td>
                                            <td class="fw-bold text-success"><?php echo $c->discount_percent; ?>%</td>
                                            <td><?php echo $c->start_date; ?></td>
                                            <td><?php echo $c->end_date; ?></td>
                                            <td>
                                                <button class="btn btn-warning btn-sm btn-edit-coup"
                                                    data-id="<?php echo $c->id; ?>"
                                                    data-code="<?php echo $c->code; ?>"
                                                    data-discount="<?php echo $c->discount_percent; ?>"
                                                    data-start="<?php echo date('Y-m-d\TH:i', strtotime($c->start_date)); ?>"
                                                    data-end="<?php echo date('Y-m-d\TH:i', strtotime($c->end_date)); ?>"><i class="fas fa-edit"></i></button>
                                                <a href="<?php echo URLROOT; ?>/admin/deleteEntity/coupon/<?php echo $c->id; ?>" class="btn btn-danger btn-sm btn-red"><i class="fas fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- MÓDULO: USUARIOS -->
                <?php elseif ($data['view'] == 'users' && $data['role'] == 'Administrador'): ?>
                    <!-- Formulario Dinámico Usuarios -->
                    <div class="admin-card p-4 mb-4" id="form-container-user">
                        <h4 class="fw-bold mb-4" id="title-user">Registrar Empleado / Vendedor</h4>
                        <form action="<?php echo URLROOT; ?>/admin/addUser" method="POST" id="form-user" class="row g-3">
                            <input type="hidden" name="id" id="usr_id" value="">
                            <div class="col-md-4"><label class="form-label text-muted fw-semibold">Nombres</label><input type="text" name="first_name" id="usr_first" class="form-control bg-light border-0" required></div>
                            <div class="col-md-4"><label class="form-label text-muted fw-semibold">Apellidos</label><input type="text" name="last_name" id="usr_last" class="form-control bg-light border-0" required></div>
                            <div class="col-md-4"><label class="form-label text-muted fw-semibold">Correo</label><input type="email" name="email" id="usr_email" class="form-control bg-light border-0" required></div>
                            <div class="col-md-3" id="usr_username_wrapper"><label class="form-label text-muted fw-semibold">Usuario</label><input type="text" name="username" class="form-control bg-light border-0" required></div>
                            <div class="col-md-3" id="usr_password_wrapper"><label class="form-label text-muted fw-semibold">Clave</label><input type="password" name="password" class="form-control bg-light border-0" required></div>
                            <div class="col-md-3" id="usr_cedula_wrapper"><label class="form-label text-muted fw-semibold">Cédula</label><input type="text" name="cedula" class="form-control bg-light border-0" required></div>
                            <div class="col-md-3" id="usr_phone_wrapper"><label class="form-label text-muted fw-semibold">Teléfono</label><input type="text" name="phone" class="form-control bg-light border-0" required></div>

                            <div class="col-md-6"><label class="form-label text-muted fw-semibold">Rol</label><select name="role" id="usr_role" class="form-select bg-light border-0">
                                    <option value="Cliente">Cliente</option>
                                    <option value="Vendedor">Vendedor</option>
                                    <option value="Administrador">Administrador</option>
                                </select></div>
                            <div class="col-md-6" id="usr_status_wrapper" style="display:none;"><label class="form-label text-muted fw-semibold">Estado de la Cuenta</label><select name="status" id="usr_status" class="form-select bg-light border-0">
                                    <option value="active">Activo</option>
                                    <option value="inactive">Inactivo</option>
                                </select></div>

                            <div class="col-12 mt-4 d-flex gap-2">
                                <button type="submit" id="btn-submit-user" class="btn btn-primary w-100 fw-bold py-3">Registrar Personal</button>
                                <button type="button" id="btn-cancel-user" class="btn btn-secondary w-100 d-none fw-bold py-3" onclick="resetFormUser()">Cancelar Edición</button>
                            </div>
                        </form>
                    </div>
                    <div class="admin-card p-4">
                        <h4 class="fw-bold mb-4">Usuarios Registrados</h4>
                        <div class="table-responsive">
                            <table class="table align-middle datatable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>Rol</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data['users'] as $u): ?>
                                        <tr>
                                            <td><?php echo $u->id; ?></td>
                                            <td class="fw-bold text-dark"><?php echo $u->first_name . ' ' . $u->last_name; ?></td>
                                            <td class="text-muted"><?php echo $u->email; ?></td>
                                            <td><span class="badge-status <?php echo $u->role == 'Administrador' ? 'status-publicado' : 'status-borrador'; ?>"><?php echo $u->role; ?></span></td>
                                            <td><span class="badge <?php echo $u->status == 'active' ? 'bg-success' : 'bg-danger'; ?>"><?php echo $u->status == 'active' ? 'Activo' : 'Inactivo'; ?></span></td>
                                            <td>
                                                <button class="btn btn-warning btn-sm btn-edit-usr"
                                                    data-id="<?php echo $u->id; ?>"
                                                    data-first="<?php echo htmlspecialchars($u->first_name, ENT_QUOTES); ?>"
                                                    data-last="<?php echo htmlspecialchars($u->last_name, ENT_QUOTES); ?>"
                                                    data-email="<?php echo $u->email; ?>"
                                                    data-role="<?php echo $u->role; ?>"
                                                    data-status="<?php echo $u->status; ?>"><i class="fas fa-edit"></i></button>
                                                <?php if ($u->role != 'Administrador'): ?>
                                                    <a href="<?php echo URLROOT; ?>/admin/deleteEntity/user/<?php echo $u->id; ?>" class="btn btn-danger btn-sm btn-red"><i class="fas fa-trash"></i></a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Scripts Generales Admin -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.css"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <?php if (isset($_SESSION['flash_msg'])): ?>
        <script>
            Swal.fire({
                icon: '<?php echo $_SESSION['flash_msg']['type']; ?>',
                title: '<?php echo $_SESSION['flash_msg']['text']; ?>',
                confirmButtonColor: '#1a73e8',
                customClass: {
                    popup: 'rounded-4'
                }
            });
        </script>
    <?php unset($_SESSION['flash_msg']);
    endif; ?>

    <script>
        const URLROOT = '<?php echo URLROOT; ?>';

        // Funciones Reset Vanilla JS
        function resetFormCategory() {
            document.getElementById('form-category').reset();
            document.getElementById('cat_id').value = '';
            document.getElementById('title-category').innerText = 'Nueva Categoría';
            document.getElementById('btn-submit-category').innerText = 'Crear Categoría';
            document.getElementById('btn-submit-category').className = 'btn btn-primary fw-bold py-2 px-4';
            document.getElementById('btn-cancel-category').classList.add('d-none');
            document.getElementById('form-category').action = URLROOT + '/admin/addCategory';
        }

        function resetFormVariation() {
            document.getElementById('form-variation').reset();
            document.getElementById('var_id').value = '';
            document.getElementById('var_prod_wrapper').style.display = 'block';
            document.getElementById('var_prod_id').required = true;
            document.getElementById('title-variation').innerText = 'Agregar Variación a Producto';
            document.getElementById('btn-submit-variation').innerText = 'Guardar Variación';
            document.getElementById('btn-submit-variation').className = 'btn btn-primary fw-bold py-2 px-4';
            document.getElementById('btn-cancel-variation').classList.add('d-none');
            document.getElementById('form-variation').action = URLROOT + '/admin/addVariation';
        }

        function resetFormOffer() {
            document.getElementById('form-offer').reset();
            document.getElementById('off_id').value = '';
            document.getElementById('title-offer').innerText = 'Nueva Campaña de Oferta';
            document.getElementById('btn-submit-offer').innerText = 'Activar Oferta';
            document.getElementById('btn-submit-offer').className = 'btn btn-primary w-100 fw-bold py-3';
            document.getElementById('btn-cancel-offer').classList.add('d-none');
            document.getElementById('form-offer').action = URLROOT + '/admin/addOffer';
        }

        function resetFormCoupon() {
            document.getElementById('form-coupon').reset();
            document.getElementById('coup_id').value = '';
            document.getElementById('title-coupon').innerText = 'Generar Cupón';
            document.getElementById('btn-submit-coupon').innerText = 'Crear Cupón';
            document.getElementById('btn-submit-coupon').className = 'btn btn-primary w-100 fw-bold py-3';
            document.getElementById('btn-cancel-coupon').classList.add('d-none');
            document.getElementById('form-coupon').action = URLROOT + '/admin/addCoupon';
        }

        function resetFormUser() {
            document.getElementById('form-user').reset();
            document.getElementById('usr_id').value = '';
            document.getElementById('usr_username_wrapper').style.display = 'block';
            document.getElementById('usr_password_wrapper').style.display = 'block';
            document.getElementById('usr_cedula_wrapper').style.display = 'block';
            document.getElementById('usr_phone_wrapper').style.display = 'block';
            document.getElementById('usr_status_wrapper').style.display = 'none';

            document.getElementById('title-user').innerText = 'Registrar Empleado / Vendedor';
            document.getElementById('btn-submit-user').innerText = 'Registrar Personal';
            document.getElementById('btn-submit-user').className = 'btn btn-primary w-100 fw-bold py-3';
            document.getElementById('btn-cancel-user').classList.add('d-none');
            document.getElementById('form-user').action = URLROOT + '/admin/addUser';
        }

        function resetFormProduct() {
            document.getElementById('form-product').reset();
            document.getElementById('prod_id').value = '';
            document.getElementById('prod_status_wrapper').style.display = 'none';
            document.getElementById('title-product').innerText = 'Crear Nuevo Producto';
            document.getElementById('btn-submit-product').innerText = 'Guardar Producto';
            document.getElementById('btn-submit-product').className = 'btn btn-primary fw-bold py-2 px-4';
            document.getElementById('btn-cancel-product').classList.add('d-none');
            document.getElementById('form-product').action = URLROOT + '/admin/addProduct';
        }

        $(document).ready(function() {
            $('.datatable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                },
                pageLength: 10,
                ordering: true
            });

            $('.btn-red').on('click', function(e) {
                if (!confirm('¡ALERTA: Seguro que desea eliminar permanentemente este registro de la base de datos?')) {
                    e.preventDefault();
                }
            });

            // EDICIÓN CATEGORÍAS
            $(document).on('click', '.btn-edit-cat', function() {
                const d = $(this).data();
                $('#cat_id').val(d.id);
                $('#cat_name').val(d.name);
                $('#cat_icon').val(d.icon);
                $('#cat_main').val(d.main);
                $('#title-category').text('Editar Categoría');
                $('#btn-submit-category').text('Actualizar').removeClass('btn-primary').addClass('btn-warning text-dark');
                $('#btn-cancel-category').removeClass('d-none');
                $('#form-category').attr('action', URLROOT + '/admin/updateCategory');
                document.getElementById('form-container-category').scrollIntoView({
                    behavior: 'smooth'
                });
            });

            // EDICIÓN VARIACIONES
            $(document).on('click', '.btn-edit-var', function() {
                const d = $(this).data();
                $('#var_id').val(d.id);
                $('#var_name').val(d.name);
                $('#var_sku').val(d.sku);
                $('#var_price').val(d.price);
                $('#var_stock').val(d.stock);
                $('#var_prod_wrapper').hide();
                $('#var_prod_id').removeAttr('required'); // Ocultar producto padre al editar
                $('#title-variation').text('Editar Variación');
                $('#btn-submit-variation').text('Actualizar').removeClass('btn-primary').addClass('btn-warning text-dark');
                $('#btn-cancel-variation').removeClass('d-none');
                $('#form-variation').attr('action', URLROOT + '/admin/updateVariation');
                document.getElementById('form-container-variation').scrollIntoView({
                    behavior: 'smooth'
                });
            });

            // EDICIÓN OFERTAS
            $(document).on('click', '.btn-edit-offer', function() {
                const d = $(this).data();
                $('#off_id').val(d.id);
                $('#off_name').val(d.name);
                $('#off_discount').val(d.discount);
                $('#off_title').val(d.title);
                $('#off_short').val(d.short);
                $('#off_start').val(d.start);
                $('#off_end').val(d.end);
                $('#off_current_banner').val(d.banner);
                $('#title-offer').text('Editar Campaña');
                $('#btn-submit-offer').text('Actualizar').removeClass('btn-primary').addClass('btn-warning text-dark');
                $('#btn-cancel-offer').removeClass('d-none');
                $('#form-offer').attr('action', URLROOT + '/admin/updateOffer');
                document.getElementById('form-container-offer').scrollIntoView({
                    behavior: 'smooth'
                });
            });

            // EDICIÓN CUPONES
            $(document).on('click', '.btn-edit-coup', function() {
                const d = $(this).data();
                $('#coup_id').val(d.id);
                $('#coup_code').val(d.code);
                $('#coup_discount').val(d.discount);
                $('#coup_start').val(d.start);
                $('#coup_end').val(d.end);
                $('#title-coupon').text('Editar Cupón');
                $('#btn-submit-coupon').text('Actualizar').removeClass('btn-primary').addClass('btn-warning text-dark');
                $('#btn-cancel-coupon').removeClass('d-none');
                $('#form-coupon').attr('action', URLROOT + '/admin/updateCoupon');
                document.getElementById('form-container-coupon').scrollIntoView({
                    behavior: 'smooth'
                });
            });

            // EDICIÓN USUARIOS
            $(document).on('click', '.btn-edit-usr', function() {
                const d = $(this).data();
                $('#usr_id').val(d.id);
                $('#usr_first').val(d.first);
                $('#usr_last').val(d.last);
                $('#usr_email').val(d.email);
                $('#usr_role').val(d.role);
                $('#usr_status').val(d.status);

                // Ocultar campos de creación que no se editan masivamente
                $('#usr_username_wrapper').hide();
                $('#usr_password_wrapper').hide();
                $('#usr_cedula_wrapper').hide();
                $('#usr_phone_wrapper').hide();
                $('#usr_status_wrapper').show();

                $('#title-user').text('Editar Usuario');
                $('#btn-submit-user').text('Actualizar').removeClass('btn-primary').addClass('btn-warning text-dark');
                $('#btn-cancel-user').removeClass('d-none');
                $('#form-user').attr('action', URLROOT + '/admin/updateUser');
                document.getElementById('form-container-user').scrollIntoView({
                    behavior: 'smooth'
                });
            });

            // EDICIÓN PRODUCTOS
            $(document).on('click', '.btn-edit-product', function() {
                const d = $(this).data();
                $('#prod_id').val(d.id);
                $('#prod_name').val(d.name);
                $('#prod_sku').val(d.sku);
                $('#prod_category').val(d.cat);
                $('#prod_price').val(d.price);
                $('#prod_discount').val(d.discount);
                $('#prod_stock').val(d.stock);
                $('#prod_status').val(d.status);
                $('#prod_short_desc').val(d.short);
                $('#prod_long_desc').val(d.long);
                $('#prod_current_main_image').val(d.image);
                $('#prod_current_gallery').val(d.gallery);

                $('#prod_status_wrapper').show();
                $('#title-product').text('Editar Producto');
                $('#btn-submit-product').text('Actualizar Producto').removeClass('btn-primary').addClass('btn-warning text-dark');
                $('#btn-cancel-product').removeClass('d-none');
                $('#form-product').attr('action', URLROOT + '/admin/updateProduct');
                document.getElementById('form-container-product').scrollIntoView({
                    behavior: 'smooth'
                });
            });

            // Gráfico Resumen
            if (document.getElementById('salesChart')) {
                const ctx = document.getElementById('salesChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab', 'Dom'],
                        datasets: [{
                            label: 'Pedidos Pagados',
                            data: [5, 12, 8, 15, 6, 20, 18],
                            backgroundColor: '#1a73e8',
                            borderRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
            }
        });
    </script>
</body>

</html>