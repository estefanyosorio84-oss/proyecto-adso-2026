<main class="container py-5">
    <div class="text-center mb-5">
        <h1 class="fw-bold text-primary">Centro de Ayuda</h1>
        <p class="text-muted fs-5">Estamos aquí para resolver tus dudas y apoyarte en tu compra.</p>
    </div>
    <div class="row g-5">
        <div class="col-lg-6">
            <h3 class="fw-bold mb-4">Preguntas Frecuentes</h3>
            <div class="accordion shadow-sm border-0 rounded-4" id="faqAccordion">
                <div class="accordion-item border-0 border-bottom">
                    <h2 class="accordion-header"><button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">¿Cómo realizo el pago de mi pedido?</button></h2>
                    <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted">Al generar tu pedido en la web, obtendrás un código único. Con ese código debes acercarte a nuestra tienda física para realizar el pago en efectivo o tarjeta y retirar tus productos.</div>
                    </div>
                </div>
                <div class="accordion-item border-0 border-bottom">
                    <h2 class="accordion-header"><button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">¿Tienen envíos a domicilio?</button></h2>
                    <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted">Sí, ofrecemos envío gratuito en productos seleccionados y zonas céntricas.</div>
                    </div>
                </div>
                <div class="accordion-item border-0">
                    <h2 class="accordion-header"><button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">¿Cuál es el horario de atención físico?</button></h2>
                    <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted">Atendemos de Lunes a Sábado de 8:00 AM a 6:00 PM.</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm border">
                <h3 class="fw-bold mb-4">Contáctanos</h3>
                <?php if (isset($_SESSION['flash_msg'])): ?>
                    <div class="alert alert-<?php echo $_SESSION['flash_msg']['type'] == 'success' ? 'success' : 'danger'; ?> border-0 rounded-3"><?php echo $_SESSION['flash_msg']['text']; ?></div>
                <?php unset($_SESSION['flash_msg']);
                endif; ?>
                <form action="<?php echo URLROOT; ?>/ayuda/send" method="POST">
                    <div class="mb-3">
                        <label class="form-label text-muted fw-semibold">Nombre Completo</label>
                        <input type="text" name="name" class="form-control bg-light border-0 py-2" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted fw-semibold">Correo Electrónico</label>
                        <input type="email" name="email" class="form-control bg-light border-0 py-2" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted fw-semibold">Asunto</label>
                        <input type="text" name="subject" class="form-control bg-light border-0 py-2" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-muted fw-semibold">Mensaje</label>
                        <textarea class="form-control bg-light border-0" name="message" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold fs-6">Enviar Mensaje</button>
                </form>
            </div>
        </div>
    </div>
</main>