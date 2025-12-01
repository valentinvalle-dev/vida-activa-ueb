-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-11-2025 a las 17:39:51
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `vida_activa_ueb_db`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_ActualizarDescripcionRutina` (IN `p_id_rutina` INT, IN `p_nueva_descripcion` TEXT)   BEGIN
    UPDATE rutinas
    SET descripcion = p_nueva_descripcion
    WHERE id_rutina = p_id_rutina;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_EliminarDetalleRutina` (IN `p_id_rutina_detalle` INT)   BEGIN
    DELETE FROM rutina_detalle
    WHERE id_rutina_detalle = p_id_rutina_detalle;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_GetEjerciciosDeRutina` (IN `p_id_rutina` INT)   BEGIN
    SELECT
        rutinas.nombre_rutina,
        ejercicios.nombre_ejercicio,
        rutina_detalle.series,
        rutina_detalle.repeticiones
    FROM
        rutinas
    INNER JOIN
        rutina_detalle ON rutinas.id_rutina = rutina_detalle.id_rutina
    INNER JOIN
        ejercicios ON rutina_detalle.id_ejercicio = ejercicios.id_ejercicio
    WHERE
        rutinas.id_rutina = p_id_rutina;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_GetEjerciciosNoUtilizados` ()   BEGIN
    SELECT ejercicios.nombre_ejercicio
    FROM ejercicios
    LEFT JOIN rutina_detalle ON ejercicios.id_ejercicio = rutina_detalle.id_ejercicio
    WHERE rutina_detalle.id_rutina_detalle IS NULL;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_GetEstadisticasProgresoGlobal` ()   BEGIN
    SELECT
        AVG(peso_kg) AS peso_promedio,
        MAX(peso_kg) AS peso_maximo,
        MIN(peso_kg) AS peso_minimo,
        COUNT(id_progreso) AS total_de_registros
    FROM
        progreso
    WHERE
        peso_kg IS NOT NULL;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_GetHistorialProgresoUsuario` (IN `p_id_usuario` INT)   BEGIN
    SELECT
        id_usuario,
        fecha_registro,
        peso_kg
    FROM
        progreso
    WHERE
        id_usuario = p_id_usuario
    ORDER BY
        fecha_registro DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_GetPlanificacionUsuario` (IN `p_id_usuario` INT)   BEGIN
    SELECT
        usuarios.nombre,
        rutinas.nombre_rutina,
        planificacion.fecha_planificada,
        planificacion.estado
    FROM
        usuarios
    INNER JOIN
        planificacion ON usuarios.id_usuario = planificacion.id_usuario
    INNER JOIN
        rutinas ON planificacion.id_rutina = rutinas.id_rutina
    WHERE
        usuarios.id_usuario = p_id_usuario;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_GetProgresoSobrePromedio` ()   BEGIN
    SELECT
        id_usuario,
        fecha_registro,
        peso_kg
    FROM
        progreso
    WHERE
        peso_kg > (SELECT AVG(peso_kg) FROM progreso WHERE peso_kg IS NOT NULL);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_GetRutinasPorNombreUsuario` (IN `p_nombre` VARCHAR(100))   BEGIN
    SELECT
        nombre_rutina,
        descripcion
    FROM
        rutinas
    WHERE
        id_usuario IN (SELECT id_usuario FROM usuarios WHERE nombre = p_nombre);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_RegistrarUsuario` (IN `p_nombre` VARCHAR(100), IN `p_email` VARCHAR(255), IN `p_password` VARCHAR(255))   BEGIN
    -- Se encripta la contraseña usando SHA2 con una longitud de 256 bits
    INSERT INTO usuarios (nombre, email, password)
    VALUES (p_nombre, p_email, SHA2(p_password, 256));
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ejercicios`
--

CREATE TABLE `ejercicios` (
  `id_ejercicio` int(11) NOT NULL,
  `nombre_ejercicio` varchar(150) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `url_imagen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ejercicios`
--

INSERT INTO `ejercicios` (`id_ejercicio`, `nombre_ejercicio`, `descripcion`, `url_imagen`) VALUES
(1, 'Flexiones de Pecho', 'Túmbate boca abajo, apoya las manos a la altura de los hombros y empuja el suelo para levantar tu cuerpo.', 'flexiones.jpg'),
(2, 'Sentadillas', 'De pie, con los pies a la anchura de los hombros, baja la cadera como si te fueras a sentar en una silla.', 'sentadillas.jpg'),
(3, 'Plancha Abdominal', 'Mantén una posición de flexión, con el cuerpo recto y los abdominales contraídos.', 'plancha.jpg'),
(4, 'Zancadas (Lunges)', 'Da un paso hacia adelante y flexiona ambas rodillas hasta que formen un ángulo de 90 grados.', 'zancadas.jpg'),
(5, 'Elevación de Talones', 'De pie, levanta los talones del suelo para trabajar los gemelos.', 'elevacion_talones.jpg'),
(6, 'Burpees', 'Un ejercicio de cuerpo completo muy intenso.', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `objetivos`
--

CREATE TABLE `objetivos` (
  `id_objetivo` int(11) NOT NULL,
  `nombre_objetivo` varchar(100) NOT NULL,
  `descripcion_guia` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `objetivos`
--

INSERT INTO `objetivos` (`id_objetivo`, `nombre_objetivo`, `descripcion_guia`) VALUES
(1, 'Perder Peso', 'Rutinas enfocadas en cardio y déficit calórico.'),
(2, 'Ganar Músculo', 'Rutinas de fuerza (hipertrofia) y superávit calórico.'),
(3, 'Mantenerse Activo', 'Rutinas equilibradas para la salud general.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `planificacion`
--

CREATE TABLE `planificacion` (
  `id_planificacion` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_rutina` int(11) NOT NULL,
  `fecha_planificada` date NOT NULL,
  `completado` tinyint(1) DEFAULT 0,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `planificacion`
--

INSERT INTO `planificacion` (`id_planificacion`, `id_usuario`, `id_rutina`, `fecha_planificada`, `completado`, `fecha_creacion`) VALUES
(1, 1, 4, '2025-10-21', 2, '2025-11-14 00:44:50'),
(2, 1, 5, '2025-10-22', 1, '2025-11-14 00:44:50'),
(3, 1, 2, '2025-10-23', 1, '2025-11-14 00:44:50'),
(4, 6, 1, '2025-10-22', 1, '2025-11-14 00:44:50'),
(5, 6, 3, '2025-10-24', 1, '2025-11-14 00:44:50'),
(6, 12, 1, '2025-10-21', 2, '2025-11-14 00:44:50'),
(7, 1, 1, '2025-11-15', 1, '2025-11-14 00:54:59'),
(8, 1, 2, '2025-11-16', 1, '2025-11-14 00:54:59'),
(9, 1, 1, '2025-11-18', 0, '2025-11-14 00:54:59'),
(10, 1, 3, '2025-11-20', 0, '2025-11-14 00:54:59'),
(11, 1, 4, '2025-11-22', 0, '2025-11-14 00:54:59');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `progreso`
--

CREATE TABLE `progreso` (
  `id_progreso` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha_registro` date NOT NULL,
  `peso_kg` decimal(5,2) DEFAULT NULL,
  `altura_cm` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `progreso`
--

INSERT INTO `progreso` (`id_progreso`, `id_usuario`, `fecha_registro`, `peso_kg`, `altura_cm`) VALUES
(1, 1, '2025-09-26', 75.00, 175.00),
(2, 1, '2025-10-10', 74.50, 175.00),
(3, 1, '2025-10-21', 74.00, 175.00),
(4, 6, '2025-10-01', 60.50, 165.00),
(5, 6, '2025-10-15', 60.00, 165.00),
(6, 12, '2025-10-21', 58.00, 163.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rutinas`
--

CREATE TABLE `rutinas` (
  `id_rutina` int(11) NOT NULL,
  `nombre_rutina` varchar(150) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rutinas`
--

INSERT INTO `rutinas` (`id_rutina`, `nombre_rutina`, `descripcion`, `id_usuario`) VALUES
(1, 'Calentamiento Rápido (Casa)', 'Rutina rápida de calentamiento para preparar el cuerpo antes del ejercicio principal. Incluye movimientos dinámicos y estiramientos.', NULL),
(2, 'Full Body Básico (Gimnasio)', 'Rutina completa que trabaja todos los grupos musculares principales. Ideal para entrenar 3 veces por semana en el gimnasio.', NULL),
(3, 'Core y Estabilidad (Casa)', 'Enfocada en fortalecer la zona abdominal, lumbar y mejorar la estabilidad del core. Perfecta para hacer en casa.', NULL),
(4, 'Martes de Cardio', 'Rutina para hacer en casa', 1),
(5, 'Plancha abdominal', 'En casa', 1),
(6, 'Cardio Intenso (Casa)', 'Rutina de alta intensidad para quemar calorías y mejorar la resistencia cardiovascular. Sin necesidad de equipo.', NULL),
(7, 'Fuerza de Tren Superior (Gimnasio)', 'Rutina enfocada en desarrollar pecho, espalda, hombros y brazos con pesas y máquinas.', NULL),
(8, 'Piernas y Glúteos (Casa)', 'Fortalece y tonifica las piernas y glúteos usando solo el peso corporal. Ideal para hacer en casa.', NULL),
(9, 'Yoga y Flexibilidad (Casa)', 'Secuencia de posturas de yoga para mejorar la flexibilidad, reducir el estrés y fortalecer el cuerpo.', NULL),
(10, 'Rutina de Resistencia (Gimnasio)', 'Combina ejercicios de fuerza y cardio para mejorar la resistencia muscular y cardiovascular.', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rutina_detalle`
--

CREATE TABLE `rutina_detalle` (
  `id_rutina_detalle` int(11) NOT NULL,
  `id_rutina` int(11) NOT NULL,
  `id_ejercicio` int(11) NOT NULL,
  `series` int(11) DEFAULT NULL,
  `repeticiones` varchar(50) DEFAULT NULL,
  `duracion_min` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rutina_detalle`
--

INSERT INTO `rutina_detalle` (`id_rutina_detalle`, `id_rutina`, `id_ejercicio`, `series`, `repeticiones`, `duracion_min`) VALUES
(1, 1, 2, 2, '15', NULL),
(2, 1, 4, 2, '12 por pierna', NULL),
(3, 2, 2, 3, '12', NULL),
(4, 2, 1, 3, '10', NULL),
(5, 2, 5, 3, '20', NULL),
(6, 3, 3, 4, NULL, 1),
(8, 6, 6, 4, '30 segundos', NULL),
(9, 6, 2, 3, '20', NULL),
(10, 6, 4, 3, '15 por pierna', NULL),
(11, 7, 1, 4, '12', NULL),
(12, 7, 1, 4, '10', NULL),
(13, 8, 2, 4, '15', NULL),
(14, 8, 4, 3, '12 por pierna', NULL),
(15, 9, 3, 3, NULL, 2),
(16, 10, 1, 3, '15', NULL),
(17, 10, 2, 3, '20', NULL),
(18, 10, 6, 3, '45 segundos', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rutina_objetivos`
--

CREATE TABLE `rutina_objetivos` (
  `id_rutina` int(11) NOT NULL,
  `id_objetivo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rutina_objetivos`
--

INSERT INTO `rutina_objetivos` (`id_rutina`, `id_objetivo`) VALUES
(1, 3),
(2, 2),
(3, 3),
(4, 1),
(5, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `email`, `password`, `fecha_registro`) VALUES
(1, 'Valentín', 'valentin258@hotmail.com', '$2y$04$ekYca/JmHWHOwXCdmHjv1.CoxOQ8y9demZ.PyZmtxEBs3LF6J3H16', '2025-09-25 21:45:17'),
(4, 'Valenchus', 'valenchus258@hotmail.com', '$2y$04$rEX4mOKzn6fkqPURBF1bmuXA8hzzHC61SKscn6IJADJS0oO1FVYE2', '2025-09-25 21:56:32'),
(6, 'Emily Velazco', 'emily22@gmail.com', '$2y$04$1uwYsYOK/gIbpeookKYoK.RH5U/6md58ajuBatIsRwuu5jD4Dd1Mi', '2025-09-30 17:06:16'),
(8, 'prueba', 'prueba@gmail.com', '$2y$04$S.O4ObVJxrixR3FBD2imBO.EsSAK704v0RqWO06pIunn7GarFrexy', '2025-10-01 15:38:38'),
(9, 'Cristian ', 'cristian2@gmail.com', '$2y$04$uetBvKZjidfjm/DijfBVsuXfEiW7CRi2SBFufm.m5uOZDUVR1AKxm', '2025-10-10 16:06:29'),
(10, 'emii', 'emiii@gmail.com', '$2y$04$ciNw3PtwYgeFZEGsavcQyu/7S/3y1WLYtKOeYW17ZA8q7ttgGdzZS', '2025-10-17 01:43:34'),
(11, 'Usuario de Prueba', 'prueba@correo.com', '97c74b48a0b149231ad99469a24ffb4b20c4c1f79edaa47620f971c7c0e8208a', '2025-10-21 16:55:18'),
(12, 'Emily Velasco', 'nuevo.estudiante@email.com', '5eda3cda6825004208c8d5fe430304e7c7127058922d9f2d1671389e71fd9222', '2025-10-21 17:07:09'),
(13, 'Valentín', '1235@gmail.com', '$2y$04$KPXGRgVPjeqYGmQq.xbmEuVtO6UmcLMYRKYlAumHMwEmU99poC7Wy', '2025-11-14 01:56:06'),
(14, 'Usuario Prueba', 'prueba@test.com', '$2y$04$rEX4mOKzn6fkqPURBF1bmuXA8hzzHC61SKscn6IJADJS0oO1FVYE2', '2025-11-14 02:23:34'),
(15, 'Emily Velasco', 'emily@velasco.com', '$2y$04$bju5HvsO/ymCFV32sxXuXen7Z7AB3dw9Oi8o9hFi7LsLc1LL0j0/q', '2025-11-14 02:51:48');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `ejercicios`
--
ALTER TABLE `ejercicios`
  ADD PRIMARY KEY (`id_ejercicio`);

--
-- Indices de la tabla `objetivos`
--
ALTER TABLE `objetivos`
  ADD PRIMARY KEY (`id_objetivo`);

--
-- Indices de la tabla `planificacion`
--
ALTER TABLE `planificacion`
  ADD PRIMARY KEY (`id_planificacion`),
  ADD KEY `fk_plan_usuario` (`id_usuario`),
  ADD KEY `fk_plan_rutina` (`id_rutina`);

--
-- Indices de la tabla `progreso`
--
ALTER TABLE `progreso`
  ADD PRIMARY KEY (`id_progreso`),
  ADD KEY `fk_progreso_usuario` (`id_usuario`);

--
-- Indices de la tabla `rutinas`
--
ALTER TABLE `rutinas`
  ADD PRIMARY KEY (`id_rutina`),
  ADD KEY `fk_rutina_usuario` (`id_usuario`);

--
-- Indices de la tabla `rutina_detalle`
--
ALTER TABLE `rutina_detalle`
  ADD PRIMARY KEY (`id_rutina_detalle`),
  ADD KEY `fk_detalle_rutina` (`id_rutina`),
  ADD KEY `fk_detalle_ejercicio` (`id_ejercicio`);

--
-- Indices de la tabla `rutina_objetivos`
--
ALTER TABLE `rutina_objetivos`
  ADD PRIMARY KEY (`id_rutina`,`id_objetivo`),
  ADD KEY `fk_objetivo_rutina` (`id_objetivo`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `ejercicios`
--
ALTER TABLE `ejercicios`
  MODIFY `id_ejercicio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `objetivos`
--
ALTER TABLE `objetivos`
  MODIFY `id_objetivo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `planificacion`
--
ALTER TABLE `planificacion`
  MODIFY `id_planificacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `progreso`
--
ALTER TABLE `progreso`
  MODIFY `id_progreso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `rutinas`
--
ALTER TABLE `rutinas`
  MODIFY `id_rutina` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `rutina_detalle`
--
ALTER TABLE `rutina_detalle`
  MODIFY `id_rutina_detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `planificacion`
--
ALTER TABLE `planificacion`
  ADD CONSTRAINT `fk_plan_rutina` FOREIGN KEY (`id_rutina`) REFERENCES `rutinas` (`id_rutina`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_plan_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `progreso`
--
ALTER TABLE `progreso`
  ADD CONSTRAINT `fk_progreso_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `rutinas`
--
ALTER TABLE `rutinas`
  ADD CONSTRAINT `fk_rutina_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `rutina_detalle`
--
ALTER TABLE `rutina_detalle`
  ADD CONSTRAINT `fk_detalle_ejercicio` FOREIGN KEY (`id_ejercicio`) REFERENCES `ejercicios` (`id_ejercicio`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_detalle_rutina` FOREIGN KEY (`id_rutina`) REFERENCES `rutinas` (`id_rutina`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `rutina_objetivos`
--
ALTER TABLE `rutina_objetivos`
  ADD CONSTRAINT `fk_objetivo_objetivo` FOREIGN KEY (`id_objetivo`) REFERENCES `objetivos` (`id_objetivo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_objetivo_rutina` FOREIGN KEY (`id_rutina`) REFERENCES `rutinas` (`id_rutina`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
