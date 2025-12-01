<?php
// Habilitamos la visualización de errores para depuración
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Iniciamos la sesión para que esté disponible en toda la aplicación
session_start();

// Incluimos una sola vez la conexión a la base de datos
require_once 'config/db.php';

require_once 'models/Usuario.php'; 

// --- ENRUTADOR PRINCIPAL ---

// Definimos el controlador y la acción por defecto
$controlador = 'UsuarioController';
$accion = 'mostrarRegistro'; // Por defecto, muestra el registro

// Si el usuario ya inició sesión, lo mandamos al dashboard
if (isset($_SESSION['identidad'])) {
    $controlador = 'DashboardController';
    $accion = 'index';
}

// Verificamos si la URL nos pide un controlador específico
if (isset($_GET['controller'])) {
    $controlador = $_GET['controller'];
}

// Verificamos si la URL nos pide una acción específica
if (isset($_GET['action'])) {
    $accion = $_GET['action'];
}

// Construimos la ruta al archivo del controlador
$rutaControlador = 'controllers/' . $controlador . '.php';

// Verificamos si el archivo del controlador existe
if (file_exists($rutaControlador)) {
    require_once $rutaControlador;

    // Creamos una instancia del controlador
    $controladorObj = new $controlador();

    // Verificamos si la acción (el método) existe en esa clase
    if (method_exists($controladorObj, $accion)) {
        // Llamamos al método
        $controladorObj->$accion();
    } else {
        echo "Error: La acción que buscas no existe.";
    }
} else {
    echo "Error: El controlador que buscas no existe.";
}
?>