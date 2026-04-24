-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 24-04-2026 a las 04:50:43
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `mental_refuge_2`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `v3_autoevaluaciones`
--

CREATE TABLE `v3_autoevaluaciones` (
  `id_evaluacion` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `v3_citas`
--

CREATE TABLE `v3_citas` (
  `id_cita` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_profesional` int(11) DEFAULT NULL,
  `fecha_hora` datetime DEFAULT NULL,
  `estado` enum('pendiente','completada','cancelada') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `v3_comentarios`
--

CREATE TABLE `v3_comentarios` (
  `id_com` int(11) NOT NULL,
  `id_pub` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `comentario` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `v3_diario`
--

CREATE TABLE `v3_diario` (
  `id_diario` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `entrada` text DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `v3_diario`
--

INSERT INTO `v3_diario` (`id_diario`, `id_usuario`, `entrada`, `fecha`) VALUES
(5, 1, 'estoy cansada', '2026-04-24 01:39:30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `v3_foros`
--

CREATE TABLE `v3_foros` (
  `id_foro` int(11) NOT NULL,
  `nombre_foro` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `v3_logs_sistema`
--

CREATE TABLE `v3_logs_sistema` (
  `id_sys` int(11) NOT NULL,
  `accion` varchar(100) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `v3_notificaciones`
--

CREATE TABLE `v3_notificaciones` (
  `id_notif` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `mensaje` varchar(255) DEFAULT NULL,
  `leido` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `v3_preguntas`
--

CREATE TABLE `v3_preguntas` (
  `id_pregunta` int(11) NOT NULL,
  `texto_pregunta` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `v3_profesionales`
--

CREATE TABLE `v3_profesionales` (
  `id_profesional` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `especialidad` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `v3_publicaciones`
--

CREATE TABLE `v3_publicaciones` (
  `id_pub` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_foro` int(11) DEFAULT NULL,
  `contenido` text DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `v3_recursos`
--

CREATE TABLE `v3_recursos` (
  `id_recurso` int(11) NOT NULL,
  `titulo` varchar(100) DEFAULT NULL,
  `link_url` varchar(255) DEFAULT NULL,
  `tipo` enum('video','articulo','pdf') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `v3_resultados`
--

CREATE TABLE `v3_resultados` (
  `id_resultado` int(11) NOT NULL,
  `id_evaluacion` int(11) DEFAULT NULL,
  `puntaje_total` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `v3_roles`
--

CREATE TABLE `v3_roles` (
  `id_rol` int(11) NOT NULL,
  `nombre_rol` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `v3_roles`
--

INSERT INTO `v3_roles` (`id_rol`, `nombre_rol`) VALUES
(1, 'Usuario'),
(2, 'Administrador'),
(3, 'Profesional');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `v3_sugerencias`
--

CREATE TABLE `v3_sugerencias` (
  `id_sug` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `texto_sugerencia` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `v3_usuarios`
--

CREATE TABLE `v3_usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre_completo` varchar(100) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `id_rol` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `v3_usuarios`
--

INSERT INTO `v3_usuarios` (`id_usuario`, `nombre_completo`, `correo`, `password`, `id_rol`) VALUES
(1, 'Katherine Castellanos', 'katherine@refuge.com', '1234', 1),
(2, 'Mathias Barrera', 'mathias@refuge.com', '1234', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `v3_autoevaluaciones`
--
ALTER TABLE `v3_autoevaluaciones`
  ADD PRIMARY KEY (`id_evaluacion`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `v3_citas`
--
ALTER TABLE `v3_citas`
  ADD PRIMARY KEY (`id_cita`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_profesional` (`id_profesional`);

--
-- Indices de la tabla `v3_comentarios`
--
ALTER TABLE `v3_comentarios`
  ADD PRIMARY KEY (`id_com`),
  ADD KEY `id_pub` (`id_pub`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `v3_diario`
--
ALTER TABLE `v3_diario`
  ADD PRIMARY KEY (`id_diario`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `v3_foros`
--
ALTER TABLE `v3_foros`
  ADD PRIMARY KEY (`id_foro`);

--
-- Indices de la tabla `v3_logs_sistema`
--
ALTER TABLE `v3_logs_sistema`
  ADD PRIMARY KEY (`id_sys`);

--
-- Indices de la tabla `v3_notificaciones`
--
ALTER TABLE `v3_notificaciones`
  ADD PRIMARY KEY (`id_notif`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `v3_preguntas`
--
ALTER TABLE `v3_preguntas`
  ADD PRIMARY KEY (`id_pregunta`);

--
-- Indices de la tabla `v3_profesionales`
--
ALTER TABLE `v3_profesionales`
  ADD PRIMARY KEY (`id_profesional`);

--
-- Indices de la tabla `v3_publicaciones`
--
ALTER TABLE `v3_publicaciones`
  ADD PRIMARY KEY (`id_pub`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_foro` (`id_foro`);

--
-- Indices de la tabla `v3_recursos`
--
ALTER TABLE `v3_recursos`
  ADD PRIMARY KEY (`id_recurso`);

--
-- Indices de la tabla `v3_resultados`
--
ALTER TABLE `v3_resultados`
  ADD PRIMARY KEY (`id_resultado`),
  ADD KEY `id_evaluacion` (`id_evaluacion`);

--
-- Indices de la tabla `v3_roles`
--
ALTER TABLE `v3_roles`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `v3_sugerencias`
--
ALTER TABLE `v3_sugerencias`
  ADD PRIMARY KEY (`id_sug`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `v3_usuarios`
--
ALTER TABLE `v3_usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD KEY `id_rol` (`id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `v3_autoevaluaciones`
--
ALTER TABLE `v3_autoevaluaciones`
  MODIFY `id_evaluacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `v3_citas`
--
ALTER TABLE `v3_citas`
  MODIFY `id_cita` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `v3_comentarios`
--
ALTER TABLE `v3_comentarios`
  MODIFY `id_com` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `v3_diario`
--
ALTER TABLE `v3_diario`
  MODIFY `id_diario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `v3_foros`
--
ALTER TABLE `v3_foros`
  MODIFY `id_foro` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `v3_logs_sistema`
--
ALTER TABLE `v3_logs_sistema`
  MODIFY `id_sys` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `v3_notificaciones`
--
ALTER TABLE `v3_notificaciones`
  MODIFY `id_notif` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `v3_preguntas`
--
ALTER TABLE `v3_preguntas`
  MODIFY `id_pregunta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `v3_profesionales`
--
ALTER TABLE `v3_profesionales`
  MODIFY `id_profesional` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `v3_publicaciones`
--
ALTER TABLE `v3_publicaciones`
  MODIFY `id_pub` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `v3_recursos`
--
ALTER TABLE `v3_recursos`
  MODIFY `id_recurso` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `v3_resultados`
--
ALTER TABLE `v3_resultados`
  MODIFY `id_resultado` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `v3_roles`
--
ALTER TABLE `v3_roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `v3_sugerencias`
--
ALTER TABLE `v3_sugerencias`
  MODIFY `id_sug` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `v3_usuarios`
--
ALTER TABLE `v3_usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `v3_autoevaluaciones`
--
ALTER TABLE `v3_autoevaluaciones`
  ADD CONSTRAINT `v3_autoevaluaciones_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `v3_usuarios` (`id_usuario`);

--
-- Filtros para la tabla `v3_citas`
--
ALTER TABLE `v3_citas`
  ADD CONSTRAINT `v3_citas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `v3_usuarios` (`id_usuario`),
  ADD CONSTRAINT `v3_citas_ibfk_2` FOREIGN KEY (`id_profesional`) REFERENCES `v3_profesionales` (`id_profesional`);

--
-- Filtros para la tabla `v3_comentarios`
--
ALTER TABLE `v3_comentarios`
  ADD CONSTRAINT `v3_comentarios_ibfk_1` FOREIGN KEY (`id_pub`) REFERENCES `v3_publicaciones` (`id_pub`),
  ADD CONSTRAINT `v3_comentarios_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `v3_usuarios` (`id_usuario`);

--
-- Filtros para la tabla `v3_diario`
--
ALTER TABLE `v3_diario`
  ADD CONSTRAINT `v3_diario_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `v3_usuarios` (`id_usuario`);

--
-- Filtros para la tabla `v3_notificaciones`
--
ALTER TABLE `v3_notificaciones`
  ADD CONSTRAINT `v3_notificaciones_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `v3_usuarios` (`id_usuario`);

--
-- Filtros para la tabla `v3_publicaciones`
--
ALTER TABLE `v3_publicaciones`
  ADD CONSTRAINT `v3_publicaciones_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `v3_usuarios` (`id_usuario`),
  ADD CONSTRAINT `v3_publicaciones_ibfk_2` FOREIGN KEY (`id_foro`) REFERENCES `v3_foros` (`id_foro`);

--
-- Filtros para la tabla `v3_resultados`
--
ALTER TABLE `v3_resultados`
  ADD CONSTRAINT `v3_resultados_ibfk_1` FOREIGN KEY (`id_evaluacion`) REFERENCES `v3_autoevaluaciones` (`id_evaluacion`);

--
-- Filtros para la tabla `v3_sugerencias`
--
ALTER TABLE `v3_sugerencias`
  ADD CONSTRAINT `v3_sugerencias_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `v3_usuarios` (`id_usuario`);

--
-- Filtros para la tabla `v3_usuarios`
--
ALTER TABLE `v3_usuarios`
  ADD CONSTRAINT `v3_usuarios_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `v3_roles` (`id_rol`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
