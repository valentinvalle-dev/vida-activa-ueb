<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Vida Activa UEB</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <div class="corner-logo">
        <img src="assets/images/logo.png" alt="Logo Vida Activa UEB">
    </div>

    <div class="auth-container">
        
        <form class="auth-form" action="index.php?controller=UsuarioController&action=login" method="POST">
            
            <div class="auth-form-title">
                <h2>Vida Activa UEB</h2>
            </div>
            
            <h1>Iniciar Sesión</h1>

            <?php
            // Mostrar mensajes de error o éxito
            if (isset($_SESSION['mensaje_error'])) {
                echo '<div class="alerta alerta-error">' . $_SESSION['mensaje_error'] . '</div>';
                unset($_SESSION['mensaje_error']);
            }
            if (isset($_SESSION['mensaje_exito'])) {
                echo '<div class="alerta alerta-exito">' . $_SESSION['mensaje_exito'] . '</div>';
                unset($_SESSION['mensaje_exito']);
            }
            ?>
            
            <label for="email">Correo Electrónico</label>
            <input type="email" id="email" name="email" required>
            
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit">Ingresar</button>
            
            <p style="text-align: center; margin-top: 1rem;">
                ¿No tienes una cuenta? 
                <a href="index.php?controller=UsuarioController&action=mostrarRegistro">Regístrate aquí</a>
            </p>
        </form>
    </div>

</body>
</html>