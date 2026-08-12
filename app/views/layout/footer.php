<footer class="main-footer">
    <div class="container">
        <div class="row gy-4">
            <div class="col-lg-4">
                <h4 class="fw-bold text-primary mb-3">Papeleria todo arte</h4>
                <p class="text-muted">Papelería con todo lo que necesitas para el colegio, la universidad y la oficina. Reserva en línea y paga en nuestro punto físico.</p>
            </div>
            <div class="col-lg-4">
                <h5 class="fw-bold mb-3">Ayuda</h5>
                <ul class="list-unstyled text-muted lh-lg">
                    <li><a href="<?php echo URLROOT; ?>/ayuda" class="text-decoration-none text-muted">Preguntas frecuentes</a></li>
                    <li><a href="<?php echo URLROOT; ?>/ayuda" class="text-decoration-none text-muted">Políticas de privacidad</a></li>
                    <li><a href="<?php echo URLROOT; ?>/ayuda" class="text-decoration-none text-muted">Términos y condiciones</a></li>
                </ul>
            </div>
            <div class="col-lg-4">
                <h5 class="fw-bold mb-3">Contacto</h5>
                <ul class="list-unstyled text-muted lh-lg">
                    <li><i class="fab fa-whatsapp text-success me-2"></i> WhatsApp: +57 300 000 0000</li>
                    <li><i class="fas fa-map-marker-alt text-danger me-2"></i> Punto físico en Medellín</li>
                </ul>
            </div>
        </div>
        <div class="text-center mt-5 pt-4 border-top text-muted small">
            &copy; <?php echo date('Y'); ?> Papeleria todo arte. Todos los derechos reservados.
        </div>
    </div>
</footer>
<!-- Scripts Generales -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.css"></script>
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const URLROOT = "<?php echo URLROOT; ?>";

    // Función global para mostrar alertas usando SweetAlert2
    function showAlert(title, text, icon = 'success') {
        Swal.fire({
            title: title,
            text: text,
            icon: icon,
            confirmButtonColor: '#1a73e8',
            confirmButtonText: 'Aceptar',
            customClass: {
                popup: 'rounded-4'
            }
        });
    }
</script>
<script src="<?php echo URLROOT; ?>/js/main.js"></script>
</body>

</html>