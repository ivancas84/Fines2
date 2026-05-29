-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 29-05-2026 a las 18:30:53
-- Versión del servidor: 10.6.20-MariaDB
-- Versión de PHP: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `planfi10_20204`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumno`
--

CREATE TABLE `alumno` (
  `id` varchar(45) NOT NULL,
  `anio_ingreso` varchar(45) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `persona` varchar(45) NOT NULL,
  `estado_inscripcion` varchar(45) DEFAULT NULL,
  `fecha_titulacion` date DEFAULT NULL,
  `plan` varchar(45) DEFAULT NULL,
  `resolucion_inscripcion` varchar(45) DEFAULT NULL,
  `anio_inscripcion` smallint(1) DEFAULT NULL,
  `semestre_inscripcion` smallint(1) DEFAULT NULL,
  `semestre_ingreso` smallint(1) DEFAULT NULL,
  `adeuda_legajo` varchar(255) DEFAULT NULL,
  `adeuda_deudores` varchar(255) DEFAULT NULL,
  `documentacion_inscripcion` varchar(255) DEFAULT NULL,
  `anio_inscripcion_completo` tinyint(1) DEFAULT NULL,
  `establecimiento_inscripcion` varchar(255) DEFAULT NULL,
  `libro_folio` varchar(255) DEFAULT NULL,
  `libro` varchar(45) DEFAULT NULL,
  `folio` varchar(45) DEFAULT NULL,
  `comentarios` text DEFAULT NULL,
  `tiene_dni` tinyint(1) NOT NULL DEFAULT 0,
  `tiene_constancia` tinyint(1) NOT NULL DEFAULT 0,
  `tiene_certificado` tinyint(1) NOT NULL DEFAULT 0,
  `previas_completas` tinyint(1) NOT NULL DEFAULT 0,
  `tiene_partida` tinyint(1) NOT NULL DEFAULT 0,
  `creado` timestamp NOT NULL DEFAULT current_timestamp(),
  `confirmado_direccion` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumno_comision`
--

CREATE TABLE `alumno_comision` (
  `id` varchar(45) NOT NULL,
  `creado` timestamp NOT NULL DEFAULT current_timestamp(),
  `activo` tinyint(1) DEFAULT 0,
  `observaciones` text DEFAULT NULL,
  `comision` varchar(45) DEFAULT NULL,
  `alumno` varchar(45) NOT NULL,
  `estado` varchar(100) DEFAULT 'Activo',
  `pfid` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignacion_planilla_docente`
--

CREATE TABLE `asignacion_planilla_docente` (
  `id` varchar(45) NOT NULL,
  `planilla_docente` varchar(45) NOT NULL,
  `toma` varchar(45) NOT NULL,
  `insertado` timestamp NOT NULL DEFAULT current_timestamp(),
  `comentario` varchar(255) DEFAULT NULL,
  `reclamo` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignatura`
--

CREATE TABLE `asignatura` (
  `id` varchar(45) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `formacion` varchar(45) DEFAULT NULL,
  `clasificacion` varchar(45) DEFAULT NULL,
  `codigo` varchar(45) DEFAULT NULL,
  `perfil` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `calendario`
--

CREATE TABLE `calendario` (
  `id` varchar(45) NOT NULL,
  `inicio` date DEFAULT NULL,
  `fin` date DEFAULT NULL,
  `anio` year(4) NOT NULL,
  `semestre` smallint(6) NOT NULL,
  `insertado` timestamp NOT NULL DEFAULT current_timestamp(),
  `descripcion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `calificacion`
--

CREATE TABLE `calificacion` (
  `id` varchar(45) NOT NULL,
  `nota1` decimal(4,2) DEFAULT NULL,
  `nota2` decimal(4,2) DEFAULT NULL,
  `nota3` decimal(4,2) DEFAULT NULL,
  `nota_final` decimal(4,2) DEFAULT NULL,
  `crec` decimal(4,2) DEFAULT NULL,
  `curso` varchar(45) DEFAULT NULL,
  `porcentaje_asistencia` int(3) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `division` varchar(255) DEFAULT NULL,
  `alumno` varchar(45) NOT NULL,
  `disposicion` varchar(45) NOT NULL,
  `fecha` date DEFAULT NULL,
  `archivado` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargo`
--

CREATE TABLE `cargo` (
  `id` varchar(45) NOT NULL,
  `descripcion` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `centro_educativo`
--

CREATE TABLE `centro_educativo` (
  `id` varchar(45) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `cue` varchar(45) DEFAULT NULL,
  `domicilio` varchar(45) DEFAULT NULL,
  `observaciones` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comision`
--

CREATE TABLE `comision` (
  `id` varchar(45) NOT NULL,
  `turno` varchar(45) DEFAULT NULL,
  `division` varchar(45) NOT NULL,
  `comentario` text DEFAULT NULL,
  `autorizada` tinyint(1) NOT NULL DEFAULT 0,
  `apertura` tinyint(1) NOT NULL DEFAULT 0,
  `publicada` tinyint(1) NOT NULL DEFAULT 0,
  `observaciones` text DEFAULT NULL,
  `alta` timestamp NOT NULL DEFAULT current_timestamp(),
  `sede` varchar(45) NOT NULL,
  `modalidad` varchar(45) NOT NULL,
  `planificacion` varchar(45) DEFAULT NULL,
  `comision_siguiente` varchar(45) DEFAULT NULL,
  `calendario` varchar(45) NOT NULL,
  `identificacion` varchar(45) DEFAULT NULL,
  `estado` varchar(45) DEFAULT 'Confirma',
  `configuracion` varchar(45) DEFAULT 'Histórica',
  `pfid` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comision_relacionada`
--

CREATE TABLE `comision_relacionada` (
  `id` varchar(45) NOT NULL,
  `comision` varchar(45) NOT NULL,
  `relacion` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contralor`
--

CREATE TABLE `contralor` (
  `id` varchar(45) NOT NULL,
  `fecha_contralor` date DEFAULT NULL,
  `fecha_consejo` date DEFAULT NULL,
  `insertado` timestamp NOT NULL DEFAULT current_timestamp(),
  `planilla_docente` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `curso`
--

CREATE TABLE `curso` (
  `id` varchar(45) NOT NULL,
  `horas_catedra` int(11) NOT NULL,
  `comision` varchar(45) NOT NULL,
  `alta` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `descripcion_horario` varchar(255) DEFAULT NULL,
  `codigo` varchar(45) DEFAULT NULL,
  `disposicion` varchar(45) DEFAULT NULL,
  `observaciones` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `designacion`
--

CREATE TABLE `designacion` (
  `id` varchar(45) NOT NULL,
  `desde` date DEFAULT NULL,
  `hasta` date DEFAULT NULL,
  `cargo` varchar(45) NOT NULL,
  `sede` varchar(45) NOT NULL,
  `persona` varchar(45) NOT NULL,
  `alta` timestamp NOT NULL DEFAULT current_timestamp(),
  `pfid` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_persona`
--

CREATE TABLE `detalle_persona` (
  `id` varchar(45) NOT NULL,
  `descripcion` text NOT NULL,
  `archivo` varchar(45) DEFAULT NULL,
  `creado` timestamp NOT NULL DEFAULT current_timestamp(),
  `persona` varchar(45) NOT NULL,
  `fecha` date DEFAULT curdate(),
  `tipo` varchar(255) DEFAULT NULL,
  `asunto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dia`
--

CREATE TABLE `dia` (
  `id` varchar(45) NOT NULL,
  `numero` smallint(1) NOT NULL,
  `dia` varchar(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `disposicion`
--

CREATE TABLE `disposicion` (
  `id` varchar(45) NOT NULL,
  `asignatura` varchar(45) NOT NULL,
  `planificacion` varchar(45) NOT NULL,
  `orden_informe_coordinacion_distrital` int(11) DEFAULT NULL,
  `horas_catedra` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `disposicion_pendiente`
--

CREATE TABLE `disposicion_pendiente` (
  `id` varchar(45) NOT NULL,
  `disposicion` varchar(45) NOT NULL,
  `alumno` varchar(45) NOT NULL,
  `modo` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `distribucion_horaria`
--

CREATE TABLE `distribucion_horaria` (
  `id` varchar(45) NOT NULL,
  `horas_catedra` int(11) NOT NULL,
  `dia` int(11) NOT NULL,
  `disposicion` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `domicilio`
--

CREATE TABLE `domicilio` (
  `id` varchar(45) NOT NULL,
  `calle` varchar(45) NOT NULL,
  `entre` varchar(45) DEFAULT NULL,
  `numero` varchar(45) NOT NULL,
  `piso` varchar(45) DEFAULT NULL,
  `departamento` varchar(45) DEFAULT NULL,
  `barrio` varchar(255) DEFAULT NULL,
  `localidad` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `email`
--

CREATE TABLE `email` (
  `id` varchar(45) NOT NULL,
  `email` varchar(255) NOT NULL,
  `verificado` tinyint(1) NOT NULL DEFAULT 0,
  `insertado` timestamp NOT NULL DEFAULT current_timestamp(),
  `eliminado` timestamp NULL DEFAULT NULL,
  `persona` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `file`
--

CREATE TABLE `file` (
  `id` varchar(45) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `content` varchar(255) NOT NULL,
  `size` int(10) UNSIGNED NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horario`
--

CREATE TABLE `horario` (
  `id` varchar(45) NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `curso` varchar(45) NOT NULL,
  `dia` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modalidad`
--

CREATE TABLE `modalidad` (
  `id` varchar(45) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `pfid` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona`
--

CREATE TABLE `persona` (
  `id` varchar(45) NOT NULL,
  `nombres` varchar(255) NOT NULL,
  `apellidos` varchar(255) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `numero_documento` varchar(45) NOT NULL,
  `cuil` varchar(45) DEFAULT NULL,
  `genero` varchar(45) DEFAULT NULL,
  `apodo` varchar(255) DEFAULT NULL,
  `telefono` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `email_abc` varchar(255) DEFAULT NULL,
  `alta` timestamp NOT NULL DEFAULT current_timestamp(),
  `domicilio` varchar(45) DEFAULT NULL,
  `lugar_nacimiento` varchar(255) DEFAULT NULL,
  `telefono_verificado` tinyint(1) NOT NULL DEFAULT 0,
  `email_verificado` tinyint(1) NOT NULL DEFAULT 0,
  `info_verificada` tinyint(1) NOT NULL DEFAULT 0,
  `descripcion_domicilio` varchar(255) DEFAULT NULL,
  `cuil1` tinyint(3) UNSIGNED DEFAULT NULL,
  `cuil2` tinyint(3) UNSIGNED DEFAULT NULL,
  `departamento` varchar(45) DEFAULT NULL,
  `localidad` varchar(100) DEFAULT NULL,
  `partido` varchar(100) DEFAULT NULL,
  `codigo_area` varchar(45) DEFAULT NULL,
  `nacionalidad` varchar(100) DEFAULT NULL,
  `sexo` tinyint(3) UNSIGNED DEFAULT NULL,
  `dia_nacimiento` tinyint(3) UNSIGNED DEFAULT NULL,
  `mes_nacimiento` tinyint(3) UNSIGNED DEFAULT NULL,
  `anio_nacimiento` smallint(5) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `plan`
--

CREATE TABLE `plan` (
  `id` varchar(45) NOT NULL,
  `orientacion` varchar(45) NOT NULL,
  `resolucion` varchar(45) DEFAULT NULL,
  `distribucion_horaria` varchar(45) DEFAULT NULL,
  `pfid` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `planificacion`
--

CREATE TABLE `planificacion` (
  `id` varchar(45) NOT NULL,
  `anio` varchar(45) NOT NULL,
  `semestre` varchar(45) NOT NULL,
  `plan` varchar(45) NOT NULL,
  `pfid` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `planilla_docente`
--

CREATE TABLE `planilla_docente` (
  `id` varchar(45) NOT NULL,
  `numero` varchar(255) NOT NULL,
  `insertado` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_contralor` date DEFAULT NULL,
  `fecha_consejo` date DEFAULT NULL,
  `observaciones` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `resolucion`
--

CREATE TABLE `resolucion` (
  `id` varchar(45) NOT NULL,
  `numero` varchar(255) NOT NULL,
  `anio` year(4) DEFAULT NULL,
  `tipo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sede`
--

CREATE TABLE `sede` (
  `id` varchar(45) NOT NULL,
  `numero` varchar(45) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `observaciones` text DEFAULT NULL,
  `alta` timestamp NOT NULL DEFAULT current_timestamp(),
  `baja` timestamp NULL DEFAULT NULL,
  `domicilio` varchar(45) DEFAULT NULL,
  `tipo_sede` varchar(45) DEFAULT NULL,
  `centro_educativo` varchar(45) DEFAULT NULL,
  `fecha_traspaso` date DEFAULT NULL,
  `organizacion` varchar(45) DEFAULT NULL,
  `pfid` varchar(45) DEFAULT NULL,
  `pfid_organizacion` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `telefono`
--

CREATE TABLE `telefono` (
  `id` varchar(45) NOT NULL,
  `tipo` varchar(45) DEFAULT NULL,
  `prefijo` varchar(45) DEFAULT NULL,
  `numero` varchar(255) NOT NULL,
  `insertado` timestamp NOT NULL DEFAULT current_timestamp(),
  `eliminado` timestamp NULL DEFAULT NULL,
  `persona` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_sede`
--

CREATE TABLE `tipo_sede` (
  `id` varchar(45) NOT NULL,
  `descripcion` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `toma`
--

CREATE TABLE `toma` (
  `id` varchar(45) NOT NULL,
  `fecha_toma` date DEFAULT NULL,
  `estado` varchar(45) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `comentario` varchar(45) DEFAULT NULL,
  `tipo_movimiento` varchar(45) NOT NULL,
  `estado_contralor` varchar(45) DEFAULT NULL,
  `alta` timestamp NOT NULL DEFAULT current_timestamp(),
  `curso` varchar(45) NOT NULL,
  `docente` varchar(45) DEFAULT NULL,
  `reemplazo` varchar(45) DEFAULT NULL,
  `planilla_docente` varchar(45) DEFAULT NULL,
  `archivo` varchar(255) DEFAULT NULL,
  `reclamo` tinyint(1) NOT NULL DEFAULT 0,
  `estado_planilla` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alumno`
--
ALTER TABLE `alumno`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `alumno_persona_un` (`persona`),
  ADD UNIQUE KEY `alumno_libro_folio_un` (`libro_folio`),
  ADD KEY `alumno_plan_FK` (`plan`),
  ADD KEY `alumno_resolucion_inscripcion_FK` (`resolucion_inscripcion`);

--
-- Indices de la tabla `alumno_comision`
--
ALTER TABLE `alumno_comision`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_alumno_comision_idx` (`comision`),
  ADD KEY `fk_alumno_comision_alumno` (`alumno`);

--
-- Indices de la tabla `asignacion_planilla_docente`
--
ALTER TABLE `asignacion_planilla_docente`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_asignacion_planilla_docente_planilla_docente_idx` (`planilla_docente`),
  ADD KEY `fk_asignacion_planilla_docente_toma_idx` (`toma`);

--
-- Indices de la tabla `asignatura`
--
ALTER TABLE `asignatura`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre_UNIQUE` (`nombre`);

--
-- Indices de la tabla `calendario`
--
ALTER TABLE `calendario`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `calificacion`
--
ALTER TABLE `calificacion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `calificacion_curso_fk` (`curso`),
  ADD KEY `calificacion_alumno_FK` (`alumno`),
  ADD KEY `calificacion_disposicion_FK` (`disposicion`);

--
-- Indices de la tabla `cargo`
--
ALTER TABLE `cargo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `descripcion_UNIQUE` (`descripcion`);

--
-- Indices de la tabla `centro_educativo`
--
ALTER TABLE `centro_educativo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cue_UNIQUE` (`cue`),
  ADD KEY `fk_centro_educativo_domicilio1_idx` (`domicilio`);

--
-- Indices de la tabla `comision`
--
ALTER TABLE `comision`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_comision_sede1_idx` (`sede`),
  ADD KEY `fk_comision_comision1_idx` (`comision_siguiente`),
  ADD KEY `fk_comision_modalidad1_idx` (`modalidad`),
  ADD KEY `fk_comision_calendario1_idx` (`calendario`),
  ADD KEY `fk_comision_planificacion1_idx` (`planificacion`);

--
-- Indices de la tabla `comision_relacionada`
--
ALTER TABLE `comision_relacionada`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comision_relacionada_comision_FK` (`comision`),
  ADD KEY `comision_relacionada_relacion_FK` (`relacion`);

--
-- Indices de la tabla `contralor`
--
ALTER TABLE `contralor`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_contralor_planilla_docente1_idx` (`planilla_docente`);

--
-- Indices de la tabla `curso`
--
ALTER TABLE `curso`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_curso_comision1_idx` (`comision`),
  ADD KEY `curso_disposicion_FK` (`disposicion`);

--
-- Indices de la tabla `designacion`
--
ALTER TABLE `designacion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_designacion_cargo1_idx` (`cargo`),
  ADD KEY `fk_designacion_sede1_idx` (`sede`),
  ADD KEY `fk_designacion_persona1_idx` (`persona`);

--
-- Indices de la tabla `detalle_persona`
--
ALTER TABLE `detalle_persona`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_info_persona_file1_idx` (`archivo`),
  ADD KEY `fk_detalle_persona_persona1_idx` (`persona`);

--
-- Indices de la tabla `dia`
--
ALTER TABLE `dia`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero_UNIQUE` (`numero`),
  ADD UNIQUE KEY `dia_UNIQUE` (`dia`);

--
-- Indices de la tabla `disposicion`
--
ALTER TABLE `disposicion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_disposicion_asignatura` (`asignatura`),
  ADD KEY `fk_disposicion_planificacion` (`planificacion`);

--
-- Indices de la tabla `disposicion_pendiente`
--
ALTER TABLE `disposicion_pendiente`
  ADD PRIMARY KEY (`id`),
  ADD KEY `disposicion_pendiente_disposicion_FK` (`disposicion`),
  ADD KEY `disposicion_pendiente_alumno_FK` (`alumno`);

--
-- Indices de la tabla `distribucion_horaria`
--
ALTER TABLE `distribucion_horaria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `distribucion_horaria_disposicion_FK` (`disposicion`);

--
-- Indices de la tabla `domicilio`
--
ALTER TABLE `domicilio`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `email`
--
ALTER TABLE `email`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_email_persona1_idx` (`persona`);

--
-- Indices de la tabla `file`
--
ALTER TABLE `file`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `horario`
--
ALTER TABLE `horario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_horario_curso1_idx` (`curso`),
  ADD KEY `fk_horario_dia1_idx` (`dia`);

--
-- Indices de la tabla `modalidad`
--
ALTER TABLE `modalidad`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre_UNIQUE` (`nombre`);

--
-- Indices de la tabla `persona`
--
ALTER TABLE `persona`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero_documento_UNIQUE` (`numero_documento`),
  ADD UNIQUE KEY `cuil_UNIQUE` (`cuil`),
  ADD UNIQUE KEY `email_abc` (`email_abc`),
  ADD KEY `fk_persona_domicilio1_idx` (`domicilio`);

--
-- Indices de la tabla `plan`
--
ALTER TABLE `plan`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `planificacion`
--
ALTER TABLE `planificacion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_planificacion_plan1_idx` (`plan`);

--
-- Indices de la tabla `planilla_docente`
--
ALTER TABLE `planilla_docente`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `resolucion`
--
ALTER TABLE `resolucion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sede`
--
ALTER TABLE `sede`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`),
  ADD KEY `fk_sede_domicilio1_idx` (`domicilio`),
  ADD KEY `fk_sede_tipo_sede1_idx` (`tipo_sede`),
  ADD KEY `fk_sede_centro_educativo1_idx` (`centro_educativo`),
  ADD KEY `fk_sede_sede1` (`organizacion`);

--
-- Indices de la tabla `telefono`
--
ALTER TABLE `telefono`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_telefono_persona1_idx` (`persona`);

--
-- Indices de la tabla `tipo_sede`
--
ALTER TABLE `tipo_sede`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `descripcion_UNIQUE` (`descripcion`);

--
-- Indices de la tabla `toma`
--
ALTER TABLE `toma`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_toma_curso1_idx` (`curso`),
  ADD KEY `fk_toma_persona1_idx` (`docente`),
  ADD KEY `fk_toma_persona2_idx` (`reemplazo`),
  ADD KEY `fk_toma_planilla_docente1_idx` (`planilla_docente`);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `alumno`
--
ALTER TABLE `alumno`
  ADD CONSTRAINT `alumno_persona_FK` FOREIGN KEY (`persona`) REFERENCES `persona` (`id`),
  ADD CONSTRAINT `alumno_plan_FK` FOREIGN KEY (`plan`) REFERENCES `plan` (`id`),
  ADD CONSTRAINT `alumno_resolucion_inscripcion_FK` FOREIGN KEY (`resolucion_inscripcion`) REFERENCES `resolucion` (`id`);

--
-- Filtros para la tabla `alumno_comision`
--
ALTER TABLE `alumno_comision`
  ADD CONSTRAINT `fk_alumno_comision` FOREIGN KEY (`comision`) REFERENCES `comision` (`id`),
  ADD CONSTRAINT `fk_alumno_comision_alumno` FOREIGN KEY (`alumno`) REFERENCES `alumno` (`id`);

--
-- Filtros para la tabla `asignacion_planilla_docente`
--
ALTER TABLE `asignacion_planilla_docente`
  ADD CONSTRAINT `fk_asignacion_planilla_docente_planilla_docente` FOREIGN KEY (`planilla_docente`) REFERENCES `planilla_docente` (`id`),
  ADD CONSTRAINT `fk_asignacion_planilla_docente_toma` FOREIGN KEY (`toma`) REFERENCES `toma` (`id`);

--
-- Filtros para la tabla `calificacion`
--
ALTER TABLE `calificacion`
  ADD CONSTRAINT `calificacion_alumno_FK` FOREIGN KEY (`alumno`) REFERENCES `alumno` (`id`),
  ADD CONSTRAINT `calificacion_curso_fk` FOREIGN KEY (`curso`) REFERENCES `curso` (`id`),
  ADD CONSTRAINT `calificacion_disposicion_FK` FOREIGN KEY (`disposicion`) REFERENCES `disposicion` (`id`);

--
-- Filtros para la tabla `centro_educativo`
--
ALTER TABLE `centro_educativo`
  ADD CONSTRAINT `fk_centro_educativo_domicilio1` FOREIGN KEY (`domicilio`) REFERENCES `domicilio` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `comision`
--
ALTER TABLE `comision`
  ADD CONSTRAINT `fk_comision_calendario1` FOREIGN KEY (`calendario`) REFERENCES `calendario` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_comision_comision1` FOREIGN KEY (`comision_siguiente`) REFERENCES `comision` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_comision_modalidad1` FOREIGN KEY (`modalidad`) REFERENCES `modalidad` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_comision_planificacion1` FOREIGN KEY (`planificacion`) REFERENCES `planificacion` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_comision_sede1` FOREIGN KEY (`sede`) REFERENCES `sede` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `comision_relacionada`
--
ALTER TABLE `comision_relacionada`
  ADD CONSTRAINT `comision_relacionada_comision_FK` FOREIGN KEY (`comision`) REFERENCES `comision` (`id`),
  ADD CONSTRAINT `comision_relacionada_relacion_FK` FOREIGN KEY (`relacion`) REFERENCES `comision` (`id`);

--
-- Filtros para la tabla `contralor`
--
ALTER TABLE `contralor`
  ADD CONSTRAINT `fk_contralor_planilla_docente1` FOREIGN KEY (`planilla_docente`) REFERENCES `planilla_docente` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `curso`
--
ALTER TABLE `curso`
  ADD CONSTRAINT `curso_disposicion_FK` FOREIGN KEY (`disposicion`) REFERENCES `disposicion` (`id`),
  ADD CONSTRAINT `fk_curso_comision1` FOREIGN KEY (`comision`) REFERENCES `comision` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `designacion`
--
ALTER TABLE `designacion`
  ADD CONSTRAINT `fk_designacion_cargo1` FOREIGN KEY (`cargo`) REFERENCES `cargo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_designacion_persona1` FOREIGN KEY (`persona`) REFERENCES `persona` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_designacion_sede1` FOREIGN KEY (`sede`) REFERENCES `sede` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `detalle_persona`
--
ALTER TABLE `detalle_persona`
  ADD CONSTRAINT `fk_detalle_persona_persona1` FOREIGN KEY (`persona`) REFERENCES `persona` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_info_persona_file1` FOREIGN KEY (`archivo`) REFERENCES `file` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `disposicion`
--
ALTER TABLE `disposicion`
  ADD CONSTRAINT `fk_disposicion_asignatura` FOREIGN KEY (`asignatura`) REFERENCES `asignatura` (`id`),
  ADD CONSTRAINT `fk_disposicion_planificacion` FOREIGN KEY (`planificacion`) REFERENCES `planificacion` (`id`);

--
-- Filtros para la tabla `disposicion_pendiente`
--
ALTER TABLE `disposicion_pendiente`
  ADD CONSTRAINT `disposicion_pendiente_alumno_FK` FOREIGN KEY (`alumno`) REFERENCES `alumno` (`id`),
  ADD CONSTRAINT `disposicion_pendiente_disposicion_FK` FOREIGN KEY (`disposicion`) REFERENCES `disposicion` (`id`);

--
-- Filtros para la tabla `distribucion_horaria`
--
ALTER TABLE `distribucion_horaria`
  ADD CONSTRAINT `distribucion_horaria_disposicion_FK` FOREIGN KEY (`disposicion`) REFERENCES `disposicion` (`id`);

--
-- Filtros para la tabla `email`
--
ALTER TABLE `email`
  ADD CONSTRAINT `fk_email_persona1` FOREIGN KEY (`persona`) REFERENCES `persona` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `horario`
--
ALTER TABLE `horario`
  ADD CONSTRAINT `fk_horario_curso1` FOREIGN KEY (`curso`) REFERENCES `curso` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_horario_dia1` FOREIGN KEY (`dia`) REFERENCES `dia` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `persona`
--
ALTER TABLE `persona`
  ADD CONSTRAINT `fk_persona_domicilio1` FOREIGN KEY (`domicilio`) REFERENCES `domicilio` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `planificacion`
--
ALTER TABLE `planificacion`
  ADD CONSTRAINT `fk_planificacion_plan1` FOREIGN KEY (`plan`) REFERENCES `plan` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `sede`
--
ALTER TABLE `sede`
  ADD CONSTRAINT `fk_sede_centro_educativo1` FOREIGN KEY (`centro_educativo`) REFERENCES `centro_educativo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_sede_domicilio1` FOREIGN KEY (`domicilio`) REFERENCES `domicilio` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_sede_sede1` FOREIGN KEY (`organizacion`) REFERENCES `sede` (`id`),
  ADD CONSTRAINT `fk_sede_tipo_sede1` FOREIGN KEY (`tipo_sede`) REFERENCES `tipo_sede` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `telefono`
--
ALTER TABLE `telefono`
  ADD CONSTRAINT `fk_telefono_persona1` FOREIGN KEY (`persona`) REFERENCES `persona` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `toma`
--
ALTER TABLE `toma`
  ADD CONSTRAINT `fk_toma_curso1` FOREIGN KEY (`curso`) REFERENCES `curso` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_toma_persona1` FOREIGN KEY (`docente`) REFERENCES `persona` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_toma_persona2` FOREIGN KEY (`reemplazo`) REFERENCES `persona` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_toma_planilla_docente1` FOREIGN KEY (`planilla_docente`) REFERENCES `planilla_docente` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
