<?php
// NO necesitas cargar db.php aquí porque ya se cargó en index.php

class Rutina {
    private $db;
    private $id_rutina;
    private $nombre_rutina;
    private $descripcion;
    private $id_usuario;

    public function __construct() {
        // Usamos la variable global que creó db.php
        global $conexion;
        $this->db = $conexion;
    }
    
    // --- Getters y Setters ---
    function getIdRutina() {
        return $this->id_rutina;
    }

    function getNombreRutina() {
        return $this->nombre_rutina;
    }

    function getDescripcion() {
        return $this->descripcion;
    }

    function getIdUsuario() {
        return $this->id_usuario;
    }

    function setIdRutina($id_rutina) {
        $this->id_rutina = $id_rutina;
    }

    function setNombreRutina($nombre_rutina) {
        $this->nombre_rutina = $this->db->real_escape_string($nombre_rutina);
    }

    function setDescripcion($descripcion) {
        $this->descripcion = $this->db->real_escape_string($descripcion);
    }

    function setIdUsuario($id_usuario) {
        $this->id_usuario = $id_usuario;
    }

    // --- MÉTODOS DE LA BASE DE DATOS ---

    public function getRutinasSistema() {
        $sql = "SELECT * FROM rutinas WHERE id_usuario IS NULL ORDER BY nombre_rutina ASC";
        $query = $this->db->query($sql);
        return $query->fetch_all(MYSQLI_ASSOC);
    }

    public function getRutinasUsuario($id_usuario) {
        $id_usuario = (int)$id_usuario; 
        $sql = "SELECT * FROM rutinas WHERE id_usuario = $id_usuario ORDER BY nombre_rutina ASC";
        $query = $this->db->query($sql);
        return $query->fetch_all(MYSQLI_ASSOC);
    }

    public function guardarConEjercicios($nombre, $descripcion, $id_usuario, $ejercicios) {
        $this->db->begin_transaction();

        try {
            // 1. Guardar la rutina principal
            $sql = "INSERT INTO rutinas (nombre_rutina, descripcion, id_usuario) VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("ssi", $nombre, $descripcion, $id_usuario);
            
            if (!$stmt->execute()) {
                throw new Exception("Error al guardar la rutina.");
            }

            // 2. Obtener el ID de la rutina que acabamos de insertar
            $id_rutina_nueva = $this->db->insert_id;

            // 3. Preparar la consulta para los detalles
            $sql_detalle = "INSERT INTO rutina_detalle (id_rutina, id_ejercicio, series, repeticiones, duracion_min) VALUES (?, ?, ?, ?, ?)";
            $stmt_detalle = $this->db->prepare($sql_detalle);

            // 4. Iterar y guardar cada ejercicio
            foreach ($ejercicios as $ej) {
                $id_ej = (int)$ej['id_ejercicio'];
                $series = (int)$ej['series'];
                $rep = $this->db->real_escape_string($ej['repeticiones']);
                $duracion = (int)$ej['duracion_min'];

                $stmt_detalle->bind_param("iissi", $id_rutina_nueva, $id_ej, $series, $rep, $duracion);
                
                if (!$stmt_detalle->execute()) {
                    throw new Exception("Error al guardar los detalles de la rutina.");
                }
            }
            
            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
    }
}
?>