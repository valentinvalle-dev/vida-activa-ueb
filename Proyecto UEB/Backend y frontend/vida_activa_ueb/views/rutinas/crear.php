<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Rutina - Vida Activa UEB</title>
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
            <h1>Crear Nueva Rutina</h1>

            <form class="form-crear" action="index.php?controller=RutinaController&action=guardar" method="POST">
                
                <label for="nombre_rutina">Nombre de la Rutina:</label>
                <input type="text" id="nombre_rutina" name="nombre_rutina" required 
                       placeholder="Ej: Rutina de Piernas">

                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" required 
                          placeholder="Describe brevemente esta rutina..."></textarea>

                <!-- Selector de Ejercicios -->
                <div class="selector-ejercicio">
                    <h3>Agregar Ejercicios</h3>
                    
                    <select id="select-ejercicio">
                        <option value="">-- Selecciona un ejercicio --</option>
                        <?php foreach ($ejercicios as $ej): ?>
                            <option value="<?= $ej->id_ejercicio ?>" data-nombre="<?= htmlspecialchars($ej->nombre_ejercicio) ?>">
                                <?= htmlspecialchars($ej->nombre_ejercicio) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <button type="button" class="btn-agregar-ejercicio" onclick="agregarEjercicio()">
                        Agregar Ejercicio
                    </button>

                    <div id="ejercicios-agregados"></div>
                </div>

                <button type="submit">Guardar Rutina</button>
            </form>
        </main>
    </div>

    <script src="assets/js/rutinas.js"></script>
</body>
</html>