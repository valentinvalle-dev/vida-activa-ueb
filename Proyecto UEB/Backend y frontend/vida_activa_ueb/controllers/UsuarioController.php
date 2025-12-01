<?php
require_once 'models/Usuario.php';

class UsuarioController {

    /**
     * Muestra el formulario de login
     */
    public function mostrarLogin() {
        require_once 'views/login.php';
    }

    /**
     * Muestra el formulario de registro
     */
    public function mostrarRegistro() {
        require_once 'views/registro.php';
    }

    /**
     * Procesa el login del usuario
     */
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';

            if (!empty($email) && !empty($password)) {
                $usuarioModel = new Usuario();
                $usuario = $usuarioModel->login($email, $password);

                if ($usuario) {
                    // Guardar datos en la sesión
                    $_SESSION['usuario_id'] = $usuario['id_usuario'];
                    $_SESSION['usuario_email'] = $usuario['email'];
                    $_SESSION['usuario_nombre'] = $usuario['nombre'];
                    
                    // Redirigir al dashboard
                    header("Location: index.php?controller=DashboardController&action=index");
                    exit();
                } else {
                    $_SESSION['mensaje_error'] = "Email o contraseña incorrectos.";
                    header("Location: index.php?controller=UsuarioController&action=mostrarLogin");
                    exit();
                }
            } else {
                $_SESSION['mensaje_error'] = "Por favor, completa todos los campos.";
                header("Location: index.php?controller=UsuarioController&action=mostrarLogin");
                exit();
            }
        } else {
            // Si no es POST, mostrar el formulario
            $this->mostrarLogin();
        }
    }

    /**
     * Procesa el registro de un nuevo usuario
     */
    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';

            // Validar que los campos no estén vacíos
            if (!empty($nombre) && !empty($email) && !empty($password)) {
                
                $usuarioModel = new Usuario();
                
                // Verificar si el email ya existe
                if ($usuarioModel->existeEmail($email)) {
                    $_SESSION['mensaje_error'] = "Este email ya está registrado.";
                    header("Location: index.php?controller=UsuarioController&action=mostrarRegistro");
                    exit();
                }

                // Guardar el usuario
                $usuarioModel->setNombre($nombre);
                $usuarioModel->setEmail($email);
                $usuarioModel->setPassword($password);
                
                $guardar = $usuarioModel->guardar();

                if ($guardar) {
                    $_SESSION['mensaje_exito'] = "¡Registro exitoso! Ahora puedes iniciar sesión.";
                    header("Location: index.php?controller=UsuarioController&action=mostrarLogin");
                    exit();
                } else {
                    $_SESSION['mensaje_error'] = "Error al registrar el usuario.";
                    header("Location: index.php?controller=UsuarioController&action=mostrarRegistro");
                    exit();
                }
            } else {
                $_SESSION['mensaje_error'] = "Por favor, completa todos los campos.";
                header("Location: index.php?controller=UsuarioController&action=mostrarRegistro");
                exit();
            }
        } else {
            $this->mostrarRegistro();
        }
    }

    /**
     * Cierra la sesión del usuario
     */
    public function logout() {
        session_destroy();
        header("Location: index.php?controller=UsuarioController&action=mostrarLogin");
        exit();
    }
}
?>