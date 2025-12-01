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

        <!-- Contenido Principal -->
        <main class="main-content">
            <h1>¡Bienvenido, <?= htmlspecialchars($nombre_usuario) ?>! 👋</h1>
            <p class="dashboard-subtitle">Aquí tienes un resumen de tu actividad física</p>

            <!-- Tarjetas de Estadísticas -->
            <div class="dashboard-stats">
                
                <!-- Tarjeta 1: Rutinas Creadas -->
                <div class="stat-card stat-card-blue">
                    <div class="stat-icon">📋</div>
                    <div class="stat-info">
                        <h3><?= $total_rutinas ?></h3>
                        <p>Rutinas Personalizadas</p>
                    </div>
                </div>

                <!-- Tarjeta 2: Rutinas del Mes -->
                <div class="stat-card stat-card-yellow">
                    <div class="stat-icon">📅</div>
                    <div class="stat-info">
                        <h3><?= $total_planificadas ?></h3>
                        <p>Planificadas este Mes</p>
                    </div>
                </div>

                <!-- Tarjeta 3: Rutinas Completadas -->
                <div class="stat-card stat-card-green">
                    <div class="stat-icon">✅</div>
                    <div class="stat-info">
                        <h3><?= $completadas ?></h3>
                        <p>Completadas este Mes</p>
                    </div>
                </div>

            </div>

            <!-- Próxima Rutina -->
            <?php if ($proxima_rutina): ?>
                <div class="proxima-rutina">
                    <h2>🎯 Próxima Rutina Planificada</h2>
                    <div class="proxima-rutina-card">
                        <div class="proxima-info">
                            <h3><?= htmlspecialchars($proxima_rutina['nombre_rutina']) ?></h3>
                            <p><?= htmlspecialchars($proxima_rutina['descripcion']) ?></p>
                            <p class="fecha-proxima">
                                📆 <?= date('d/m/Y', strtotime($proxima_rutina['fecha_planificada'])) ?>
                            </p>
                        </div>
                        <div class="proxima-acciones">
                            <a href="index.php?controller=PlanificacionController&action=completar&id=<?= $proxima_rutina['id_planificacion'] ?>" 
                               class="btn-completar-dashboard">
                                Marcar como Completada
                            </a>
                            <a href="index.php?controller=PlanificacionController&action=index" 
                               class="btn-ver-calendario">
                                Ver Calendario Completo
                            </a>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="sin-rutinas">
                    <h2>📝 No tienes rutinas planificadas próximamente</h2>
                    <p>¡Organiza tu semana de entrenamiento!</p>
                    <a href="index.php?controller=PlanificacionController&action=index" class="btn-ir-calendario">
                        Ir al Calendario
                    </a>
                </div>
            <?php endif; ?>

            <!-- Accesos Rápidos -->
            <div class="accesos-rapidos">
                <h2>⚡ Accesos Rápidos</h2>
                <div class="accesos-grid">
                    <a href="index.php?controller=RutinaController&action=crear" class="acceso-card">
                        <span class="acceso-icon">➕</span>
                        <span>Crear Rutina</span>
                    </a>
                    <a href="index.php?controller=PlanificacionController&action=index" class="acceso-card">
                        <span class="acceso-icon">📅</span>
                        <span>Ver Calendario</span>
                    </a>
                    <a href="index.php?controller=RutinaController&action=index" class="acceso-card">
                        <span class="acceso-icon">🏋️</span>
                        <span>Mis Rutinas</span>
                    </a>
                    <a href="index.php?controller=ProgresosController&action=index" class="acceso-card">
                        <span class="acceso-icon">📊</span>
                        <span>Ver Progreso</span>
                    </a>
                </div>
            </div>

        </main>
    </div>

</body>
</html>