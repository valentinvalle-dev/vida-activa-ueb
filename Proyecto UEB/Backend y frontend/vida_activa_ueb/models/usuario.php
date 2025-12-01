<?php

class Usuario {
    private $db;
    private $id_usuario;
    private $nombre;
    private $email;
    private $password;

    public function __construct() {
        global $conexion;
        $this->db = $conexion;
    }

    // --- GETTERS Y SETTERS ---
    
    public function getIdUsuario() {
        return $this->id_usuario;
    }

    public function setIdUsuario($id_usuario) {
        $this->id_usuario = $id_usuario;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function setNombre($nombre) {
        $this->nombre = $this->db->real_escape_string($nombre);
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $this->db->real_escape_string($email);
    }

    public function setPassword($password) {
        // Encriptar la contraseña con password_hash
        $this->password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 4]);
    }

    // --- MÉTODOS DE BASE DE DATOS ---

    /**
     * Registra un nuevo usuario
     */
    public function guardar() {
        $sql = "INSERT INTO usuarios (nombre, email, password) 
                VALUES ('{$this->nombre}', '{$this->email}', '{$this->password}')";
        
        $guardar = $this->db->query($sql);
        
        return $guardar ? true : false;
    }

    /**
     * Valida las credenciales del usuario para el login
     */
    public function login($email, $password) {
        // Escapar el email
        $email = $this->db->real_escape_string($email);
        
        // Buscar el usuario por email
        $sql = "SELECT * FROM usuarios WHERE email = '$email'";
        $resultado = $this->db->query($sql);
        
        if ($resultado && $resultado->num_rows == 1) {
            $usuario = $resultado->fetch_assoc();
            
            // Verificar la contraseña
            // Usar password_verify() para las contraseñas encriptadas con password_hash()
            if (password_verify($password, $usuario['password'])) {
                return $usuario; // Login exitoso
            }
        }
        
        return false; // Login fallido
    }

    /**
     * Verifica si un email ya está registrado
     */
    public function existeEmail($email) {
        $email = $this->db->real_escape_string($email);
        $sql = "SELECT id_usuario FROM usuarios WHERE email = '$email'";
        $resultado = $this->db->query($sql);
        
        return ($resultado && $resultado->num_rows > 0);
    }
}
?>