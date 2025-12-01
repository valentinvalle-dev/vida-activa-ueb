<?php
require_once 'models/Rutina.php';
require_once 'models/Ejercicio.php';

class RutinaController {

    public function index() {
        // Ya NO necesitas estas líneas porque index.php ya inició la sesión
        // if (session_status() == PHP_SESSION_NONE) {
        //     session_start();
        // }
        
        // 2. Verificamos si el usuario está logueado
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?controller=UsuarioController&action=mostrarLogin");
            exit();
        }
        
        // 3. Crear una instancia del modelo
        $rutinaModel = new Rutina();
        
        // 4. Obtener las rutinas del sistema
        $rutinas_sistema = $rutinaModel->getRutinasSistema();
        
        // 5. Obtener las rutinas del usuario logueado
        $id_usuario = $_SESSION['usuario_id'];
        $rutinas_usuario = $rutinaModel->getRutinasUsuario($id_usuario);
        
        // 6. Cargar la vista y pasarle los dos arrays de datos
        require_once 'views/rutinas/index.php';
    }
/**
 * Muestra el detalle completo de una rutina con sus ejercicios
 */
public function verDetalle() {
    // Verificar sesión
    if (!isset($_SESSION['usuario_id'])) {
        header("Location: index.php?controller=UsuarioController&action=mostrarLogin");
        exit();
    }

    // Obtener el ID de la rutina
    $id_rutina = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    if ($id_rutina <= 0) {
        $_SESSION['mensaje_error'] = "Rutina no encontrada.";
        header("Location: index.php?controller=RutinaController&action=index");
        exit();
    }

    // Obtener los datos de la rutina
    global $conexion;
    
    $sql = "SELECT * FROM rutinas WHERE id_rutina = $id_rutina";
    $resultado = $conexion->query($sql);
    
    if (!$resultado || $resultado->num_rows == 0) {
        $_SESSION['mensaje_error'] = "Rutina no encontrada.";
        header("Location: index.php?controller=RutinaController&action=index");
        exit();
    }
    
    $rutina = $resultado->fetch_assoc();

    // Obtener los ejercicios de la rutina
    $sql_ejercicios = "SELECT e.nombre_ejercicio, e.descripcion, rd.series, rd.repeticiones, rd.duracion_min
                       FROM rutina_detalle rd
                       INNER JOIN ejercicios e ON rd.id_ejercicio = e.id_ejercicio
                       WHERE rd.id_rutina = $id_rutina";
    
    $resultado_ejercicios = $conexion->query($sql_ejercicios);
    $ejercicios = [];
    
    if ($resultado_ejercicios) {
        while ($ej = $resultado_ejercicios->fetch_assoc()) {
            $ejercicios[] = $ej;
        }
    }

    // Cargar la vista
    require_once 'views/rutinas/detalle.php';
}
/**
 * Muestra el formulario para crear una nueva rutina
 */
public function crear() {
    // Verificar sesión
    if (!isset($_SESSION['usuario_id'])) {
        header("Location: index.php?controller=UsuarioController&action=mostrarLogin");
        exit();
    }

    // Obtener todos los ejercicios disponibles
    require_once 'models/Ejercicio.php';
    $ejercicioModel = new Ejercicio();
    $ejercicios = $ejercicioModel->getAll();

    // Cargar la vista
    require_once 'views/rutinas/crear.php';
}

/**
 * Guarda una nueva rutina con sus ejercicios
 */
public function guardar() {
    // Verificar sesión
    if (!isset($_SESSION['usuario_id'])) {
        header("Location: index.php?controller=UsuarioController&action=mostrarLogin");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre_rutina = isset($_POST['nombre_rutina']) ? trim($_POST['nombre_rutina']) : '';
        $descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : '';
        $ejercicios = isset($_POST['ejercicios']) ? $_POST['ejercicios'] : [];
        $id_usuario = $_SESSION['usuario_id'];

        // Validar datos
        if (!empty($nombre_rutina) && !empty($descripcion) && !empty($ejercicios)) {
            
            $rutinaModel = new Rutina();
            $resultado = $rutinaModel->guardarConEjercicios($nombre_rutina, $descripcion, $id_usuario, $ejercicios);

            if ($resultado) {
                $_SESSION['mensaje_exito'] = "¡Rutina creada exitosamente!";
            } else {
                $_SESSION['mensaje_error'] = "Error al crear la rutina.";
            }
        } else {
            $_SESSION['mensaje_error'] = "Por favor, completa todos los campos y agrega al menos un ejercicio.";
        }

        // Redirigir a la lista de rutinas
        header("Location: index.php?controller=RutinaController&action=index");
        exit();
    }
}

/**
 * Elimina una rutina personalizada del usuario
 */
public function eliminar() {
    // Verificar sesión
    if (!isset($_SESSION['usuario_id'])) {
        header("Location: index.php?controller=UsuarioController&action=mostrarLogin");
        exit();
    }

    $id_rutina = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $id_usuario = $_SESSION['usuario_id'];

    if ($id_rutina > 0) {
        global $conexion;
        
        // Verificar que la rutina pertenezca al usuario (seguridad)
        $sql_verificar = "SELECT id_rutina FROM rutinas WHERE id_rutina = $id_rutina AND id_usuario = $id_usuario";
        $resultado = $conexion->query($sql_verificar);

        if ($resultado && $resultado->num_rows > 0) {
            // Eliminar la rutina (las relaciones se eliminan automáticamente por CASCADE)
            $sql_eliminar = "DELETE FROM rutinas WHERE id_rutina = $id_rutina";
            
            if ($conexion->query($sql_eliminar)) {
                $_SESSION['mensaje_exito'] = "Rutina eliminada correctamente.";
            } else {
                $_SESSION['mensaje_error'] = "Error al eliminar la rutina.";
            }
        } else {
            $_SESSION['mensaje_error'] = "No tienes permiso para eliminar esta rutina.";
        }
    }

    header("Location: index.php?controller=RutinaController&action=index");
    exit();
}
}
?>