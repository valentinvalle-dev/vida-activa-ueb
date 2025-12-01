<?php
require_once 'models/Planificacion.php';
require_once 'models/Rutina.php';

class PlanificacionController {

    /**
     * Muestra el calendario principal
     */
    public function index() {
        // Verificar sesión
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?controller=UsuarioController&action=mostrarLogin");
            exit();
        }

        $id_usuario = $_SESSION['usuario_id'];
        
        // Obtener el mes y año actual (o el que venga por parámetro)
        $mes = isset($_GET['mes']) ? (int)$_GET['mes'] : date('n');
        $anio = isset($_GET['anio']) ? (int)$_GET['anio'] : date('Y');

        // Validar mes y año
        if ($mes < 1 || $mes > 12) $mes = date('n');
        if ($anio < 2020 || $anio > 2030) $anio = date('Y');

        // Crear instancia del modelo
        $planificacionModel = new Planificacion();

        // Obtener las planificaciones del mes
        $planificaciones = $planificacionModel->getPlanificacionesMes($id_usuario, $mes, $anio);

        // Obtener todas las rutinas disponibles para el modal
        $rutinas = $planificacionModel->getRutinasDisponibles($id_usuario);

        // Cargar la vista
        require_once 'views/planificacion/index.php';
    }

    /**
     * Guarda una nueva planificación
     */
    public function guardar() {
        // Verificar sesión
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?controller=UsuarioController&action=mostrarLogin");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_usuario = $_SESSION['usuario_id'];
            $id_rutina = isset($_POST['id_rutina']) ? (int)$_POST['id_rutina'] : 0;
            $fecha = isset($_POST['fecha']) ? $_POST['fecha'] : '';

            // Validar datos
            if ($id_rutina > 0 && !empty($fecha)) {
                $planificacionModel = new Planificacion();
                $resultado = $planificacionModel->guardarPlanificacion($id_usuario, $id_rutina, $fecha);

                if ($resultado) {
                    $_SESSION['mensaje_exito'] = "¡Rutina planificada correctamente!";
                } else {
                    $_SESSION['mensaje_error'] = "Error: Ya existe una rutina planificada para esa fecha.";
                }
            } else {
                $_SESSION['mensaje_error'] = "Error: Datos incompletos.";
            }

            // Redirigir al calendario
            header("Location: index.php?controller=PlanificacionController&action=index");
            exit();
        }
    }

    /**
     * Elimina una planificación
     */
    public function eliminar() {
        // Verificar sesión
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?controller=UsuarioController&action=mostrarLogin");
            exit();
        }

        $id_planificacion = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $id_usuario = $_SESSION['usuario_id'];

        if ($id_planificacion > 0) {
            $planificacionModel = new Planificacion();
            $resultado = $planificacionModel->eliminarPlanificacion($id_planificacion, $id_usuario);

            if ($resultado) {
                $_SESSION['mensaje_exito'] = "Planificación eliminada correctamente.";
            } else {
                $_SESSION['mensaje_error'] = "Error al eliminar la planificación.";
            }
        }

        // Redirigir al calendario
        header("Location: index.php?controller=PlanificacionController&action=index");
        exit();
    }

    /**
     * Marca una rutina como completada
     */
    public function completar() {
        // Verificar sesión
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?controller=UsuarioController&action=mostrarLogin");
            exit();
        }

        $id_planificacion = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $id_usuario = $_SESSION['usuario_id'];

        if ($id_planificacion > 0) {
            $planificacionModel = new Planificacion();
            $resultado = $planificacionModel->marcarCompletada($id_planificacion, $id_usuario, 1);

            if ($resultado) {
                $_SESSION['mensaje_exito'] = "¡Rutina marcada como completada!";
            } else {
                $_SESSION['mensaje_error'] = "Error al marcar como completada.";
            }
        }

        // Redirigir al calendario
        header("Location: index.php?controller=PlanificacionController&action=index");
        exit();
    }
}
?>