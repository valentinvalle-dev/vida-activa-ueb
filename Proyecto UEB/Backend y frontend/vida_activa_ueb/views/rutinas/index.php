<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Rutinas - Vida Activa UEB</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <div class="layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <h3>Vida Activa UEB</h3>
            <nav class="menu">
                <ul>
                    <li><a href="index.php?controller=DashboardController&action=index">Inicio</a></li>
                    <li><a href="index.php?controller=RutinaController&action=index">Rutinas</a></li>
                    <li><a href="index.php?controller=PlanificacionController&action=index">Calendario</a></li>
                    <li><a href="index.php?controller=ProgresosController&action=index">Progresos</a></li>
                    <li><a href="index.php?controller=UsuarioController&action=logout">Cerrar Sesión</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Contenido principal -->
        <main class="main-content">
            <h1>Gestión de Rutinas</h1>

            <?php
            // Mostrar mensajes de éxito o error
            if (isset($_SESSION['mensaje_exito'])) {
                echo '<div class="alerta alerta-exito">' . $_SESSION['mensaje_exito'] . '</div>';
                unset($_SESSION['mensaje_exito']);
            }
            if (isset($_SESSION['mensaje_error'])) {
                echo '<div class="alerta alerta-error">' . $_SESSION['mensaje_error'] . '</div>';
                unset($_SESSION['mensaje_error']);
            }
            ?>

            <!-- Rutinas del Sistema -->
            <h2>Rutinas Predefinidas del Sistema</h2>
            <div class="rutinas-grid">
                <?php if (!empty($rutinas_sistema)): ?>
                    <?php foreach ($rutinas_sistema as $rutina): ?>
                        <div class="rutina-card">
                            <div class="card-content">
                                <h3><?= htmlspecialchars($rutina['nombre_rutina']) ?></h3>
                                <p><?= htmlspecialchars($rutina['descripcion']) ?></p>
                                
                                <!-- Botones de acción -->
                                <div class="card-actions">
                                    <a href="index.php?controller=RutinaController&action=verDetalle&id=<?= $rutina['id_rutina'] ?>" 
                                       class="btn-card btn-ver">
                                        📋 Ver Detalle
                                    </a>
                                    <a href="index.php?controller=PlanificacionController&action=index&rutina=<?= $rutina['id_rutina'] ?>" 
                                       class="btn-card btn-planificar">
                                        📅 Planificar
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No hay rutinas del sistema disponibles.</p>
                <?php endif; ?>
            </div>

            <!-- Rutinas del Usuario -->
            <h2 style="margin-top: 3rem;">Mis Rutinas Personalizadas</h2>
            <div class="rutinas-grid">
                <?php if (!empty($rutinas_usuario)): ?>
                    <?php foreach ($rutinas_usuario as $rutina): ?>
                        <div class="rutina-card">
                            <div class="card-content">
                                <h3><?= htmlspecialchars($rutina['nombre_rutina']) ?></h3>
                                <p><?= htmlspecialchars($rutina['descripcion']) ?></p>
                                
                                <!-- Botones de acción -->
                                <div class="card-actions">
                                    <a href="index.php?controller=RutinaController&action=verDetalle&id=<?= $rutina['id_rutina'] ?>" 
                                       class="btn-card btn-ver">
                                        📋 Ver Detalle
                                    </a>
                                    <a href="index.php?controller=PlanificacionController&action=index&rutina=<?= $rutina['id_rutina'] ?>" 
                                       class="btn-card btn-planificar">
                                        📅 Planificar
                                    </a>
                                    <a href="index.php?controller=RutinaController&action=eliminar&id=<?= $rutina['id_rutina'] ?>" 
                                       class="btn-card btn-eliminar"
                                       onclick="return confirm('¿Eliminar esta rutina?')">
                                        🗑️ Eliminar
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Aún no has creado rutinas personalizadas.</p>
                <?php endif; ?>
            </div>

            <!-- Botón para crear nueva rutina -->
            <a href="index.php?controller=RutinaController&action=crear" class="btn-crear-rutina">
                + Crear Nueva Rutina
            </a>
        </main>
    </div>

</body>
</html>