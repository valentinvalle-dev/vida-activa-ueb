<?php
class Usuario {
    // Propiedades del modelo
    private $id_usuario;
    private $nombre;
    private $email;
    private $password;
    private $db; // Para guardar el objeto de la conexión a la BD

    public function __construct() {
        // Hacemos que la conexión a la BD esté disponible para el modelo
        global $conexion;
        $this->db = $conexion;
    }
    
    // --- Getters y Setters ---
    function getIdUsuario() {
        return $this->id_usuario;
    }

    function getNombre() {
        return $this->nombre;
    }

    function getEmail() {
        return $this->email;
    }

    function getPassword() {
        return $this->password;
    }

    function setNombre($nombre) {
        $this->nombre = $this->db->real_escape_string($nombre);
    }

    function setEmail($email) {
        $this->email = $this->db->real_escape_string($email);
    }

    function setPassword($password) {
        // Encriptamos la contraseña por seguridad antes de guardarla
        $this->password = password_hash($this->db->real_escape_string($password), PASSWORD_BCRYPT, ['cost' => 4]);
    }

    // --- Métodos de la Base de Datos ---
    public function guardar() {
        $sql = "INSERT INTO Usuarios (nombre, email, password) VALUES ('{$this->getNombre()}', '{$this->getEmail()}', '{$this->getPassword()}')";
        $guardado = $this->db->query($sql);
        return $guardado;
    }

    public function login($password) {
        $email = $this->email;
        
        // --- LÍNEA CORREGIDA ---
        $sql = "SELECT * FROM Usuarios WHERE email = '$email'";
        // -----------------------

        $login_query = $this->db->query($sql);

        if ($login_query && $login_query->num_rows == 1) {
            $usuario = $login_query->fetch_object();

            // Verificamos la contraseña encriptada
            $verify = password_verify($password, $usuario->password);

            if ($verify) {
                return $usuario; // Devolvemos el objeto del usuario
            }
        }
        return false; // Si algo falla, devolvemos false
    }
}
?>