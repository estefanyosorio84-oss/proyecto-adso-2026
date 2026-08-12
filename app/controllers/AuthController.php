<?php
class AuthController extends Controller
{

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userModel = $this->model('User');
            $user = $userModel->login(trim($_POST['email']), trim($_POST['password']));

            if ($user) {
                $_SESSION['user_id'] = $user->id;
                $_SESSION['role'] = $user->role;
                $_SESSION['first_name'] = $user->first_name;
                $_SESSION['email'] = $user->email;

                if ($user->role === 'Administrador' || $user->role === 'Vendedor') {
                    header('Location: ' . URLROOT . '/admin');
                } else {
                    // Si viene del checkout, lo devolvemos allá
                    header('Location: ' . URLROOT . '/checkout');
                }
                exit;
            } else {
                $_SESSION['flash_msg'] = ['type' => 'error', 'text' => 'Credenciales Incorrectas. Intente nuevamente.'];
            }
        }

        $this->view('layout/header', ['title' => 'Iniciar Sesión']);
        // HTML INCLUIDO DIRECTAMENTE AQUÍ COMO VISTA (Para mantener tu estructura original pero bonita)
?>
        <main class="container py-5">
            <div class="auth-container text-center">
                <div class="mb-4"><i class="fas fa-user-circle fa-4x text-primary"></i></div>
                <h2 class="fw-bold mb-4 text-dark">Iniciar Sesión</h2>

                <?php if (isset($_SESSION['flash_msg'])): ?>
                    <div class="alert alert-danger border-0 rounded-3"><?php echo $_SESSION['flash_msg']['text']; ?></div>
                <?php unset($_SESSION['flash_msg']);
                endif; ?>

                <form action="<?php echo URLROOT; ?>/auth/login" method="POST" class="auth-form text-start">
                    <div class="mb-3">
                        <label class="form-label text-muted fw-semibold ms-1">Correo Electrónico</label>
                        <input type="email" name="email" class="form-control" placeholder="tucorreo@ejemplo.com" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-muted fw-semibold ms-1">Contraseña</label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-3 fs-5">Entrar</button>
                </form>
                <div class="mt-4 text-muted">
                    ¿No tienes una cuenta? <a href="<?php echo URLROOT; ?>/auth/register" class="fw-bold text-primary text-decoration-none">Regístrate aquí</a>
                </div>
            </div>
        </main>
    <?php
        $this->view('layout/footer');
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userModel = $this->model('User');
            if ($userModel->register($_POST)) {
                $_SESSION['flash_msg'] = ['type' => 'success', 'text' => 'Cuenta creada exitosamente. Ya puedes iniciar sesión.'];
                header('Location: ' . URLROOT . '/auth/login');
                exit;
            }
        }

        $this->view('layout/header', ['title' => 'Crear Cuenta']);
    ?>
        <main class="container py-5">
            <div class="auth-container" style="max-width: 600px;">
                <h2 class="fw-bold mb-4 text-dark text-center">Crear Cuenta Nueva</h2>
                <form action="<?php echo URLROOT; ?>/auth/register" method="POST" class="auth-form text-start">
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted fw-semibold ms-1">Nombres</label>
                            <input type="text" name="first_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted fw-semibold ms-1">Apellidos</label>
                            <input type="text" name="last_name" class="form-control" required>
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted fw-semibold ms-1">Cédula</label>
                            <input type="text" name="cedula" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted fw-semibold ms-1">Teléfono Móvil</label>
                            <input type="text" name="phone" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted fw-semibold ms-1">Nombre de Usuario</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted fw-semibold ms-1">Correo Electrónico</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-muted fw-semibold ms-1">Contraseña</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-3 fs-5">Registrarme</button>
                </form>
                <div class="mt-4 text-muted text-center">
                    ¿Ya tienes cuenta? <a href="<?php echo URLROOT; ?>/auth/login" class="fw-bold text-primary text-decoration-none">Inicia Sesión</a>
                </div>
            </div>
        </main>
<?php
        $this->view('layout/footer');
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        header('Location: ' . URLROOT . '/');
    }
}
