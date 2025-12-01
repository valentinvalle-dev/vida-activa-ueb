<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Vida Activa UEB</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <div class="corner-logo">
        <img src="assets/images/logo.png" alt="Logo Vida Activa UEB">
    </div>

    <div class="auth-container">
        
        <form class="auth-form" action="index.php?controller=UsuarioController&action=registrar" method="POST">

            <div class="auth-form-title">
                <h2>Vida Activa UEB</h2>
            </div>

            <h1>Crear una Cuenta</h1>
            
            <label for="nombre">Nombre Completo</label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="email">Correo Electrónico</label>
            <input type="email" id="email" name="email" required>
            
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit">Registrarse</button>
            
            <p style="text-align: center; margin-top: 1rem;">
                ¿Ya tienes una cuenta? 
                <a href="index.php?controller=UsuarioController&action=mostrarLogin">Inicia sesión</a>
            </p>
        </form>
    </div>

</body>
</html>