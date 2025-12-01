<?php

// Configuración de la conexión a la base de datos
$servidor = 'localhost'; // o 127.0.0.1, es la dirección de tu servidor local
$usuario = 'root';       // El usuario por defecto de MySQL en XAMPP
$password = '';          // La contraseña por defecto está vacía en XAMPP
$db = 'vida_activa_ueb_db'; // El nombre de nuestra base de datos

// Crear la conexión utilizando la clase mysqli
$conexion = new mysqli($servidor, $usuario, $password, $db);

// --- Verificación de la Conexión ---
// Esta es una buena práctica para asegurarnos de que todo funcionó.
if ($conexion->connect_error) {
    // Si hay un error, el script se detiene y muestra el mensaje de error.
    die('Error de Conexión (' . $conexion->connect_errno . ') ' . $conexion->connect_error);
}

// Opcional: Para probar que la conexión funciona, puedes descomentar la siguiente línea temporalmente.
// Si la ves en el navegador, significa que todo está bien. Luego vuelve a comentarla.
// echo "¡Conexión exitosa a la base de datos!";

// Establecer el juego de caracteres a UTF-8 para evitar problemas con acentos y ñ
$conexion->set_charset('utf8');

?>