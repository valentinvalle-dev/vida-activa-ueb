<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Rutina - Vida Activa UEB</title>
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
            <div class="detalle-header">
                <a href="index.php?controller=RutinaController&action=index" class="btn-volver">
                    ← Volver a Rutinas
                </a>
                <h1><?= htmlspecialchars($rutina['nombre_rutina']) ?></h1>
            </div>

            <div class="detalle-rutina">
                <!-- Información de la Rutina -->
                <div class="info-rutina-box">
                    <h2>📝 Descripción</h2>
                    <p><?= htmlspecialchars($rutina['descripcion']) ?></p>
                    
                    <?php if ($rutina['id_usuario']): ?>
                        <span class="badge badge-personal">Rutina Personalizada</span>
                    <?php else: ?>
                        <span class="badge badge-sistema">Rutina del Sistema</span>
                    <?php endif; ?>
                </div>

                <!-- Lista de Ejercicios -->
                <div class="ejercicios-detalle">
                    <h2>💪 Ejercicios de esta Rutina</h2>
                    
                    <?php if (!empty($ejercicios)): ?>
                        <div class="ejercicios-lista">
                            <?php foreach ($ejercicios as $index => $ejercicio): ?>
                                <div class="ejercicio-detalle-card">
                                    <div class="ejercicio-numero"><?= $index + 1 ?></div>
                                    <div class="ejercicio-info">
                                        <h3><?= htmlspecialchars($ejercicio['nombre_ejercicio']) ?></h3>
                                        <?php if ($ejercicio['descripcion']): ?>
                                            <p class="ejercicio-desc"><?= htmlspecialchars($ejercicio['descripcion']) ?></p>
                                        <?php endif; ?>
                                        
                                        <div class="ejercicio-stats">
                                            <?php if ($ejercicio['series']): ?>
                                                <span class="stat-item">
                                                    <strong>Series:</strong> <?= $ejercicio['series'] ?>
                                                </span>
                                            <?php endif; ?>
                                            
                                            <?php if ($ejercicio['repeticiones']): ?>
                                                <span class="stat-item">
                                                    <strong>Repeticiones:</strong> <?= $ejercicio['repeticiones'] ?>
                                                </span>
                                            <?php endif; ?>
                                            
                                            <?php if ($ejercicio['duracion_min']): ?>
                                                <span class="stat-item">
                                                    <strong>Duración:</strong> <?= $ejercicio['duracion_min'] ?> min
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="sin-ejercicios">Esta rutina aún no tiene ejercicios asignados.</p>
                    <?php endif; ?>
                </div>

                <!-- Acciones -->
                <div class="acciones-detalle">
                    <a href="index.php?controller=PlanificacionController&action=index" 
                       class="btn-accion btn-planificar-detalle">
                        📅 Planificar esta Rutina
                    </a>
                    <a href="index.php?controller=RutinaController&action=index" 
                       class="btn-accion btn-ver-todas">
                        🏋️ Ver Todas las Rutinas
                    </a>
                </div>
            </div>

        </main>
    </div>

</body>
</html>