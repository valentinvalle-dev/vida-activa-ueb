<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Calendario de Entrenamientos - Vida Activa UEB</title>
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
            <h1>📅 Mi Calendario de Entrenamientos</h1>

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

            <!-- Navegación del Calendario -->
            <div class="calendario-nav">
                <?php
                // Calcular mes anterior y siguiente
                $mes_anterior = $mes - 1;
                $anio_anterior = $anio;
                if ($mes_anterior < 1) {
                    $mes_anterior = 12;
                    $anio_anterior--;
                }

                $mes_siguiente = $mes + 1;
                $anio_siguiente = $anio;
                if ($mes_siguiente > 12) {
                    $mes_siguiente = 1;
                    $anio_siguiente++;
                }

                // Array de nombres de meses en español
                $nombres_meses = [
                    1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
                    5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
                    9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
                ];
                ?>

                <a href="index.php?controller=PlanificacionController&action=index&mes=<?= $mes_anterior ?>&anio=<?= $anio_anterior ?>" 
                   class="btn-nav-calendario">← Anterior</a>
                
                <h2 class="mes-actual"><?= $nombres_meses[$mes] ?> <?= $anio ?></h2>
                
                <a href="index.php?controller=PlanificacionController&action=index&mes=<?= $mes_siguiente ?>&anio=<?= $anio_siguiente ?>" 
                   class="btn-nav-calendario">Siguiente →</a>
            </div>

            <!-- Botón para agregar rutina -->
            <button class="btn-agregar-rutina" onclick="mostrarModal()">
                + Planificar Rutina
            </button>

            <!-- El Calendario -->
            <div class="calendario-container">
                <div class="calendario-grid">
                    <!-- Encabezados de días de la semana -->
                    <div class="dia-semana">Dom</div>
                    <div class="dia-semana">Lun</div>
                    <div class="dia-semana">Mar</div>
                    <div class="dia-semana">Mié</div>
                    <div class="dia-semana">Jue</div>
                    <div class="dia-semana">Vie</div>
                    <div class="dia-semana">Sáb</div>

                    <?php
                    // Calcular el primer día del mes y total de días
                    $primer_dia = date('w', strtotime("$anio-$mes-01")); // 0=Domingo, 6=Sábado
                    $total_dias = date('t', strtotime("$anio-$mes-01"));
                    
                    // Organizar planificaciones por día
                    $planificaciones_por_dia = [];
                    foreach ($planificaciones as $plan) {
                        $dia = (int)date('j', strtotime($plan['fecha_planificada']));
                        if (!isset($planificaciones_por_dia[$dia])) {
                            $planificaciones_por_dia[$dia] = [];
                        }
                        $planificaciones_por_dia[$dia][] = $plan;
                    }

                    // Espacios vacíos antes del primer día
                    for ($i = 0; $i < $primer_dia; $i++) {
                        echo '<div class="dia-vacio"></div>';
                    }

                    // Días del mes
                    $hoy = date('Y-m-d');
                    for ($dia = 1; $dia <= $total_dias; $dia++) {
                        $fecha_actual = "$anio-" . str_pad($mes, 2, '0', STR_PAD_LEFT) . "-" . str_pad($dia, 2, '0', STR_PAD_LEFT);
                        $es_hoy = ($fecha_actual == $hoy) ? 'dia-hoy' : '';
                        $tiene_rutinas = isset($planificaciones_por_dia[$dia]) ? 'dia-con-rutina' : '';
                        
                        echo '<div class="dia-mes ' . $es_hoy . ' ' . $tiene_rutinas . '">';
                        echo '<div class="numero-dia">' . $dia . '</div>';
                        
                        // Mostrar rutinas planificadas para este día
                        if (isset($planificaciones_por_dia[$dia])) {
                            echo '<div class="rutinas-del-dia">';
                            foreach ($planificaciones_por_dia[$dia] as $plan) {
                                $clase_completado = $plan['completado'] ? 'rutina-completada' : 'rutina-pendiente';
                                echo '<div class="rutina-item-calendario ' . $clase_completado . '">';
                                echo '<span class="icono-estado">' . ($plan['completado'] ? '✓' : '○') . '</span>';
                                echo '<span class="nombre-rutina-cal">' . htmlspecialchars($plan['nombre_rutina']) . '</span>';
                                
                                // Botones de acción
                                echo '<div class="acciones-rutina">';
                                if (!$plan['completado']) {
                                    echo '<a href="index.php?controller=PlanificacionController&action=completar&id=' . $plan['id_planificacion'] . '" 
                                          class="btn-completar" title="Marcar como completada">✓</a>';
                                }
                                echo '<a href="index.php?controller=PlanificacionController&action=eliminar&id=' . $plan['id_planificacion'] . '" 
                                      class="btn-eliminar-mini" 
                                      onclick="return confirm(\'¿Eliminar esta planificación?\')" 
                                      title="Eliminar">×</a>';
                                echo '</div>';
                                
                                echo '</div>';
                            }
                            echo '</div>';
                        }
                        
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>

            <!-- Leyenda -->
            <div class="calendario-leyenda">
                <div class="leyenda-item">
                    <span class="leyenda-color leyenda-hoy"></span>
                    <span>Hoy</span>
                </div>
                <div class="leyenda-item">
                    <span class="leyenda-color leyenda-con-rutina"></span>
                    <span>Día con rutinas</span>
                </div>
                <div class="leyenda-item">
                    <span class="icono-estado">✓</span>
                    <span>Completada</span>
                </div>
                <div class="leyenda-item">
                    <span class="icono-estado">○</span>
                    <span>Pendiente</span>
                </div>
            </div>

        </main>
    </div>

    <!-- MODAL PARA AGREGAR RUTINA -->
    <div id="modalAgregarRutina" class="modal">
        <div class="modal-contenido">
            <span class="cerrar-modal" onclick="cerrarModal()">&times;</span>
            <h2>Planificar Rutina</h2>
            
            <form action="index.php?controller=PlanificacionController&action=guardar" method="POST">
                <label for="fecha">Fecha:</label>
                <input type="date" id="fecha" name="fecha" required 
                       min="<?= date('Y-m-d') ?>" 
                       value="<?= date('Y-m-d') ?>">

                <label for="id_rutina">Selecciona una rutina:</label>
                <select id="id_rutina" name="id_rutina" required>
                    <option value="">-- Elige una rutina --</option>
                    <?php foreach ($rutinas as $rutina): ?>
                        <option value="<?= $rutina['id_rutina'] ?>">
                            <?= htmlspecialchars($rutina['nombre_rutina']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <button type="submit" class="btn-guardar-modal">Guardar Planificación</button>
            </form>
        </div>
    </div>

    <script src="assets/js/calendario.js"></script>
</body>
</html>