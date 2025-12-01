<?php
require_once 'config/db.php';
class Ejercicio {
    private $db;

    public function __construct() {
        global $conexion;
        $this->db = $conexion;
    }

    /**
     * Obtiene todos los ejercicios de la base de datos.
     */
    public function getAll() {
        $sql = "SELECT * FROM Ejercicios ORDER BY nombre_ejercicio ASC";
        $resultado = $this->db->query($sql);

        $ejercicios = [];
        if ($resultado) {
            while ($fila = $resultado->fetch_object()) {
                $ejercicios[] = $fila;
            }
        }
        return $ejercicios;
    }
}
?>