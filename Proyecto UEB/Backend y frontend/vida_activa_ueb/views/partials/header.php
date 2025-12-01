<?php
// --- GUARDIA DE SEGURIDAD ---
// La sesión ya se inicia en index.php, aquí solo la usamos.
// Si no existe la sesión del usuario, lo redirigimos al login.
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php?controller=UsuarioController&action=mostrarLogin");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Vida Activa UEB</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="layout">
    <aside class="sidebar">
        <h3>Vida Activa UEB</h3>
        <nav class="menu">
            <ul>
                <li><a href="index.php?controller=DashboardController&action=index">Inicio</a></li>
                
                <li><a href="index.php?controller=RutinaController&action=index">Mis Rutinas</a></li>
                
                <li><a href="#">Calendario</a></li>
                <li><a href="#">Progreso</a></li>
                
                <li><a href="index.php?controller=UsuarioController&action=logout">Cerrar Sesión</a></li>
            </ul>
        </nav>
    </aside>

    <main class="main-content">