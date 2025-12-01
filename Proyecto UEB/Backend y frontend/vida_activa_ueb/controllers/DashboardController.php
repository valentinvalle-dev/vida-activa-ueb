<?php
require_once 'models/Rutina.php';
require_once 'models/Planificacion.php';

class DashboardController {

    public function index() {
        // Verificar sesión
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?controller=UsuarioController&action=mostrarLogin");
            exit();
        }

        $id_usuario = $_SESSION['usuario_id'];
        $nombre_usuario = isset($_SESSION['usuario_nombre']) ? $_SESSION['usuario_nombre'] : 'Usuario';

        // Obtener estadísticas del usuario
        $rutinaModel = new Rutina();
        $planificacionModel = new Planificacion();

        // Total de rutinas personalizadas
        $rutinas_usuario = $rutinaModel->getRutinasUsuario($id_usuario);
        $total_rutinas = count($rutinas_usuario);

        // Rutinas planificadas este mes
        $mes_actual = date('n');
        $anio_actual = date('Y');
        $planificaciones_mes = $planificacionModel->getPlanificacionesMes($id_usuario, $mes_actual, $anio_actual);
        $total_planificadas = count($planificaciones_mes);

        // Rutinas completadas este mes
        $completadas = 0;
        foreach ($planificaciones_mes as $plan) {
            if ($plan['completado'] == 1) {
                $completadas++;
            }
        }

        // Próxima rutina planificada
        $proxima_rutina = null;
        $hoy = date('Y-m-d');
        foreach ($planificaciones_mes as $plan) {
            if ($plan['fecha_planificada'] >= $hoy && $plan['completado'] == 0) {
                $proxima_rutina = $plan;
                break;
            }
        }

        // Cargar la vista
        require_once 'views/dashboard/index.php';
    }
}
?>