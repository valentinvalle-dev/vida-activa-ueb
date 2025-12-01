<?php

class Planificacion {
    private $db;
    private $id_planificacion;
    private $id_usuario;
    private $id_rutina;
    private $fecha_planificada;
    private $completado;

    public function __construct() {
        global $conexion;
        $this->db = $conexion;
    }

    // --- GETTERS Y SETTERS ---
    
    public function getIdPlanificacion() {
        return $this->id_planificacion;
    }

    public function setIdPlanificacion($id_planificacion) {
        $this->id_planificacion = $id_planificacion;
    }

    public function getIdUsuario() {
        return $this->id_usuario;
    }

    public function setIdUsuario($id_usuario) {
        $this->id_usuario = $id_usuario;
    }

    public function getIdRutina() {
        return $this->id_rutina;
    }

    public function setIdRutina($id_rutina) {
        $this->id_rutina = $id_rutina;
    }

    public function getFechaPlanificada() {
        return $this->fecha_planificada;
    }

    public function setFechaPlanificada($fecha_planificada) {
        $this->fecha_planificada = $fecha_planificada;
    }

    public function getCompletado() {
        return $this->completado;
    }

    public function setCompletado($completado) {
        $this->completado = $completado;
    }

    // --- MÉTODOS DE BASE DE DATOS ---

    /**
     * Obtiene todas las planificaciones de un usuario en un mes específico
     * @param int $id_usuario
     * @param int $mes (1-12)
     * @param int $anio
     * @return array
     */
    public function getPlanificacionesMes($id_usuario, $mes, $anio) {
        $id_usuario = (int)$id_usuario;
        $mes = (int)$mes;
        $anio = (int)$anio;

        $sql = "SELECT p.*, r.nombre_rutina, r.descripcion 
                FROM planificacion p
                INNER JOIN rutinas r ON p.id_rutina = r.id_rutina
                WHERE p.id_usuario = $id_usuario 
                AND MONTH(p.fecha_planificada) = $mes 
                AND YEAR(p.fecha_planificada) = $anio
                ORDER BY p.fecha_planificada ASC";

        $query = $this->db->query($sql);
        
        if ($query) {
            return $query->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }

    /**
     * Guarda una nueva planificación
     * @param int $id_usuario
     * @param int $id_rutina
     * @param string $fecha (formato: YYYY-MM-DD)
     * @return bool
     */
    public function guardarPlanificacion($id_usuario, $id_rutina, $fecha) {
        $id_usuario = (int)$id_usuario;
        $id_rutina = (int)$id_rutina;
        $fecha = $this->db->real_escape_string($fecha);

        // Verificar que no exista ya una planificación para esa fecha y rutina
        $sql_check = "SELECT id_planificacion FROM planificacion 
                      WHERE id_usuario = $id_usuario 
                      AND id_rutina = $id_rutina 
                      AND fecha_planificada = '$fecha'";
        
        $existe = $this->db->query($sql_check);
        
        if ($existe && $existe->num_rows > 0) {
            return false; // Ya existe
        }

        $sql = "INSERT INTO planificacion (id_usuario, id_rutina, fecha_planificada, completado) 
                VALUES ($id_usuario, $id_rutina, '$fecha', 0)";
        
        return $this->db->query($sql);
    }

    /**
     * Elimina una planificación específica
     * @param int $id_planificacion
     * @param int $id_usuario (para seguridad)
     * @return bool
     */
    public function eliminarPlanificacion($id_planificacion, $id_usuario) {
        $id_planificacion = (int)$id_planificacion;
        $id_usuario = (int)$id_usuario;

        $sql = "DELETE FROM planificacion 
                WHERE id_planificacion = $id_planificacion 
                AND id_usuario = $id_usuario";
        
        return $this->db->query($sql);
    }

    /**
     * Marca una rutina como completada o no completada
     * @param int $id_planificacion
     * @param int $id_usuario
     * @param int $completado (0 o 1)
     * @return bool
     */
    public function marcarCompletada($id_planificacion, $id_usuario, $completado = 1) {
        $id_planificacion = (int)$id_planificacion;
        $id_usuario = (int)$id_usuario;
        $completado = (int)$completado;

        $sql = "UPDATE planificacion 
                SET completado = $completado 
                WHERE id_planificacion = $id_planificacion 
                AND id_usuario = $id_usuario";
        
        return $this->db->query($sql);
    }

    /**
     * Obtiene todas las rutinas disponibles para un usuario (del sistema + propias)
     * @param int $id_usuario
     * @return array
     */
    public function getRutinasDisponibles($id_usuario) {
        $id_usuario = (int)$id_usuario;

        $sql = "SELECT id_rutina, nombre_rutina, descripcion 
                FROM rutinas 
                WHERE id_usuario IS NULL OR id_usuario = $id_usuario
                ORDER BY nombre_rutina ASC";

        $query = $this->db->query($sql);
        
        if ($query) {
            return $query->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }

    /**
     * Obtiene el detalle de una planificación específica
     * @param int $id_planificacion
     * @param int $id_usuario
     * @return array|null
     */
    public function getDetallePlanificacion($id_planificacion, $id_usuario) {
        $id_planificacion = (int)$id_planificacion;
        $id_usuario = (int)$id_usuario;

        $sql = "SELECT p.*, r.nombre_rutina, r.descripcion 
                FROM planificacion p
                INNER JOIN rutinas r ON p.id_rutina = r.id_rutina
                WHERE p.id_planificacion = $id_planificacion 
                AND p.id_usuario = $id_usuario";

        $query = $this->db->query($sql);
        
        if ($query && $query->num_rows > 0) {
            return $query->fetch_assoc();
        }
        return null;
    }
}
?>