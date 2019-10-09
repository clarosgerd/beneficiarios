-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 09, 2019 at 08:14 PM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `benificiaries`
--

-- --------------------------------------------------------

--
-- Table structure for table `actividad`
--

CREATE TABLE `actividad` (
  `id` int(11) NOT NULL,
  `id_sector` int(11) NOT NULL,
  `id_tipoactividad` int(11) NOT NULL,
  `organizador` int(11) NOT NULL,
  `nombreactividad` varchar(100) NOT NULL,
  `nombrelocal` varchar(100) NOT NULL,
  `direccionlocal` varchar(100) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `horasprogramadas` varchar(100) NOT NULL,
  `id_persona` int(11) NOT NULL,
  `contenido` varchar(100) NOT NULL,
  `observaciones` varchar(100) DEFAULT NULL,
  `id_centro` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `actividad`
--

INSERT INTO `actividad` (`id`, `id_sector`, `id_tipoactividad`, `organizador`, `nombreactividad`, `nombrelocal`, `direccionlocal`, `fecha_inicio`, `fecha_fin`, `horasprogramadas`, `id_persona`, `contenido`, `observaciones`, `id_centro`) VALUES
(1, 5, 2, 0, 'Formando capacidades en Audiologia', 'Casa Campestre', 'Av. Reducto', '2019-04-17', '2019-04-18', '16', 0, 'Prueba', 'Pruebas', 0),
(2, 5, 2, 0, 'Formando capacidades en Audiologia', 'Casa Campestre', 'Av. Reducto', '2019-04-11', '2019-04-24', '4', 0, 'Pruebas', 'Pruebas', 0),
(3, 3, 1, 1, 'xxxxxxxxx', 'xxxxxxxxxxxx', 'xxxxxxxxxxxx', '2019-04-01', '2019-04-27', '12344', 0, 'dasdada', 'dsfsdf', 0),
(5, 4, 4, 1, 'CENTRO DE ESTUDIANTES', 'AUDIOLOGIA', 'GENERAL ACHA Y SUIPACHA', '2019-04-25', '2019-04-29', '30 MIN POR DIA', 13, 'ELECCIÓN DEL CENTRO DE ESTUDIANTES AUDIOLOGIA', 'NINGUNA', 0),
(6, 4, 1, 1, 'JORNADA PEGADOGICA', 'AUDIOLOGIA', 'GENERAL ACHA Y SUIPACHA', '2019-04-24', '2019-04-24', '4', 14, 'XXXXXXX', 'FACILITADOR ALTERON.....', 0),
(7, 4, 6, 1, 'costurs', 'ddd', 'ddd', '2019-04-01', '2019-05-04', '12', 0, 'asdasdsa', 'asdasd', 0),
(8, 3, 10, 3, 'cuidado del oido', 'xcz', 'zxczx', '2019-04-24', '2019-04-16', '423', 0, 'wfwewf', NULL, 0),
(9, 5, 11, 3, 'Genero', 'asdas', 'asd', '2019-04-26', '2019-04-19', '23', 0, 'dczxczxc', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `apoderado`
--

CREATE TABLE `apoderado` (
  `id` int(11) NOT NULL,
  `parentesco` varchar(100) DEFAULT NULL,
  `apellidopaterno` varchar(100) NOT NULL,
  `apellidomaterno` varchar(100) NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `ci` varchar(15) NOT NULL,
  `fechanacimiento` date NOT NULL,
  `sexo` int(11) NOT NULL,
  `dirección` varchar(100) DEFAULT NULL,
  `celular` int(11) DEFAULT NULL,
  `ocupación` varchar(100) DEFAULT NULL,
  `observaciones` varchar(100) DEFAULT NULL,
  `id_centro` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `apoderado`
--

INSERT INTO `apoderado` (`id`, `parentesco`, `apellidopaterno`, `apellidomaterno`, `nombres`, `ci`, `fechanacimiento`, `sexo`, `dirección`, `celular`, `ocupación`, `observaciones`, `id_centro`) VALUES
(1, 'sfhsdfkhskh', 'fksjdfsj', 'fsdjfklsj', 'fjsdjfk', '3883838', '1990-01-01', 0, '53534', 294729473, 'kjshfhsk', 'fsdfsd', 0),
(2, 'rwerewr', 'eee', 'ee', 'ee', '342342', '2019-04-18', 0, '4234', 4234, 'dsff', 'fsdf', 0);

-- --------------------------------------------------------

--
-- Table structure for table `atencion`
--

CREATE TABLE `atencion` (
  `id` int(11) NOT NULL,
  `id_neonato` int(11) DEFAULT NULL,
  `id_otros` int(11) DEFAULT NULL,
  `id_escolar` int(11) DEFAULT NULL,
  `id_especialista` int(11) NOT NULL,
  `id_referencia` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `atencion`
--

INSERT INTO `atencion` (`id`, `id_neonato`, `id_otros`, `id_escolar`, `id_especialista`, `id_referencia`) VALUES
(1, NULL, NULL, NULL, 0, NULL),
(2, NULL, NULL, NULL, 0, NULL),
(3, 1, NULL, NULL, 11111, NULL),
(4, 1, NULL, NULL, 11111, NULL),
(5, 1, NULL, NULL, 11111, NULL),
(6, 1, NULL, NULL, 11111, NULL),
(7, 1, NULL, NULL, 11111, NULL),
(8, 1, NULL, NULL, 11111, NULL),
(9, 1, NULL, NULL, 11111, NULL),
(10, 1, NULL, NULL, 11111, NULL),
(11, 1, NULL, NULL, 11111, NULL),
(12, 1, NULL, NULL, 11111, NULL),
(13, 1, NULL, NULL, 11111, NULL),
(14, 1, NULL, NULL, 11111, NULL),
(15, 1, NULL, NULL, 11111, NULL),
(16, 1, NULL, NULL, 11111, NULL),
(17, 1, NULL, NULL, 11111, NULL),
(18, 1, NULL, NULL, 11111, NULL),
(19, 1, NULL, NULL, 11111, NULL),
(20, 1, NULL, NULL, 11111, NULL),
(21, 1, NULL, NULL, 11111, NULL),
(22, 1, NULL, NULL, 11111, NULL),
(23, 1, NULL, NULL, 11111, NULL),
(24, 1, NULL, NULL, 11111, NULL),
(25, 1, NULL, NULL, 11111, NULL),
(26, 1, NULL, NULL, 11111, NULL),
(27, 1, NULL, NULL, 11111, NULL),
(28, 1, NULL, NULL, 11111, NULL),
(29, 1, NULL, NULL, 11111, NULL),
(30, 1, NULL, NULL, 11111, NULL),
(31, 1, NULL, NULL, 11115, NULL),
(32, 1, NULL, NULL, 11114, NULL),
(33, 1, NULL, NULL, 11116, NULL),
(34, 1, NULL, NULL, 11116, NULL),
(35, 1, NULL, NULL, 11116, NULL),
(36, 1, NULL, NULL, 11117, NULL),
(37, 1, NULL, NULL, 11117, NULL),
(38, NULL, NULL, 1, 11115, NULL),
(39, 1, NULL, NULL, 11117, NULL),
(40, 1, NULL, NULL, 11116, NULL),
(41, 1, NULL, NULL, 11117, NULL),
(42, 2, NULL, NULL, 11117, NULL),
(43, 2, NULL, NULL, 11117, NULL),
(44, NULL, NULL, 1, 11116, NULL),
(45, 2, NULL, NULL, 11117, NULL),
(46, 2, NULL, NULL, 11115, NULL),
(47, NULL, NULL, NULL, 11117, NULL),
(48, NULL, NULL, 1, 11117, NULL),
(49, NULL, NULL, 2, 11115, NULL),
(50, NULL, NULL, 1, 11117, NULL),
(51, NULL, NULL, 2, 11115, NULL),
(52, 3, NULL, NULL, 11115, NULL),
(53, NULL, NULL, 1, 11117, NULL),
(54, 3, NULL, NULL, 11117, NULL),
(55, NULL, NULL, NULL, 11117, NULL),
(56, NULL, NULL, 3, 11116, NULL),
(57, NULL, NULL, NULL, 11116, NULL),
(58, NULL, NULL, NULL, 11115, NULL),
(59, NULL, NULL, NULL, 11115, NULL),
(60, NULL, NULL, NULL, 11115, NULL),
(61, NULL, NULL, 2, 11115, NULL),
(62, NULL, NULL, 1, 11114, NULL),
(63, 3, NULL, NULL, 11117, NULL),
(64, NULL, 2, NULL, 11117, NULL),
(65, NULL, NULL, 3, 11116, NULL),
(66, NULL, NULL, 4, 11119, NULL),
(67, NULL, NULL, 1, 11120, NULL),
(68, NULL, 3, NULL, 11120, NULL),
(69, NULL, NULL, 4, 11120, NULL),
(70, NULL, 3, NULL, 11120, NULL),
(71, NULL, NULL, 1, 11119, NULL),
(72, NULL, NULL, 4, 11116, NULL),
(73, 1, NULL, NULL, 11121, NULL),
(74, 1, NULL, NULL, 11121, NULL),
(75, NULL, 1, NULL, 11122, NULL),
(76, 1, NULL, NULL, 11120, NULL),
(77, 1, NULL, NULL, 11119, NULL),
(78, NULL, NULL, NULL, 11111, NULL),
(79, NULL, NULL, NULL, 11116, NULL),
(80, NULL, NULL, NULL, 11117, NULL),
(81, NULL, NULL, NULL, 11117, NULL),
(82, NULL, NULL, NULL, 11119, NULL),
(83, NULL, 3, NULL, 11120, NULL);

-- --------------------------------------------------------

--
-- Stand-in structure for view `atencionescolaraudiologia`
-- (See below for the actual view)
--
CREATE TABLE `atencionescolaraudiologia` (
`id` int(11)
,`id_escolar` int(11)
,`id_especialista` int(11)
,`id_referencia` int(11)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `atencionneonatoaudiologia`
-- (See below for the actual view)
--
CREATE TABLE `atencionneonatoaudiologia` (
`id1` int(11)
,`id_neonato` int(11)
,`id_especialista` int(11)
,`id_referencia` int(11)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `atencionotrosaudiologia`
-- (See below for the actual view)
--
CREATE TABLE `atencionotrosaudiologia` (
`id` int(11)
,`id_otros` int(11)
,`id_especialista` int(11)
,`id_referencia` int(11)
);

-- --------------------------------------------------------

--
-- Table structure for table `audiologia`
--

CREATE TABLE `audiologia` (
  `id` int(11) NOT NULL,
  `id_especialista` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `id_escolar` int(11) DEFAULT NULL,
  `id_neonato` int(11) DEFAULT NULL,
  `id_atencion` int(11) NOT NULL,
  `id_otros` int(11) DEFAULT NULL,
  `especialidad` varchar(20) DEFAULT NULL,
  `observaciones` int(11) DEFAULT NULL,
  `id_centro` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `audiologia`
--

INSERT INTO `audiologia` (`id`, `id_especialista`, `fecha`, `id_escolar`, `id_neonato`, `id_atencion`, `id_otros`, `especialidad`, `observaciones`, `id_centro`) VALUES
(1, 11111, '2019-03-31', NULL, 1, 23, NULL, '0', NULL, 1),
(4, 0, '2019-04-02', NULL, 0, 0, NULL, '0', NULL, 1),
(5, 11111, '2019-04-02', NULL, 1, 27, NULL, '0', NULL, 1),
(6, 11111, '2019-04-03', NULL, 1, 28, NULL, '0', NULL, 1),
(7, 11111, '2019-04-03', NULL, 1, 29, NULL, '', NULL, 1),
(8, 11111, '2019-04-03', NULL, 1, 30, NULL, 'AUDIO', NULL, 1),
(9, 11115, '2019-04-03', NULL, 1, 31, NULL, 'AUDIOLOGIA', NULL, 1),
(10, 11114, '2019-04-03', NULL, 1, 32, NULL, 'AUDIOLOGIA', NULL, 1),
(11, 11116, '2019-04-03', NULL, 1, 33, NULL, 'AUDIOLOGIA', NULL, 1),
(12, 11116, '2019-04-03', NULL, 1, 34, NULL, 'AUDIOLOGIA', NULL, 1),
(13, 11116, '2019-04-04', NULL, 1, 35, NULL, 'AUDIOLOGIA', NULL, 1),
(14, 11117, '2019-04-04', NULL, 1, 36, NULL, 'PSICOLOGIA', NULL, 1),
(15, 11117, '2019-04-04', NULL, 1, 37, NULL, 'PSICOLOGIA', NULL, 1),
(16, 0, '2019-04-04', NULL, 0, 0, NULL, '', NULL, 1),
(17, 0, '2019-04-04', NULL, 0, 0, NULL, '', NULL, 1),
(18, 0, '2019-04-04', NULL, 0, 0, NULL, '', NULL, 1),
(19, 11115, '2019-04-04', 1, NULL, 38, NULL, 'AUDIOLOGIA', NULL, 1),
(20, 0, '2019-04-04', NULL, 0, 0, NULL, '', NULL, 1),
(21, 11117, '2019-04-04', NULL, 1, 39, NULL, 'PSICOLOGIA', NULL, 1),
(22, 11116, '2019-04-04', NULL, 1, 40, NULL, 'AUDIOLOGIA', NULL, 2),
(23, 11117, '2019-04-06', NULL, 1, 41, NULL, 'PSICOLOGIA', NULL, NULL),
(24, 11117, '2019-04-06', NULL, 2, 42, NULL, 'PSICOLOGIA', NULL, NULL),
(25, 11117, '2019-04-06', NULL, 2, 43, NULL, 'PSICOLOGIA', NULL, 1),
(26, 11116, '2019-04-06', 1, NULL, 44, NULL, 'AUDIOLOGIA', NULL, NULL),
(27, 11117, '2019-04-06', NULL, 2, 45, NULL, 'PSICOLOGIA', NULL, 4),
(28, 11115, '2019-04-09', NULL, 2, 46, NULL, 'AUDIOLOGIA', NULL, 0),
(29, 11117, '2019-04-09', NULL, NULL, 47, 0, 'PSICOLOGIA', NULL, 0),
(30, 11115, '2019-04-09', NULL, 3, 52, NULL, 'AUDIOLOGIA', NULL, 0),
(31, 11117, '2019-04-09', NULL, 3, 54, NULL, 'PSICOLOGIA', NULL, 2),
(32, 11117, '2019-04-09', NULL, NULL, 55, 0, 'PSICOLOGIA', NULL, 2),
(33, 11116, '2019-04-09', 3, NULL, 56, NULL, 'AUDIOLOGIA', NULL, 2),
(34, 11116, '2019-04-09', NULL, NULL, 57, 0, 'AUDIOLOGIA', NULL, 2),
(35, 11115, '2019-04-09', NULL, NULL, 58, 0, 'AUDIOLOGIA', NULL, 2),
(36, 11115, '2019-04-09', NULL, NULL, 59, 0, 'AUDIOLOGIA', NULL, 2),
(37, 11115, '2019-04-09', NULL, NULL, 60, 0, 'AUDIOLOGIA', NULL, 2),
(38, 11115, '2019-04-09', 2, NULL, 61, NULL, 'AUDIOLOGIA', NULL, 0),
(39, 11114, '2019-04-09', 1, NULL, 62, NULL, 'OFTALMOLOGÍA', NULL, 1),
(40, 11117, '2019-04-09', NULL, 3, 63, NULL, 'PSICOLOGIA', NULL, 2),
(41, 11117, '2019-04-09', NULL, NULL, 64, 2, 'PSICOLOGIA', NULL, 2),
(42, 11116, '2019-04-09', 3, NULL, 65, NULL, 'AUDIOLOGIA', NULL, 2),
(43, 0, '2019-04-10', NULL, 0, 0, NULL, '', NULL, NULL),
(44, 11119, '2019-04-10', 4, NULL, 66, NULL, 'FISIOTERAPIA', NULL, 3),
(45, 11120, '2019-04-10', 1, NULL, 67, NULL, 'AUDIOLOGIA', NULL, 3),
(46, 11120, '2019-04-15', NULL, NULL, 68, 3, 'AUDIOLOGIA', NULL, 3),
(47, 11120, '2019-04-18', 4, NULL, 69, NULL, 'AUDIOLOGIA', NULL, 3),
(48, 11120, '2019-04-18', NULL, NULL, 70, 3, 'AUDIOLOGIA', NULL, 3),
(49, 11119, '2019-04-18', 1, NULL, 71, NULL, 'FISIOTERAPIA', NULL, 3),
(50, 0, '2019-04-27', NULL, 0, 0, NULL, '', NULL, NULL),
(51, 0, '2019-05-02', NULL, 0, 0, NULL, '', NULL, NULL),
(52, 11116, '2019-05-24', 4, NULL, 72, NULL, 'AUDIOLOGIA', NULL, 0),
(53, 11121, '2019-05-24', NULL, 1, 73, NULL, 'Estimulacion LSB', NULL, 0),
(54, 11121, '2019-05-24', NULL, 1, 74, NULL, 'Estimulacion LSB', NULL, 0),
(55, 0, '2019-05-24', NULL, 0, 0, NULL, '', NULL, NULL),
(56, 11122, '2019-05-24', NULL, NULL, 75, 1, 'Xxxxx', NULL, 0),
(57, 11120, '2019-05-28', NULL, 1, 76, NULL, 'AUDIOLOGIA', NULL, 0),
(58, 11119, '2019-05-28', NULL, 1, 77, NULL, 'FISIOTERAPIA', NULL, 0),
(59, 11111, '2019-05-28', NULL, NULL, 78, 0, 'AUDIOLOGIA', NULL, 0),
(60, 11116, '2019-05-28', NULL, NULL, 79, 0, 'AUDIOLOGIA', NULL, 0),
(61, 11117, '2019-05-28', NULL, NULL, 80, 0, 'PSICOLOGIA', NULL, 0),
(62, 11117, '2019-05-28', NULL, NULL, 81, 0, 'PSICOLOGIA', NULL, 0),
(63, 11119, '2019-05-28', NULL, NULL, 82, 0, 'FISIOTERAPIA', NULL, 0),
(64, 11120, '2019-05-28', NULL, NULL, 83, 3, 'AUDIOLOGIA', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `categoria`
--

CREATE TABLE `categoria` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `categoria`
--

INSERT INTO `categoria` (`id`, `nombre`) VALUES
(3, 'Adulto'),
(4, 'NNAA'),
(5, 'Estudiante Secundaria'),
(6, 'Estudiante Primaria'),
(7, 'Maestros de unidades educativas regulares'),
(8, 'Autoridades de Gobierno'),
(9, 'Personas del programa'),
(10, 'capacitados en CPOA'),
(11, 'Marstros UE Especiales'),
(12, 'Público en general'),
(13, 'Familiares');

-- --------------------------------------------------------

--
-- Table structure for table `centros`
--

CREATE TABLE `centros` (
  `id` int(11) NOT NULL,
  `nombreinstitucion` varchar(100) NOT NULL,
  `ciudad` varchar(20) NOT NULL,
  `municipio` int(11) NOT NULL,
  `provincia` int(11) NOT NULL,
  `direccion` varchar(100) NOT NULL,
  `telefono` varchar(100) NOT NULL,
  `id_persona` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `centros`
--

INSERT INTO `centros` (`id`, `nombreinstitucion`, `ciudad`, `municipio`, `provincia`, `direccion`, `telefono`, `id_persona`) VALUES
(1, 'Audiologico', 'COCHABAMBA', 1, 1, 'Av. America 2343', '2324324', 3),
(2, 'PiscologosMundi', 'COCHABAMBA', 1, 1, 'Av. Libertador 2321', '5645645631', 4),
(3, 'Altiora', 'COCHABAMBA', 1, 1, 'Aniceto Padilla #54', '6543346', 8),
(4, 'PruebaCentro Nuevo', 'COCHABAMBA', 1, 3, 'calle de rosas', '2343534', 7),
(5, '234 Prueba de institucion con un maximo de varios caracteres.', 'COCHABAMBA', 2, 3, '234 Prueba de institucion con un maximo de varios caracteres.', '2345678', 9);

-- --------------------------------------------------------

--
-- Table structure for table `ciudad`
--

CREATE TABLE `ciudad` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `curso`
--

CREATE TABLE `curso` (
  `id` int(11) NOT NULL,
  `curso` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `curso`
--

INSERT INTO `curso` (`id`, `curso`) VALUES
(1, '1 Inicial'),
(2, '2 Inicial'),
(3, '1 Primaria'),
(4, '2 Primaria'),
(5, '3 Primaria'),
(6, '4 Primaria'),
(7, '5 Primaria'),
(8, '6A Primaria'),
(9, '6 B'),
(10, 'PRIMERO'),
(11, 'SEGUNDO'),
(12, 'TERCERO'),
(13, 'CUARTO'),
(14, 'QUINTO'),
(15, 'SEXTO');

-- --------------------------------------------------------

--
-- Table structure for table `departamento`
--

CREATE TABLE `departamento` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `departamento`
--

INSERT INTO `departamento` (`id`, `nombre`) VALUES
(1, 'Pando'),
(2, 'Cochabamba'),
(3, 'Beni'),
(4, 'Santa Cruz'),
(5, 'La Paz'),
(6, 'Tarija'),
(7, 'Potosi'),
(8, 'Chuquisaca'),
(9, 'Oruro');

-- --------------------------------------------------------

--
-- Table structure for table `derivacion`
--

CREATE TABLE `derivacion` (
  `id` int(11) NOT NULL,
  `id_audiologia` int(11) NOT NULL,
  `id_tipoespecialidad` int(11) DEFAULT NULL,
  `tipoderivacion` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `derivacion`
--

INSERT INTO `derivacion` (`id`, `id_audiologia`, `id_tipoespecialidad`, `tipoderivacion`) VALUES
(1, 1, 1, 'INTERNA'),
(2, 19, 7, 'INTERNA'),
(3, 28, 5, 'INTERNA'),
(4, 28, 8, 'EXTERNA'),
(5, 44, 2, 'INTERNA'),
(6, 45, 8, 'EXTERNA'),
(7, 47, 5, 'EXTERNA'),
(8, 49, 10, 'INTERNA'),
(9, 52, 2, 'INTERNA'),
(10, 1, NULL, 'INTERNA');

-- --------------------------------------------------------

--
-- Table structure for table `diagnosticoaudiologia`
--

CREATE TABLE `diagnosticoaudiologia` (
  `id` int(11) NOT NULL,
  `id_audiologia` int(11) NOT NULL,
  `id_tipodiagnosticoaudiologia` int(11) DEFAULT NULL,
  `resultado` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `diagnosticoaudiologia`
--

INSERT INTO `diagnosticoaudiologia` (`id`, `id_audiologia`, `id_tipodiagnosticoaudiologia`, `resultado`) VALUES
(1, 1, 1, 'jhgjhgjgjh'),
(2, 5, 2, 'sfsdfsdfsdfsdfsfdfsdfsdsfs'),
(3, 19, 9, 'dotarse de un audífono'),
(4, 28, 1, 'sdadasda as dasda'),
(5, 28, 8, 'safs f sdfs sdf sdf'),
(6, 28, 6, 'sd f aad ads asdadasd'),
(7, 44, 10, 'xxxxx'),
(8, 45, 5, 'ORL'),
(9, 47, 4, '3333'),
(10, 52, 9, 'Aidofonos'),
(11, 30, 9, NULL),
(12, 58, 1, NULL),
(13, 1, 9, 'assdasda');

-- --------------------------------------------------------

--
-- Table structure for table `discapacidad`
--

CREATE TABLE `discapacidad` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `discapacidad`
--

INSERT INTO `discapacidad` (`id`, `nombre`) VALUES
(1, 'SI'),
(2, 'NO');

-- --------------------------------------------------------

--
-- Table structure for table `docente`
--

CREATE TABLE `docente` (
  `id` int(11) NOT NULL,
  `id_departamento` int(11) NOT NULL,
  `unidadeducativa` int(11) NOT NULL,
  `apellidopaterno` varchar(100) NOT NULL,
  `apellidomaterno` varchar(100) NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `nrodiscapacidad` varchar(15) DEFAULT NULL,
  `ci` varchar(15) DEFAULT NULL,
  `fechanacimiento` date NOT NULL,
  `sexo` int(11) NOT NULL,
  `celular` varchar(100) DEFAULT NULL,
  `materias` varchar(100) DEFAULT NULL,
  `discapacidad` int(11) NOT NULL,
  `tipodiscapacidad` int(11) NOT NULL,
  `id_centro` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `docente`
--

INSERT INTO `docente` (`id`, `id_departamento`, `unidadeducativa`, `apellidopaterno`, `apellidomaterno`, `nombres`, `nrodiscapacidad`, `ci`, `fechanacimiento`, `sexo`, `celular`, `materias`, `discapacidad`, `tipodiscapacidad`, `id_centro`) VALUES
(1, 2, 7, 'Portal', 'Vera', 'María del Rosario', 'Xxxx', '4138402', '1964-10-10', 0, '74123886', 'Técnica Femenina', 1, 4, 0),
(2, 2, 7, 'Rojas', 'Saravia', 'Lourdes', 'Xxxx', '4520448', '1978-02-05', 0, '70306075', '1 Inicial', 2, 7, 0),
(3, 2, 7, 'Alarcón', 'Camargo', 'María Elena', 'Xxxxxx', '3433257', '1970-04-02', 0, '70712730', '2 inicial', 2, 7, 0),
(4, 2, 7, 'CASTRO', 'TOLA', 'RUTH DIANA', 'XXX', '4458924', '1987-10-23', 0, '68459627', 'LSB', 1, 4, 0),
(5, 2, 7, 'MORENO', 'PINTO', 'SANDRA LENY', 'XXXXXXX', '3027077', '1965-05-06', 0, '77498882', 'LITERATURA', 2, 7, 0),
(6, 2, 7, 'CATARI', 'CONDORI', 'LULA VERONICA', 'XXXX', '6724664', '1983-07-07', 0, '79725970', 'DAU 3º PRIMARIA', 2, 7, 0),
(7, 2, 7, 'Delgadillo', 'Perez', 'Mario Ronald', 'Xxxx', '4411836', '1973-09-09', 0, '69506520', 'DAN 4 primaria', 1, 4, 0),
(8, 2, 7, 'Camacho', 'Arnez', 'Carmen Florinda', 'Xxx', '831778', '1955-04-03', 0, '44231515', 'DÍA 5 primaria', 2, 7, 0),
(9, 2, 7, 'Abasto', 'Encinas', 'Hancy Gloria', 'Xxxx', '983830', '1959-12-28', 0, '77935595', 'DAU 1 primaria', 2, 7, 0),
(10, 2, 7, 'Villca', 'Mérida', 'Daniela Evelin', 'Xxxx', '7882563', '1987-08-31', 0, '61676314', 'Educación Física', 2, 7, 0),
(11, 2, 7, 'Nieto', 'Macuaco', 'Votaría Miriam', 'Xxxx', '3501436', '1963-06-15', 0, '72799355', 'DAU 6 B primara', 2, 7, 0),
(12, 2, 7, 'Bacarreza', 'Bohorquez', 'Wilmer', 'Xxxx', '8146722', '1979-10-18', 0, '68459627', 'LSB', 1, 4, 0),
(13, 2, 7, 'Moron', 'Robles', 'María Ibeth', 'Xxxx', '3243608', '1967-01-28', 0, '72724358', 'DAU 2 primaria', 2, 7, 0),
(14, 2, 7, 'Estaca', 'Huayta', 'Marcia Daniela', 'Xxxx', '9318867', '1993-08-17', 0, '70785230', 'Sociales Secundaria', 2, 7, 0),
(15, 2, 7, 'Sansusty', 'Zapata', 'Ruth', 'Xxxx', '2347186', '1952-09-27', 0, '79954661', 'DAU 6 A primaria', 2, 7, 0),
(16, 2, 7, 'Flores', 'Condori', 'María Angelica', 'Xxxx', '4021441', '1978-04-25', 0, '72289830', 'Artes Plásticas', 2, 7, 0),
(17, 2, 7, 'Alanes', 'Guzman', 'Gregoria', 'Xxxxx', '6237100', '1979-05-09', 0, 'Xxxxxx', NULL, 2, 7, 0),
(18, 2, 7, 'CAMACHO', 'ZAPATA', 'ORLANDO', 'Xxxxx', '3039817', '1964-08-29', 0, '70737046', 'MATEMÁTICAS Y FISICA', 2, 7, 0),
(19, 2, 7, 'Peñaloza', 'León', 'María Eugenia', 'Xxxx', '800187-1N', '1952-11-05', 0, '79553758', 'Química Biología', 2, 7, 0),
(20, 2, 7, 'Medrano', 'Jimenez', 'Tania Daniela', 'Xxxx', '6558706', '1988-05-09', 0, '65309845', 'Lenguaje', 2, 7, 0),
(21, 2, 7, 'Mariaca', 'Veizaga', 'Jhenny', 'Xxxx', '5286306', '1981-05-05', 0, 'Xxxxx', 'Filosofía Psicología', 2, 7, 0),
(22, 2, 7, 'Lucana', 'Siles', 'Kerin', 'Xxxx', '5825576', '1981-09-08', 0, '67520800', 'Foniatria', 2, 7, 0),
(23, 2, 7, 'Alcazar', 'Dalenz', 'María Teresa', 'Xxxx', '2732704-1C', '1963-09-03', 0, '70725666', 'Psicopedagogia', 2, 7, 0),
(24, 2, 7, 'Portal', 'Vera', 'María del Rosario', 'Xxxx', '4138402', '1964-10-10', 0, '74123886', 'Técnica Femenina', 1, 4, 0);

-- --------------------------------------------------------

--
-- Table structure for table `escolar`
--

CREATE TABLE `escolar` (
  `id` int(11) NOT NULL,
  `codigorude` varchar(100) DEFAULT NULL,
  `codigorude_es` varchar(100) DEFAULT NULL,
  `fecha` date NOT NULL,
  `id_departamento` int(11) NOT NULL,
  `unidadeducativa` int(11) NOT NULL,
  `apellidopaterno` varchar(100) NOT NULL,
  `apellidomaterno` varchar(100) NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `ci` varchar(15) NOT NULL,
  `nrodiscapacidad` varchar(100) DEFAULT NULL,
  `fechanacimiento` date NOT NULL,
  `sexo` varchar(100) DEFAULT NULL,
  `curso` varchar(100) NOT NULL,
  `id_discapacidad` int(11) NOT NULL,
  `id_tipodiscapacidad` int(11) DEFAULT NULL,
  `resultado` varchar(100) NOT NULL,
  `resultadotamizaje` varchar(100) DEFAULT NULL,
  `tapon` varchar(10) DEFAULT NULL,
  `tapodonde` varchar(100) DEFAULT NULL,
  `repetirprueba` varchar(100) DEFAULT NULL,
  `observaciones` varchar(200) DEFAULT NULL,
  `id_apoderado` int(11) DEFAULT NULL,
  `id_referencia` int(11) DEFAULT NULL,
  `id_centro` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `escolar`
--

INSERT INTO `escolar` (`id`, `codigorude`, `codigorude_es`, `fecha`, `id_departamento`, `unidadeducativa`, `apellidopaterno`, `apellidomaterno`, `nombres`, `ci`, `nrodiscapacidad`, `fechanacimiento`, `sexo`, `curso`, `id_discapacidad`, `id_tipodiscapacidad`, `resultado`, `resultadotamizaje`, `tapon`, `tapodonde`, `repetirprueba`, `observaciones`, `id_apoderado`, `id_referencia`, `id_centro`) VALUES
(1, NULL, NULL, '2019-04-03', 0, 1, 'test', 'test', 'nombre', '8382737', NULL, '2019-04-01', NULL, '3yye', 1, 1, 'PASO', 'sfkjsdfjsdhk', 'SI', 'IZQUIERDA', 'SI', 'sdfjskdhk', NULL, NULL, 0),
(2, NULL, NULL, '2019-04-03', 0, 1, 'djhkasjhdkha', 'dshakfhk', 'kjhkhakjdhf', '23479237', NULL, '2019-04-01', NULL, 'kjsdhakhd', 1, 1, 'PASO', 'dsfsldhl', 'SI', 'IZQUIERDA', 'SI', 'sdfhsdhk', NULL, NULL, 0),
(3, '2345324234234', '234234234234', '2019-04-03', 0, 3, 'werwerwerv wr wrwerwerwerwer wer', 'werwerwerv wr wrwerwerwerwer wer', 'werwerwerv wr wrwerwerwerwer wer', '234234234234', '23524234234', '2019-04-10', 'FEMENINO', '34', 1, 2, 'PASO', '345354534534', 'SI', 'IZQUIERDA', 'SI', '345345345345', 2, 2, 0),
(4, NULL, NULL, '2019-04-10', 2, 3, 'Zarate', 'Ramos', 'Nauel', '121144', '12323214', '2014-02-05', 'MASCULINO', '25', 1, 4, 'PASO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(5, NULL, NULL, '2019-04-10', 0, 5, 'Perez', 'Iriarte', 'Pedro', '234423424', NULL, '2019-04-02', 'MASCULINO', '32', 1, 4, 'NO PASO', 'frecuencias testeadas no paso', 'SI', 'AMBOS', 'SI', 'repetir prueba previa auraul', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `especialista`
--

CREATE TABLE `especialista` (
  `id` int(11) NOT NULL,
  `especialidad` int(11) NOT NULL,
  `id_departamento` int(11) NOT NULL,
  `apellidopaterno` varchar(100) NOT NULL,
  `apellidomaterno` varchar(100) NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `ci` varchar(15) NOT NULL,
  `fechanacimiento` date NOT NULL,
  `sexo` varchar(20) NOT NULL,
  `celular` varchar(100) NOT NULL,
  `matricula` varchar(100) NOT NULL,
  `observaciones` varchar(100) DEFAULT NULL,
  `nombreespecialidad` varchar(100) DEFAULT NULL,
  `id_centro` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `especialista`
--

INSERT INTO `especialista` (`id`, `especialidad`, `id_departamento`, `apellidopaterno`, `apellidomaterno`, `nombres`, `ci`, `fechanacimiento`, `sexo`, `celular`, `matricula`, `observaciones`, `nombreespecialidad`, `id_centro`) VALUES
(11111, 1, 2, 'carlos', 'claros', 'carlos', '934829739729', '1990-09-01', 'MASCULINO', '39127397', 'Hsjkekk28384', 'kjsdhfkjshkj', NULL, 0),
(11112, 1, 1, 'jfjfkljsldkj', 'klsjflksdj', 'juna', '4yi2y4i', '2019-04-30', 'FEMENINO', '3242342', 'dasdadas', 'fsdfsf', NULL, 0),
(11113, 1, 1, 'prueba', 'preuba', 'prueba', '492834982', '2019-04-01', 'FEMENINO', '7268243', 'fsdf', '23429832', NULL, 0),
(11114, 6, 2, 'Anzoleaga', 'Murillo', 'Marisol', '435345', '2019-04-24', 'FEMENINO', '423478', '78668', '84628364', '', 0),
(11115, 1, 1, 'gggg', 'gggg', 'gggg', '979797', '2019-04-11', 'FEMENINO', '76874682364', 'yyyy', 'jljslgjsl', 'AUDIOLOGIA', 0),
(11116, 1, 1, 'fkjsdhfkshdh', 'fsjdhfkjshk', 'test servidor', '234', '2018-10-16', 'FEMENINO', '39127397', '249273497', 'fhsdhfskdfhs', 'AUDIOLOGIA', 0),
(11117, 2, 2, 'Claure', 'Claure', 'Marcelo', '234234234', '1983-12-01', 'MASCULINO', '234234', '234523423', NULL, 'PSICOLOGIA', 0),
(11119, 9, 2, 'Garcia', 'Garcia', 'Claudia', '34578488', '1986-07-16', 'FEMENINO', '15151651', '124521', NULL, 'FISIOTERAPIA', 0),
(11120, 1, 2, 'Balderrama', 'Saire', 'Nilda', '5278172', '1993-02-06', 'FEMENINO', '65606556', '4747474747', 'Fonoaudiologa,', 'AUDIOLOGIA', 0),
(11121, 11, 5, 'Vggg', 'Vcvvv', 'Ggg', '2335567', '2019-05-21', 'MASCULINO', '45667', '3333', NULL, 'Estimulacion LSB', 0),
(11122, 12, 5, 'Ggg', 'Ggg', 'Gggggg', '24567', '2019-05-29', 'FEMENINO', '4456', '3445', NULL, 'Xxxxx', 0);

-- --------------------------------------------------------

--
-- Table structure for table `estudiante`
--

CREATE TABLE `estudiante` (
  `id` int(11) NOT NULL,
  `departamento` int(11) DEFAULT NULL,
  `provincisa` int(11) DEFAULT NULL,
  `municipio` int(11) DEFAULT NULL,
  `unidadeducativa` int(11) NOT NULL,
  `apellidopaterno` varchar(100) NOT NULL,
  `apellidomaterno` varchar(100) NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `nrodiscapacidad` varchar(15) DEFAULT NULL,
  `ci` varchar(15) DEFAULT NULL,
  `fechanacimiento` date NOT NULL,
  `sexo` varchar(100) NOT NULL,
  `discapacidad` int(11) DEFAULT NULL,
  `tipodiscapacidad` int(11) DEFAULT NULL,
  `observaciones` varchar(100) DEFAULT NULL,
  `codigorude` varchar(100) DEFAULT NULL,
  `codigorude_es` varchar(100) DEFAULT NULL,
  `id_centro` int(11) NOT NULL,
  `curso` int(11) NOT NULL,
  `gestion` int(11) NOT NULL,
  `esincritoespecial` int(11) NOT NULL DEFAULT '1',
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `estudiante`
--

INSERT INTO `estudiante` (`id`, `departamento`, `provincisa`, `municipio`, `unidadeducativa`, `apellidopaterno`, `apellidomaterno`, `nombres`, `nrodiscapacidad`, `ci`, `fechanacimiento`, `sexo`, `discapacidad`, `tipodiscapacidad`, `observaciones`, `codigorude`, `codigorude_es`, `id_centro`, `curso`, `gestion`, `esincritoespecial`, `fecha`) VALUES
(1, 2, 1, 1, 7, 'Arevalo', 'Guayacuma', 'Miguel Mateo', 'Xxxxxx', '15670725', '2004-02-08', 'MASCULINO', 1, 4, NULL, NULL, '8098044620198087', 3, 1, 2018, 1, '0000-00-00'),
(2, 2, 1, 1, 7, 'Paz', 'Lupe', 'Erick', 'Xxxxx', '15201417', '2013-01-11', 'MASCULINO', 1, 4, NULL, NULL, '8098044620166947', 3, 1, 2018, 1, '0000-00-00'),
(3, 2, 1, 1, 7, 'Rios', 'Carrión', 'Miguel Jhudid', '03-2014012JMRC', '15072278', '2014-01-20', 'MASCULINO', 1, 4, NULL, NULL, '8098044620186900', 3, 1, 2018, 1, '0000-00-00'),
(4, 2, 1, 1, 7, 'Zurita', 'Cuba', 'Damián Fernando', 'Xxxx', '14992163', '2013-04-19', 'MASCULINO', 1, 4, NULL, NULL, '8098044620195001', 3, 1, 2018, 1, '0000-00-00'),
(5, 2, 1, 1, 7, 'Toledo', 'Dorado', 'Reyna', 'Xxxx', '15607858', '2015-05-14', 'FEMENINO', 1, 4, NULL, NULL, '809804462019513', 3, 1, 2018, 1, '0000-00-00'),
(6, 2, 1, 1, 7, 'Gonzales', 'Salazar', 'Kendra Thais', 'Xxxx', 'Xxxxx', '2015-09-06', 'FEMENINO', 1, 4, NULL, NULL, '809800452018081', 3, 1, 2018, 1, '0000-00-00'),
(7, 2, 1, 1, 7, 'Huaranca', 'Cáceres', 'Abril Adriana', 'Xxxx', '14645960', '2013-02-09', 'FEMENINO', 1, 6, NULL, NULL, '809804462016471', 3, 0, 0, 1, '0000-00-00'),
(8, 2, 3, 2, 7, 'Guardia', 'Cadima', 'Jhosua', '03-20120801JGC', '12811234', '2012-08-01', 'MASCULINO', 1, 4, NULL, NULL, '809804092017027', 3, 0, 0, 1, '0000-00-00'),
(9, 2, 1, 1, 7, 'Sanchez', 'Huanca', 'Jhessica', '03-20100909JSH', '14818438', '2010-09-09', 'FEMENINO', 2, 4, NULL, NULL, '8089011720174024', 3, 0, 0, 1, '0000-00-00'),
(10, 2, 1, 1, 7, 'Avalos', 'Quisbert', 'Katherine', '03-20110922KAQ', '14265014', '2011-09-22', 'FEMENINO', 1, 4, NULL, NULL, '8098044620168391', 3, 0, 0, 1, '0000-00-00'),
(11, 2, 1, 1, 7, 'Garnica', 'Huanca', 'Diana Evelin', '03/20100115DGH', '14265317', '2010-01-15', 'FEMENINO', 1, 6, NULL, NULL, '8098044620168310', 3, 0, 0, 1, '0000-00-00'),
(12, 2, 3, 2, 7, 'Mercado', 'Calle', 'Noemi', '03-20110522NMC', '13256487', '2011-05-22', 'FEMENINO', 1, 4, NULL, NULL, '8098044620175271', 3, 0, 0, 1, '0000-00-00'),
(13, 2, 1, 1, 7, 'Xxxxx', 'Tapia', 'Samuel Alejandro', 'Xxxx', 'Xxxxx', '2008-06-06', 'MASCULINO', 1, 4, NULL, NULL, '809804462014262', 3, 0, 0, 1, '0000-00-00'),
(14, 2, 3, 2, 7, 'Huanca', 'Chipana', 'Juana', '03-20101124JHC', '12812507', '2010-11-24', 'FEMENINO', 1, 4, NULL, NULL, '8098044620165345', 3, 0, 0, 1, '0000-00-00'),
(15, 2, 1, 1, 7, 'Teran', 'Maturano', 'Fabricio Lautaro', '03-20110826FTM', '14812536', '2011-08-28', 'MASCULINO', 1, 4, NULL, NULL, '809801992017026', 3, 0, 0, 1, '0000-00-00'),
(16, 2, 3, 2, 7, 'Alejo', 'Condori', 'Camila', 'Xxxx', '14852636', '2009-07-10', 'FEMENINO', 1, 4, NULL, NULL, '8089044620169844', 3, 0, 0, 1, '0000-00-00'),
(17, 2, 2, 7, 7, 'Arévalo', 'Garcia', 'Eloyda', '03-20100604EAG', '9849916', '2010-07-04', 'FEMENINO', 1, 4, NULL, NULL, '8098044620165510', 3, 0, 0, 1, '0000-00-00'),
(18, 2, 1, 1, 7, 'Calle', 'Villcarani', 'Nico! Wendy', 'Xxxx', '14093700', '2009-08-30', 'FEMENINO', 1, 4, NULL, NULL, '8098044620167484', 3, 0, 0, 1, '0000-00-00'),
(19, 2, 1, 1, 7, 'Choque', 'Martinez', 'Kevin Marcelo', '03-20081007KCM', '9493960', '2008-10-07', 'MASCULINO', 1, 4, NULL, NULL, '80980446201459', 3, 0, 0, 1, '0000-00-00'),
(20, 2, 1, 1, 7, 'Mamani', 'Rojas', 'David Alejandro', '03-20110629DMR', '13418485', '2011-08-29', 'MASCULINO', 1, 4, NULL, NULL, '80980446201488', 3, 0, 0, 1, '0000-00-00'),
(21, 2, 1, 1, 7, 'Saenez', 'Flores', 'Cristhian Ronaldo', '03-20080704CSF', '13528952', '2009-07-04', 'MASCULINO', 1, 4, NULL, NULL, '80980446201442', 3, 0, 0, 1, '0000-00-00'),
(22, 2, 1, 1, 7, 'Vallejos', 'Mamani', 'Sara', '03-20060816SVM', '13623808', '2006-08-16', 'FEMENINO', 1, 4, NULL, NULL, '8098044620167387', 3, 0, 0, 1, '0000-00-00'),
(23, 2, 1, 1, 7, 'Santillan', 'López', 'Hugo Daniel', 'Xxxx', '12442356', '2010-11-14', 'MASCULINO', 1, 4, NULL, NULL, '80980324201596', 3, 0, 0, 1, '0000-00-00'),
(24, 2, 1, 1, 7, 'Corrales', 'Vargas', 'José Miguel', 'Xxxx', 'Xxxxx', '2009-08-15', 'MASCULINO', 1, 4, NULL, NULL, '819802742014194', 3, 0, 0, 1, '0000-00-00'),
(25, 2, 1, 1, 7, 'Xxxxx', 'Miranda', 'Alvaro', 'Xxxx', '14093039', '2007-05-09', 'MASCULINO', 1, 4, NULL, NULL, '8098044620161456', 3, 0, 0, 1, '0000-00-00'),
(26, 2, 1, 1, 7, 'Aguilar', 'Yampa', 'Carlos Gerardo', 'Xxxxx', 'Xxxxx', '2006-12-01', 'MASCULINO', 1, 6, NULL, NULL, '80980446201413', 3, 0, 0, 1, '0000-00-00'),
(27, 2, 1, 1, 7, 'Escalera', 'Choque', 'Martin', 'Xxxxx', 'Xxxx', '2006-07-27', 'MASCULINO', 1, 6, NULL, NULL, '8098044620166997', 3, 0, 0, 1, '0000-00-00'),
(28, 2, 1, 1, 7, 'Pozo', 'Martínez', 'Marcelo Rodolfo', '03-20081209MPM', '9416512', '2008-12-09', 'MASCULINO', 1, 6, NULL, NULL, '809803242013191', 3, 0, 0, 1, '0000-00-00'),
(29, 2, 1, 1, 7, 'Ramirez', 'Perez', 'Mauricio Fernando', '03-20060616MRP', '13001946', '2006-06-16', 'MASCULINO', 1, 4, NULL, NULL, '80980446201465', 3, 0, 0, 1, '0000-00-00'),
(30, 2, 1, 1, 7, 'Terrazas', 'Higueras', 'Yessenia Nicol', '03-20090408YTH', '13378613', '2009-04-08', 'MASCULINO', 1, 4, NULL, NULL, '8098044620165676', 3, 0, 0, 1, '0000-00-00'),
(31, 2, 1, 1, 7, 'Tola', 'Vallejos', 'Juan Daniel', '03-20090025JTV', '13378422', '2009-03-25', 'MASCULINO', 1, 4, NULL, NULL, '809804462016839', 3, 0, 0, 1, '0000-00-00'),
(32, NULL, 1, 1, 7, 'Torrez', 'Reynaga', 'Matías Gerardo', '03-20090924MTR', '13002404', '2009-09-24', 'MASCULINO', 2, 4, NULL, NULL, '8098044620139140', 3, 0, 0, 1, '0000-00-00'),
(33, 2, 1, 1, 7, 'Esquivel', 'Canasi', 'Humberto', 'Xxxx', '15129296', '2009-07-04', 'MASCULINO', 1, 4, NULL, NULL, '714500012016038', 3, 0, 0, 1, '0000-00-00'),
(34, 2, 1, 1, 7, 'Guzmán', 'Aranibar', 'Sophia Belen', 'Xxxxx', 'Xxxx', '2004-07-26', 'MASCULINO', 1, 4, NULL, NULL, '809804742018633', 3, 0, 0, 1, '0000-00-00'),
(35, 2, 1, 1, 7, 'Xxxxx', 'Illanes', 'Jhonatan Cristian', '03-20070425JI', '13226100', '2007-04-25', 'MASCULINO', 1, 4, NULL, NULL, '80980446201436', 3, 0, 0, 1, '0000-00-00'),
(36, 2, 1, 1, 7, 'Mirabal', 'Chocotea', 'Marializ', '03-20090103MMC', '13289308', '2009-01-03', 'MASCULINO', 1, 4, NULL, NULL, '8098044620139098', 3, 0, 0, 1, '0000-00-00'),
(37, 2, 1, 1, 7, 'Reynaga', 'Alvarez', 'Etmar Marcelo', '03-20071130ERA', '13260877', '2007-11-30', 'MASCULINO', 1, 4, NULL, NULL, '8098044620139155', 3, 0, 0, 1, '0000-00-00'),
(38, 2, 1, 1, 7, 'Sossa', 'Cayo', 'José Miguel', 'Xxxx', 'Xxxx', '2003-08-27', 'FEMENINO', 1, 4, NULL, NULL, '80980446201494', 3, 0, 0, 1, '0000-00-00'),
(39, 2, 1, 1, 7, 'Jiménez', 'Espinoza', 'Jeison Guido', '03-20070321JJE', '13418357', '2007-03-21', 'FEMENINO', 1, 4, NULL, NULL, '71860012201279', 3, 0, 0, 1, '0000-00-00'),
(40, 2, 1, 1, 7, 'Barrientos', 'Segales', 'Cristóbal Alejandro', 'Xxxx', '13228266', '2008-09-11', 'FEMENINO', 1, 4, NULL, NULL, '80980446201163', 3, 0, 0, 1, '0000-00-00'),
(41, 2, 1, 1, 7, 'Xxxx', 'Chambi', 'Angelica', '03-20070104LC', '12342908', '2007-01-04', 'FEMENINO', 1, 6, NULL, NULL, '809804462013916A', 3, 0, 0, 1, '0000-00-00'),
(42, 2, 1, 1, 7, 'Anampa', 'Quinteros', 'Leonardo Leonel', 'Xxxx', 'Xxxx', '2006-06-09', 'FEMENINO', 1, 4, NULL, NULL, '80980446201225', 3, 0, 0, 1, '0000-00-00'),
(43, 2, 1, 1, 7, 'Delgadillo', 'Cerrudo', 'Alejandra', '03-20089110ADC', '9422534', '2006-01-13', 'FEMENINO', 1, 4, NULL, NULL, '80980446201157', 3, 0, 0, 1, '0000-00-00'),
(44, 2, 1, 1, 7, 'Mamani', 'Rojas', 'Isac', 'Xxx', '8850390', '2005-03-30', 'FEMENINO', 1, 4, NULL, NULL, '809804462011119', 3, 0, 0, 1, '0000-00-00'),
(45, 2, 1, 1, 7, 'Olivarez', 'Alavi', 'Gabriel Melvin', '03-20051108GOA', '13097801', '2005-11-08', 'FEMENINO', 1, 4, NULL, NULL, '8098044620139174', 3, 0, 0, 1, '0000-00-00'),
(46, 2, 1, 1, 7, 'Ojeda', 'Bernabe', 'Gabriel', '03-20071208GOB', '12714561', '2007-12-06', 'FEMENINO', 1, 4, NULL, NULL, '8098044620139189', 3, 0, 0, 1, '0000-00-00'),
(47, 2, 1, 1, 7, 'Cayuba', 'Roca', 'Matilde', 'xxxx', 'Xxxxx', '2004-03-31', 'FEMENINO', 1, 4, NULL, NULL, '8219013020112193', 3, 0, 0, 1, '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `gestion`
--

CREATE TABLE `gestion` (
  `id` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `gestion`
--

INSERT INTO `gestion` (`id`) VALUES
(1),
(2),
(2000),
(2001),
(2002),
(2003),
(2004),
(2005),
(2006),
(2007),
(2008),
(2009),
(2010),
(2011),
(2012),
(2013),
(2014),
(2015),
(2016),
(2017),
(2018),
(2019);

-- --------------------------------------------------------

--
-- Table structure for table `inscripcionestudiante`
--

CREATE TABLE `inscripcionestudiante` (
  `id_estudiante` int(11) NOT NULL,
  `id_curso` int(11) NOT NULL,
  `id_gestion` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `esincritoespecial` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `inscripcionestudiante`
--

INSERT INTO `inscripcionestudiante` (`id_estudiante`, `id_curso`, `id_gestion`, `fecha`, `esincritoespecial`) VALUES
(1, 1, 2018, '2019-10-06', 1),
(1, 1, 2019, '2019-10-06', 1),
(2, 1, 2018, '2019-10-06', 1),
(3, 1, 2018, '2019-10-06', 1),
(3, 1, 2019, '2019-10-06', 1),
(4, 1, 2018, '2019-10-06', 1),
(4, 1, 2019, '2019-10-06', 1),
(5, 1, 2018, '2019-10-06', 1),
(5, 1, 2019, '2019-10-06', 1),
(6, 1, 2018, '2019-10-06', 1),
(6, 1, 2019, '2019-10-06', 1);

-- --------------------------------------------------------

--
-- Table structure for table `institucionesdesalud`
--

CREATE TABLE `institucionesdesalud` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `departamento` int(11) NOT NULL,
  `provincia` int(11) NOT NULL,
  `municipio` int(11) NOT NULL,
  `direccion` varchar(100) NOT NULL,
  `telefono` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `id_persona` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `institucionesdesalud`
--

INSERT INTO `institucionesdesalud` (`id`, `nombre`, `departamento`, `provincia`, `municipio`, `direccion`, `telefono`, `email`, `id_persona`) VALUES
(4234234, 'hfkhsdkjfhkjsdh', 0, 0, 0, 'dkjahfdjah', '32478234', 'carltiao@gmail.com', 2),
(4234235, 'Centro de salud Norte', 0, 0, 0, 'Hernando Siles', '234234', 'cdsn@hotmail.com', 7),
(4234236, 'Visión Infantil', 0, 0, 0, 'Arquímedes # 234', '97643', 'dsaludP@hotmail.com', 2);

-- --------------------------------------------------------

--
-- Table structure for table `medio`
--

CREATE TABLE `medio` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `medio`
--

INSERT INTO `medio` (`id`, `nombre`) VALUES
(1, 'Television'),
(2, 'Volantes'),
(3, 'Pagina Web'),
(4, 'Radio'),
(10, 'trabajadores de salud y educación');

-- --------------------------------------------------------

--
-- Table structure for table `municipio`
--

CREATE TABLE `municipio` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `id_provincia` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `municipio`
--

INSERT INTO `municipio` (`id`, `nombre`, `descripcion`, `id_provincia`) VALUES
(1, 'Cochabamba', 'GAM de Cochabamba', 0),
(2, 'Quillacollo', 'GAM de Quillacollo', 0),
(3, 'Colcapirhua', 'GAM de Colcapirhua', 0),
(4, 'Sipe Sipe', 'GAM de Sipe Sipe', 0),
(5, 'Vinto', 'GAM de Vinto', 0),
(6, 'Tiquipaya', 'GAM de Tiquipaya', 0),
(7, 'Sacaba', 'GAM de Sacaba', 0),
(8, 'Villa Tunari', 'GAM de Villa Tunari', 0),
(9, 'Colomi', 'GAM de Colomi', 0),
(10, 'San Benito', 'GAM San Benito', 4);

-- --------------------------------------------------------

--
-- Table structure for table `neonatal`
--

CREATE TABLE `neonatal` (
  `id` int(11) NOT NULL,
  `fecha_tamizaje` date NOT NULL,
  `id_centro` int(11) NOT NULL,
  `apellidopaterno` varchar(100) NOT NULL,
  `apellidomaterno` varchar(100) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `ci` varchar(100) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `dias` varchar(100) NOT NULL,
  `semanas` varchar(100) NOT NULL,
  `meses` varchar(100) NOT NULL,
  `sexo` varchar(100) NOT NULL,
  `discapacidad` int(11) NOT NULL,
  `id_tipodiscapacidad` int(11) DEFAULT NULL,
  `resultado` varchar(100) DEFAULT NULL,
  `resultadotamizaje` varchar(100) DEFAULT NULL,
  `tapon` varchar(100) DEFAULT NULL,
  `tipo` varchar(100) DEFAULT NULL,
  `repetirprueba` varchar(100) DEFAULT NULL,
  `observaciones` varchar(100) DEFAULT NULL,
  `id_apoderado` int(11) DEFAULT NULL,
  `id_referencia` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `neonatal`
--

INSERT INTO `neonatal` (`id`, `fecha_tamizaje`, `id_centro`, `apellidopaterno`, `apellidomaterno`, `nombre`, `ci`, `fecha_nacimiento`, `dias`, `semanas`, `meses`, `sexo`, `discapacidad`, `id_tipodiscapacidad`, `resultado`, `resultadotamizaje`, `tapon`, `tipo`, `repetirprueba`, `observaciones`, `id_apoderado`, `id_referencia`) VALUES
(1, '2019-03-31', 4234234, 'fhkshfsh', 'fjsklfjklsj', 'carlos', '479249729', '2019-03-17', '1', '1', '1', 'FEMENINO', 0, 0, '427', '577834', 'eiuwuew', 'riuwey', 'SI', 'fsdfsd', 1, 1),
(2, '2019-04-04', 4234235, 'de', 'de', 'de', '34234', '2019-04-01', '3', '3', '3', 'FEMENINO', 3423, 423, '23423', '423', 'SI', 'IZQUIERDA', 'SI', '3234', NULL, NULL),
(3, '2019-04-09', 4234235, 'sdfsdfsf sd fsdf sdf sdf sdfsdf sd f sdf', 'Esta es una prueba para ver el tamano de los datos del ingreso', 'Esta es una prueba para ver el tamano de los datos del ingreso', '234234234', '2019-04-10', '1', '2', '3', 'FEMENINO', 1, 1, 'Esta es una prueba para ver el tamano de los datos del ingreso', 'Esta es una prueba para ver el tamano de los datos del ingreso', 'SI', 'IZQUIERDA', 'SI', 'Esta es una prueba para ver el tamano de los datos del ingreso', 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `otrasorganizaciones`
--

CREATE TABLE `otrasorganizaciones` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `departamento` varchar(100) NOT NULL,
  `ciudad` varchar(100) NOT NULL,
  `direccion` varchar(100) NOT NULL,
  `telefono` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `id_persona` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `otros`
--

CREATE TABLE `otros` (
  `id` int(11) NOT NULL,
  `id_actividad` int(11) NOT NULL,
  `apellidopaterno` varchar(100) NOT NULL,
  `apellidomaterno` varchar(100) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `nrodiscapacidad` varchar(100) DEFAULT NULL,
  `ci` varchar(100) DEFAULT NULL,
  `fecha_nacimiento` date NOT NULL,
  `sexo` varchar(100) NOT NULL,
  `nivelestudio` varchar(100) DEFAULT NULL,
  `id_discapacidad` int(11) DEFAULT NULL,
  `id_tipodiscapacidad` int(11) DEFAULT NULL,
  `resultado` varchar(100) DEFAULT NULL,
  `resultadotamizaje` varchar(100) DEFAULT NULL,
  `tapon` int(11) DEFAULT NULL,
  `tipo` varchar(100) DEFAULT NULL,
  `repetirprueba` varchar(100) DEFAULT NULL,
  `observaciones` varchar(100) DEFAULT NULL,
  `id_apoderado` int(11) DEFAULT NULL,
  `id_referencia` int(11) DEFAULT NULL,
  `id_centro` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `otros`
--

INSERT INTO `otros` (`id`, `id_actividad`, `apellidopaterno`, `apellidomaterno`, `nombre`, `nrodiscapacidad`, `ci`, `fecha_nacimiento`, `sexo`, `nivelestudio`, `id_discapacidad`, `id_tipodiscapacidad`, `resultado`, `resultadotamizaje`, `tapon`, `tipo`, `repetirprueba`, `observaciones`, `id_apoderado`, `id_referencia`, `id_centro`) VALUES
(1, 1, 'dddd', 'ddd', 'dddd', '2423424', '423423', '2019-04-01', 'FEMENINO', '4234', 1, NULL, 'SI', 'rwerw', 1, 'IZQUIERDA', 'werw', 'rwer', NULL, NULL, 0),
(2, 2, 'Esta es una prueba para ver el tamano de los datos del ingreso', 'Esta es una prueba para ver el tamano de los datos del ingreso', 'Esta es una prueba para ver el tamano de los datos del ingreso', 'erwerwer', 'sdfdsgdfgdf', '2019-04-10', 'FEMENINO', 'dsfgdfsgsdfg', 1, NULL, 'SI', 'sdfgdfsgdfsg', 1, 'IZQUIERDA', 'dgdfgsfgfdg', 'sdgdsfgdfsg', 2, 2, 0),
(3, 3, 'MOREIRA', 'MONTAÑO', 'ALBINA', NULL, '526796', '1950-01-02', 'FEMENINO', NULL, 2, NULL, 'NO', NULL, 2, NULL, 'AUDIOMETRIA', NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `participante`
--

CREATE TABLE `participante` (
  `id` int(11) NOT NULL,
  `id_sector` int(11) NOT NULL,
  `id_actividad` int(11) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `apellidopaterno` varchar(100) NOT NULL,
  `apellidomaterno` varchar(100) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `sexo` varchar(100) NOT NULL,
  `ci` varchar(100) DEFAULT NULL,
  `nrodiscapacidad` varchar(100) DEFAULT NULL,
  `celular` varchar(100) DEFAULT NULL,
  `direcciondomicilio` varchar(100) DEFAULT NULL,
  `ocupacion` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `cargo` varchar(100) DEFAULT NULL,
  `nivelestudio` varchar(100) DEFAULT NULL,
  `id_institucion` int(11) DEFAULT NULL,
  `observaciones` varchar(100) DEFAULT NULL,
  `id_centro` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `participante`
--

INSERT INTO `participante` (`id`, `id_sector`, `id_actividad`, `id_categoria`, `apellidopaterno`, `apellidomaterno`, `nombre`, `fecha_nacimiento`, `sexo`, `ci`, `nrodiscapacidad`, `celular`, `direcciondomicilio`, `ocupacion`, `email`, `cargo`, `nivelestudio`, `id_institucion`, `observaciones`, `id_centro`) VALUES
(1, 5, 1, 3, 'Mamani', 'Pérez', 'zasdad', '2019-04-23', 'MASCULINO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(2, 5, 1, 4, 'Prueba App y tamano del texto', 'Prueba App y tamano del texto', 'Prueba App y tamano del texto', '2019-04-10', 'MASCULINO', '23423423', '2342342', '23423', 'Prueba App y tamano del texto', 'Prueba App y tamano del texto', 'sdfasd@sdfs.com', 'sdfsdfs', 'sdfsd', NULL, 'Prueba App y tamano del texto', 0),
(3, 4, 5, 4, 'dddddd', 'ddddd', 'dddd', '2019-01-30', 'MASCULINO', NULL, '34455566', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(4, 4, 5, 4, 'Pepito', 'pepito', 'Pepito', '2014-02-04', 'MASCULINO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(5, 4, 7, 5, 'asdasdas', 'asdasd', 'asdasda', '2019-04-24', 'MASCULINO', '13123123', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(6, 4, 3, 7, 'sss', 'sss', 'sss', '2019-04-18', 'MASCULINO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `persona`
--

CREATE TABLE `persona` (
  `id` int(11) NOT NULL,
  `nombreinstitucion` varchar(100) NOT NULL,
  `apellidopaterno` varchar(100) NOT NULL,
  `apellidomaterno` varchar(100) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `telefono` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `tipopersona` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `persona`
--

INSERT INTO `persona` (`id`, `nombreinstitucion`, `apellidopaterno`, `apellidomaterno`, `nombre`, `telefono`, `email`, `tipopersona`) VALUES
(1, '', 'XX', 'XXX', 'XXX', '39393993', '', ''),
(2, '', 'RRR', 'RRR', 'RRR', '39399292', '', ''),
(3, '', 'Fernandez', 'Antelo', 'Maria Silvia', '4353534', 'm.antelo@hotmail.com', ''),
(4, '', 'Medichi', 'Orellana', 'Jorge', '46532', 'j.medichi@gmail.com', ''),
(5, '', 'Jverter', 'Urreña', 'Javier', '345345345', 'dscer@gmail.com', ''),
(6, '', 'Monteagudo', 'Arce', 'Janeth', '34534', 'j.arce@gmail.com', ''),
(7, '', 'Sandoval', 'Moron', 'Jorge', '235423', 'jmoron@comteco.com.bo', ''),
(8, '', 'Mercado', 'Argote', 'Claudia', '865432', 'c.argote@hotmail.com', ''),
(9, '', '234 Prueba de institucion con un maximo de varios caracteres.', '234 Prueba de institucion con un maximo de varios caracteres.', '234 Prueba de institucion con un maximo de varios caracteres.', '1231221312', 'asdasd@sad.com', ''),
(10, '', 'Prueba facilitador', 'prueba facilitador', '67Esta es una prueba para ver la cantidad de caracteres que acepta', 'pruab facilitador', 'correo', ''),
(11, '', 'XXX Llenar', 'XXX Llenar', 'XX Llenar', '0000', '0000@com.bo', ''),
(12, '', 'Aguilar', 'Meneces', 'Juan', '70379913', 'juanmen-300562@hotmail.com', ''),
(13, '', 'VACARREZA', 'BOORQUEZ', 'WILMER', 'X', 'X', ''),
(14, '', 'LUCANA', 'SILES', 'KERIN', 'X', 'X', ''),
(15, '', 'CUAQUIRA', 'CRUZ', 'LILIAN', '4259577', 'lily_marcelo@hotmail.com', '');

-- --------------------------------------------------------

--
-- Table structure for table `provincia`
--

CREATE TABLE `provincia` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `id_departamento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `provincia`
--

INSERT INTO `provincia` (`id`, `nombre`, `descripcion`, `id_departamento`) VALUES
(1, 'Cercado', 'Provincia Cercado', 0),
(2, 'Chapare', 'Provincia Chapare', 0),
(3, 'Quillacollo', 'Provincia Quillacollo', 0),
(4, 'Punata', 'Provincia Punata', 231);

-- --------------------------------------------------------

--
-- Table structure for table `pruebasaudiologia`
--

CREATE TABLE `pruebasaudiologia` (
  `id` int(11) NOT NULL,
  `id_audiologia` int(11) NOT NULL,
  `id_tipopruebasaudiologia` int(11) DEFAULT NULL,
  `resultado` varchar(100) DEFAULT NULL,
  `recomendacion` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pruebasaudiologia`
--

INSERT INTO `pruebasaudiologia` (`id`, `id_audiologia`, `id_tipopruebasaudiologia`, `resultado`, `recomendacion`) VALUES
(1, 1, 1, 'sadasdasd', NULL),
(2, 1, 1, 'fsdfsd', 'fsdfsdfs'),
(3, 5, 2, 'gdgdf', 'gdgfgd'),
(4, 11, 1, 'iyriwye', 'rwerywi'),
(5, 11, 1, 'eryugwue', 'rweryu'),
(6, 19, 1, 'cerumen', 'lavado de oído'),
(7, 19, 6, NULL, NULL),
(8, 21, 7, 'Hyyyy', NULL),
(9, 28, 6, 'asda dsa asd adas a', 'asd a asd asd adas asd'),
(10, 44, 11, 'deficiencia', 'estimulación'),
(11, 44, 12, 'xxx', 'xxxx'),
(12, 45, 1, 'ndie esoosadasd', 'cuidasdnalkdnsal'),
(13, 45, 2, 'jhkasdhksaj  asdjksa', 'jhadsjk'),
(14, 45, 7, 'asdasd', 'asdasd'),
(15, 46, 1, 'membranas timpánicas íntegras, conductos auditivos permeables.', 'cuidados del oído y la audición'),
(16, 46, 3, 'hipoacusia sensorioneural de grado moderado', 'adaptación de ayudas auditivas.'),
(17, 47, 3, '111', '2222'),
(18, 49, 7, NULL, NULL),
(19, 49, 1, NULL, NULL),
(20, 52, 3, 'Jjj', 'Jjj'),
(21, 52, 4, NULL, NULL),
(22, 54, 13, NULL, NULL),
(23, 29, 11, NULL, NULL),
(24, 30, 11, NULL, NULL),
(25, 57, 1, 'sss', 'sss'),
(26, 58, 1, 'dssdfdsdad', 'fsdfsdfsd'),
(27, 58, 2, NULL, NULL),
(28, 58, 2, 'dasdasdas', NULL),
(29, 64, 11, NULL, NULL),
(30, 10, 1, NULL, NULL),
(31, 64, 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `referencia`
--

CREATE TABLE `referencia` (
  `id` int(11) NOT NULL,
  `id_medio` int(11) NOT NULL,
  `nombrescompleto` varchar(100) DEFAULT NULL,
  `nombrescentromedico` varchar(100) DEFAULT NULL,
  `dirección` int(11) DEFAULT NULL,
  `telefono` int(11) DEFAULT NULL,
  `id_centro` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `referencia`
--

INSERT INTO `referencia` (`id`, `id_medio`, `nombrescompleto`, `nombrescentromedico`, `dirección`, `telefono`, `id_centro`) VALUES
(1, 1, 'xxx', 'xxx', 53453, 84792, 0),
(2, 2, 'Javier Romero', NULL, NULL, NULL, 0),
(3, 4, 'FM 343.2', 'Centro Radio', 4543, 29825672, 0);

-- --------------------------------------------------------

--
-- Table structure for table `sector`
--

CREATE TABLE `sector` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sector`
--

INSERT INTO `sector` (`id`, `nombre`) VALUES
(3, 'Salud'),
(4, 'Educacion'),
(5, 'Ambos'),
(6, 'GENERO');

-- --------------------------------------------------------

--
-- Table structure for table `tapon`
--

CREATE TABLE `tapon` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tapon`
--

INSERT INTO `tapon` (`id`, `nombre`) VALUES
(1, 'Si'),
(2, 'No');

-- --------------------------------------------------------

--
-- Table structure for table `tipoactividad`
--

CREATE TABLE `tipoactividad` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tipoactividad`
--

INSERT INTO `tipoactividad` (`id`, `nombre`) VALUES
(1, 'Taller'),
(2, 'Capacitacion'),
(3, 'Feria'),
(4, 'ELECCIÓN DE CENTRO DE ESTUDIANTES'),
(5, 'JORNADAS PEDAGOGICAS'),
(6, 'talleres de formación técnica vocacional básica'),
(7, 'Actividades lideradas por la asociación o junta de padres y madres'),
(8, 'Actividades lideradas por los centros de estudiantes'),
(9, 'capacitación sobre los derechos y la inclusión de personas con discapacidad'),
(10, 'eventos de sensibilización y concientización'),
(11, 'temas transversales');

-- --------------------------------------------------------

--
-- Table structure for table `tipocentro`
--

CREATE TABLE `tipocentro` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tipocentro`
--

INSERT INTO `tipocentro` (`id`, `descripcion`) VALUES
(1, '6');

-- --------------------------------------------------------

--
-- Table structure for table `tipodiagnosticoaudiologia`
--

CREATE TABLE `tipodiagnosticoaudiologia` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tipodiagnosticoaudiologia`
--

INSERT INTO `tipodiagnosticoaudiologia` (`id`, `nombre`) VALUES
(1, 'Otoscopia Normal'),
(2, 'Tapon de Cerumen'),
(3, 'Otitis Media'),
(4, 'Otitis Aguda'),
(5, 'Cuerpo extraño'),
(6, 'Malformaciones'),
(7, 'Emisiones Otacústicas Normal'),
(8, 'Emisiones Otacústicas Alterado'),
(9, 'Tinitus'),
(10, 'xxxxxxx');

-- --------------------------------------------------------

--
-- Table structure for table `tipodiscapacidad`
--

CREATE TABLE `tipodiscapacidad` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tipodiscapacidad`
--

INSERT INTO `tipodiscapacidad` (`id`, `nombre`) VALUES
(1, 'Fisica'),
(2, 'Intelectual'),
(3, 'Visual'),
(4, 'Auditiva'),
(5, 'Psicosocial'),
(6, 'Multiple Auditiva intelectual'),
(7, 'NINGUNO');

-- --------------------------------------------------------

--
-- Table structure for table `tipoespecialidad`
--

CREATE TABLE `tipoespecialidad` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tipoespecialidad`
--

INSERT INTO `tipoespecialidad` (`id`, `nombre`) VALUES
(1, 'AUDIOLOGIA'),
(2, 'PSICOLOGIA'),
(5, 'Estímulacion temprana'),
(6, 'OFTALMOLOGÍA'),
(7, 'fisioterapia'),
(8, 'OTORRINOLARINGOLOGÍA ORL'),
(9, 'FISIOTERAPIA'),
(10, 'Cirugia'),
(11, 'Estimulacion LSB'),
(12, 'Xxxxx');

-- --------------------------------------------------------

--
-- Table structure for table `tipoprueba`
--

CREATE TABLE `tipoprueba` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `id_audiologia` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tipopruebasaudiologia`
--

CREATE TABLE `tipopruebasaudiologia` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tipopruebasaudiologia`
--

INSERT INTO `tipopruebasaudiologia` (`id`, `nombre`) VALUES
(1, 'Otoscopia'),
(2, 'Emisiones otacústicas'),
(3, 'Audiometría Tonal Liminar'),
(4, 'Audiometría verbal / logo audiometría'),
(5, 'Potenciales evocados auditivos de tronco cerebral'),
(6, 'Potenciales evocados auditivos de estado estable'),
(7, 'Ocufenometria'),
(8, 'Observaciones de conducta a la estimulación auditiva'),
(9, 'Impedanciometria / temporametrica'),
(10, 'Audiometría campo libre'),
(11, 'motricidad'),
(12, 'xxxxxx'),
(13, 'Pruebita');

-- --------------------------------------------------------

--
-- Table structure for table `tipotratamientoaudiologia`
--

CREATE TABLE `tipotratamientoaudiologia` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tipotratamientoaudiologia`
--

INSERT INTO `tipotratamientoaudiologia` (`id`, `nombre`) VALUES
(1, 'Lavado oural'),
(2, 'Quirurgico'),
(3, 'Medico'),
(4, 'Adaptación de ayuda auditiva'),
(5, 'Enfoque multidisciplinario de estimulación de lenguaje y la comunicación.'),
(6, 'xxxxxxxxxx'),
(7, 'Otorrinolaringologia');

-- --------------------------------------------------------

--
-- Table structure for table `tratamiento`
--

CREATE TABLE `tratamiento` (
  `id` int(11) NOT NULL,
  `id_audiologia` int(11) NOT NULL,
  `id_tipotratamientoaudiologia` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tratamiento`
--

INSERT INTO `tratamiento` (`id`, `id_audiologia`, `id_tipotratamientoaudiologia`) VALUES
(1, 1, 1),
(2, 1, 1),
(3, 5, 2),
(4, 12, 1),
(5, 28, 5),
(6, 28, 4),
(7, 44, 6),
(8, 45, 7),
(9, 47, 4),
(10, 52, 4);

-- --------------------------------------------------------

--
-- Table structure for table `turno`
--

CREATE TABLE `turno` (
  `id` int(11) NOT NULL,
  `turno` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `unidadeducativa`
--

CREATE TABLE `unidadeducativa` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `codigo_sie` varchar(100) NOT NULL,
  `departamento` int(11) NOT NULL,
  `provincia` int(11) NOT NULL,
  `municipio` int(11) NOT NULL,
  `direccion` varchar(100) NOT NULL,
  `telefono` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `id_persona` int(11) DEFAULT NULL,
  `id_centro` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `unidadeducativa`
--

INSERT INTO `unidadeducativa` (`id`, `nombre`, `codigo_sie`, `departamento`, `provincia`, `municipio`, `direccion`, `telefono`, `email`, `id_persona`, `id_centro`) VALUES
(1, 'Fe y alegria', 'sdfeer2322', 2, 1, 1, 'Atahuallpa', '298', 'carli@gmail.com', 5, 0),
(2, 'Fe y Alegria Sud', 'SDE2324234', 2, 1, 1, 'Av. Suecia', '2424234', 'feyalegriasud@gmail.com', 6, 0),
(3, 'Mariano Terrazas', '7556hjji', 2, 1, 1, 'Aurelio Melean', '765544', 'dsaludP@hotmail.com', 4, 0),
(5, 'XXX llenar Nilda', 'llenar', 2, 4, 10, 'San Benito', '000000', '000@com.bo', 11, 0),
(6, 'Josè Quintín Mendoza', '80980175', 2, 1, 1, 'Baptista y La Paz', '70379913', 'juanmen-300562@hotmail.com', 12, 0),
(7, 'AUDIOLOGIA', '80980446', 2, 1, 1, 'GRAL. ACHA 677', '4259577', 'lily_marcelo@hotmail.com', 15, 0);

-- --------------------------------------------------------

--
-- Table structure for table `userlevelpermissions`
--

CREATE TABLE `userlevelpermissions` (
  `userlevelid` int(11) NOT NULL,
  `tablename` varchar(255) NOT NULL,
  `permission` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `userlevelpermissions`
--

INSERT INTO `userlevelpermissions` (`userlevelid`, `tablename`, `permission`) VALUES
(-2, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}actividad', 0),
(-2, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}apoderado', 0),
(-2, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}atencion', 0),
(-2, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}atencionescolaraudiologia', 0),
(-2, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}atencionneonatoaudiologia', 0),
(-2, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}atencionotrosaudiologia', 0),
(-2, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}audiologia', 0),
(-2, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}categoria', 0),
(-2, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}centros', 0),
(-2, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}ciudad', 0),
(-2, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}curso', 0),
(-2, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}departamento', 0),
(-2, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}derivacion', 0),
(-2, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}diagnosticoaudiologia', 0),
(-2, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}discapacidad', 0),
(-2, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}docente', 0),
(-2, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}escolar', 0),
(-2, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}especialista', 0),
(-2, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}estudiante', 0),
(-2, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}gestion', 0),
(-2, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}inscripcionestudiante', 0),
(-2, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}institucionesdesalud', 0),
(-2, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}medio', 0),
(-2, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}municipio', 0),
(-2, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}neonatal', 0),
(-2, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}otrasorganizaciones', 0),
(-2, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}otros', 0),
(-2, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}participante', 0),
(-2, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}persona', 0),
(-2, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}provincia', 0),
(-2, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}pruebasaudiologia', 0),
(-2, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}referencia', 0),
(-2, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}sector', 0),
(-2, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}tapon', 0),
(-2, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}ticket.php', 0),
(-2, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}tipoactividad', 0),
(-2, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}tipocentro', 0),
(-2, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}tipodiagnosticoaudiologia', 0),
(-2, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}tipodiscapacidad', 0),
(-2, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}tipoespecialidad', 0),
(-2, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}tipoprueba', 0),
(-2, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}tipopruebasaudiologia', 0),
(-2, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}tipotratamientoaudiologia', 0),
(-2, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}tratamiento', 0),
(-2, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}unidadeducativa', 0),
(-2, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}userlevelpermissions', 0),
(-2, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}userlevels', 0),
(-2, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}usuario', 0),
(-2, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}viewactividad', 0),
(-2, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}viewdocente', 0),
(-2, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}viewestudiante', 0),
(-2, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}viewestudiantecurso', 0),
(-2, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}viewestudiantesetareo', 0),
(-2, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}viewestudiantesetareocurso', 0),
(-2, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}viewmarcologico', 0),
(-2, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}viewparticipante', 0),
(-2, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}viewunidadeducativa', 0),
(0, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}actividad', 0),
(0, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}apoderado', 0),
(0, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}atencion', 0),
(0, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}atencionescolaraudiologia', 0),
(0, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}atencionneonatoaudiologia', 0),
(0, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}atencionotrosaudiologia', 0),
(0, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}audiologia', 0),
(0, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}categoria', 0),
(0, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}centros', 0),
(0, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}ciudad', 0),
(0, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}curso', 0),
(0, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}departamento', 0),
(0, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}derivacion', 0),
(0, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}diagnosticoaudiologia', 0),
(0, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}discapacidad', 0),
(0, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}docente', 0),
(0, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}escolar', 0),
(0, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}especialista', 0),
(0, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}estudiante', 0),
(0, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}gestion', 0),
(0, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}inscripcionestudiante', 0),
(0, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}institucionesdesalud', 0),
(0, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}medio', 0),
(0, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}municipio', 0),
(0, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}neonatal', 0),
(0, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}otrasorganizaciones', 0),
(0, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}otros', 0),
(0, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}participante', 0),
(0, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}persona', 0),
(0, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}provincia', 0),
(0, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}pruebasaudiologia', 0),
(0, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}referencia', 0),
(0, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}sector', 0),
(0, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}tapon', 0),
(0, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}ticket.php', 0),
(0, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}tipoactividad', 0),
(0, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}tipocentro', 0),
(0, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}tipodiagnosticoaudiologia', 0),
(0, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}tipodiscapacidad', 0),
(0, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}tipoespecialidad', 0),
(0, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}tipoprueba', 0),
(0, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}tipopruebasaudiologia', 0),
(0, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}tipotratamientoaudiologia', 0),
(0, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}tratamiento', 0),
(0, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}unidadeducativa', 0),
(0, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}userlevelpermissions', 0),
(0, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}userlevels', 0),
(0, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}usuario', 0),
(0, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}viewactividad', 0),
(0, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}viewdocente', 0),
(0, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}viewestudiante', 0),
(0, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}viewestudiantecurso', 0),
(0, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}viewestudiantesetareo', 0),
(0, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}viewestudiantesetareocurso', 0),
(0, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}viewmarcologico', 0),
(0, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}viewparticipante', 0),
(0, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}viewunidadeducativa', 0),
(9, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}actividad', 111),
(9, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}apoderado', 111),
(9, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}atencion', 111),
(9, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}atencionescolaraudiologia', 111),
(9, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}atencionneonatoaudiologia', 111),
(9, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}atencionotrosaudiologia', 111),
(9, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}audiologia', 111),
(9, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}categoria', 111),
(9, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}centros', 111),
(9, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}ciudad', 111),
(9, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}curso', 111),
(9, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}departamento', 111),
(9, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}derivacion', 111),
(9, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}diagnosticoaudiologia', 111),
(9, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}discapacidad', 111),
(9, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}docente', 111),
(9, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}escolar', 111),
(9, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}especialista', 111),
(9, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}estudiante', 111),
(9, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}gestion', 111),
(9, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}inscripcionestudiante', 111),
(9, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}institucionesdesalud', 111),
(9, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}medio', 111),
(9, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}municipio', 111),
(9, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}neonatal', 111),
(9, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}otrasorganizaciones', 111),
(9, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}otros', 111),
(9, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}participante', 111),
(9, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}persona', 111),
(9, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}provincia', 111),
(9, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}pruebasaudiologia', 111),
(9, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}referencia', 111),
(9, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}sector', 111),
(9, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}tapon', 111),
(9, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}ticket.php', 111),
(9, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}tipoactividad', 111),
(9, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}tipocentro', 111),
(9, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}tipodiagnosticoaudiologia', 111),
(9, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}tipodiscapacidad', 111),
(9, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}tipoespecialidad', 111),
(9, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}tipoprueba', 111),
(9, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}tipopruebasaudiologia', 111),
(9, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}tipotratamientoaudiologia', 111),
(9, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}tratamiento', 111),
(9, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}turno', 111),
(9, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}unidadeducativa', 111),
(9, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}userlevelpermissions', 111),
(9, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}userlevels', 111),
(9, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}usuario', 111),
(9, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}viewactividad', 111),
(9, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}viewdocente', 111),
(9, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}viewestudiante', 111),
(9, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}viewestudiantecurso', 111),
(9, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}viewestudiantesetareo', 111),
(9, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}viewestudiantesetareocurso', 111),
(9, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}viewmarcologico', 111),
(9, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}viewparticipante', 111),
(9, '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}viewunidadeducativa', 111),
(9, '{707530BA-BEB7-415A-B683-2C9753B31FA3}actividad', 111),
(9, '{707530BA-BEB7-415A-B683-2C9753B31FA3}apoderado', 111),
(9, '{707530BA-BEB7-415A-B683-2C9753B31FA3}atencion', 111),
(9, '{707530BA-BEB7-415A-B683-2C9753B31FA3}atencionescolaraudiologia', 111),
(9, '{707530BA-BEB7-415A-B683-2C9753B31FA3}atencionneonatoaudiologia', 111),
(9, '{707530BA-BEB7-415A-B683-2C9753B31FA3}atencionotrosaudiologia', 111),
(9, '{707530BA-BEB7-415A-B683-2C9753B31FA3}audiologia', 111),
(9, '{707530BA-BEB7-415A-B683-2C9753B31FA3}categoria', 111),
(9, '{707530BA-BEB7-415A-B683-2C9753B31FA3}centros', 111),
(9, '{707530BA-BEB7-415A-B683-2C9753B31FA3}ciudad', 111),
(9, '{707530BA-BEB7-415A-B683-2C9753B31FA3}curso', 111),
(9, '{707530BA-BEB7-415A-B683-2C9753B31FA3}Dashboard1', 111),
(9, '{707530BA-BEB7-415A-B683-2C9753B31FA3}Dashboard2', 111),
(9, '{707530BA-BEB7-415A-B683-2C9753B31FA3}departamento', 111),
(9, '{707530BA-BEB7-415A-B683-2C9753B31FA3}derivacion', 111),
(9, '{707530BA-BEB7-415A-B683-2C9753B31FA3}diagnosticoaudiologia', 111),
(9, '{707530BA-BEB7-415A-B683-2C9753B31FA3}discapacidad', 111),
(9, '{707530BA-BEB7-415A-B683-2C9753B31FA3}docente', 111),
(9, '{707530BA-BEB7-415A-B683-2C9753B31FA3}escolar', 111),
(9, '{707530BA-BEB7-415A-B683-2C9753B31FA3}especialista', 111),
(9, '{707530BA-BEB7-415A-B683-2C9753B31FA3}estudiante', 111),
(9, '{707530BA-BEB7-415A-B683-2C9753B31FA3}gestion', 111),
(9, '{707530BA-BEB7-415A-B683-2C9753B31FA3}inscripcionestudiante', 111),
(9, '{707530BA-BEB7-415A-B683-2C9753B31FA3}institucionesdesalud', 111),
(9, '{707530BA-BEB7-415A-B683-2C9753B31FA3}medio', 111),
(9, '{707530BA-BEB7-415A-B683-2C9753B31FA3}municipio', 111),
(9, '{707530BA-BEB7-415A-B683-2C9753B31FA3}neonatal', 111),
(9, '{707530BA-BEB7-415A-B683-2C9753B31FA3}otrasorganizaciones', 111),
(9, '{707530BA-BEB7-415A-B683-2C9753B31FA3}otros', 111),
(9, '{707530BA-BEB7-415A-B683-2C9753B31FA3}participante', 111),
(9, '{707530BA-BEB7-415A-B683-2C9753B31FA3}persona', 111),
(9, '{707530BA-BEB7-415A-B683-2C9753B31FA3}provincia', 111),
(9, '{707530BA-BEB7-415A-B683-2C9753B31FA3}pruebasaudiologia', 111),
(9, '{707530BA-BEB7-415A-B683-2C9753B31FA3}referencia', 111),
(9, '{707530BA-BEB7-415A-B683-2C9753B31FA3}ReportEstudiantes', 111),
(9, '{707530BA-BEB7-415A-B683-2C9753B31FA3}sector', 111),
(9, '{707530BA-BEB7-415A-B683-2C9753B31FA3}tapon', 111),
(9, '{707530BA-BEB7-415A-B683-2C9753B31FA3}tipoactividad', 111),
(9, '{707530BA-BEB7-415A-B683-2C9753B31FA3}tipocentro', 111),
(9, '{707530BA-BEB7-415A-B683-2C9753B31FA3}tipodiagnosticoaudiologia', 111),
(9, '{707530BA-BEB7-415A-B683-2C9753B31FA3}tipodiscapacidad', 111),
(9, '{707530BA-BEB7-415A-B683-2C9753B31FA3}tipoespecialidad', 111),
(9, '{707530BA-BEB7-415A-B683-2C9753B31FA3}tipoprueba', 111),
(9, '{707530BA-BEB7-415A-B683-2C9753B31FA3}tipopruebasaudiologia', 111),
(9, '{707530BA-BEB7-415A-B683-2C9753B31FA3}tipotratamientoaudiologia', 111),
(9, '{707530BA-BEB7-415A-B683-2C9753B31FA3}tratamiento', 111),
(9, '{707530BA-BEB7-415A-B683-2C9753B31FA3}turno', 111),
(9, '{707530BA-BEB7-415A-B683-2C9753B31FA3}unidadeducativa', 111),
(9, '{707530BA-BEB7-415A-B683-2C9753B31FA3}userlevelpermissions', 111),
(9, '{707530BA-BEB7-415A-B683-2C9753B31FA3}userlevels', 111),
(9, '{707530BA-BEB7-415A-B683-2C9753B31FA3}usuario', 111),
(9, '{707530BA-BEB7-415A-B683-2C9753B31FA3}viewactividad', 111),
(9, '{707530BA-BEB7-415A-B683-2C9753B31FA3}viewdocente', 111),
(9, '{707530BA-BEB7-415A-B683-2C9753B31FA3}viewestudiante', 111),
(9, '{707530BA-BEB7-415A-B683-2C9753B31FA3}viewestudiantecurso', 111),
(9, '{707530BA-BEB7-415A-B683-2C9753B31FA3}viewestudiantesetareo', 111),
(9, '{707530BA-BEB7-415A-B683-2C9753B31FA3}viewestudiantesetareocurso', 111),
(9, '{707530BA-BEB7-415A-B683-2C9753B31FA3}viewmarcologico', 111),
(9, '{707530BA-BEB7-415A-B683-2C9753B31FA3}viewparticipante', 111),
(9, '{707530BA-BEB7-415A-B683-2C9753B31FA3}viewunidadeducativa', 111);

-- --------------------------------------------------------

--
-- Table structure for table `userlevels`
--

CREATE TABLE `userlevels` (
  `userlevelid` int(11) NOT NULL,
  `userlevelname` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `userlevels`
--

INSERT INTO `userlevels` (`userlevelid`, `userlevelname`) VALUES
(-2, 'Anonymous'),
(-1, 'Administrator'),
(0, 'Default'),
(9, 'nodelete');

-- --------------------------------------------------------

--
-- Table structure for table `usuario`
--

CREATE TABLE `usuario` (
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `id_centro` int(11) NOT NULL,
  `login` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `ci` varchar(20) NOT NULL,
  `id_rol` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `usuario`
--

INSERT INTO `usuario` (`nombre`, `apellido`, `id_centro`, `login`, `password`, `ci`, `id_rol`) VALUES
('carlos', 'claros', 2, 'carlos', '123456789', '12345', -1),
('david', 'sanchez', 1, 'david', '123456789', '938378', -1),
('Gloria', 'Bueno', 3, 'gloriabueno', '123456789', '3393108', 9),
('liliam', 'zamorano', 5, 'liliam', 'liliam', '34543', -1),
('Lilian', 'Cuaquira', 1, 'liliancuaquira', '123456789', '1235456', 9),
('Nilda', 'Balderrama', 3, 'nildabalderrama', '123456789', '2321312321', 9),
('prueba', 'prueba', 4, 'prueba', '123456789', '32423', -1),
('Veronica', 'Barriga', 3, 'veronicabarriga', '123456789', '4509437', 9);

-- --------------------------------------------------------

--
-- Stand-in structure for view `viewactividad`
-- (See below for the actual view)
--
CREATE TABLE `viewactividad` (
`sector` varchar(100)
,`tipoactividad` varchar(100)
,`organizador` int(11)
,`nombreactividad` varchar(100)
,`nombrelocal` varchar(100)
,`direccionlocal` varchar(100)
,`fecha_inicio` date
,`fecha_fin` date
,`horasprogramadas` varchar(100)
,`perosnanombre` varchar(100)
,`personaapellidomaterno` varchar(100)
,`personaapellidopaterno` varchar(100)
,`contenido` varchar(100)
,`observaciones` varchar(100)
,`nombreinstitucion` varchar(100)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `viewdocente`
-- (See below for the actual view)
--
CREATE TABLE `viewdocente` (
`deoartamento` varchar(100)
,`unidadeducativa` varchar(100)
,`nombres` varchar(100)
,`apellidopaterno` varchar(100)
,`apellidomaterno` varchar(100)
,`nrodiscapacidad` varchar(15)
,`ci` varchar(15)
,`fechanacimiento` date
,`sexo` int(11)
,`celular` varchar(100)
,`materias` varchar(100)
,`nombreinstitucion` varchar(100)
,`discapacidad` varchar(100)
,`tipodiscapacidad` varchar(100)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `viewestudiante`
-- (See below for the actual view)
--
CREATE TABLE `viewestudiante` (
`departamento` varchar(100)
,`codigorude` varchar(100)
,`codigorude_es` varchar(100)
,`municipio` varchar(100)
,`provincia` varchar(100)
,`unidadeducativa` varchar(100)
,`nombre` varchar(100)
,`materno` varchar(100)
,`paterno` varchar(100)
,`nrodiscapacidad` varchar(15)
,`ci` varchar(15)
,`fechanacimiento` date
,`edad` bigint(21)
,`sexo` varchar(100)
,`discapacidad` varchar(100)
,`tipodiscapcidad` varchar(100)
,`nombreinstitucion` varchar(100)
,`observaciones` varchar(100)
,`id_estudiante` int(11)
,`curso` varchar(50)
,`fecha` date
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `viewestudiantecurso`
-- (See below for the actual view)
--
CREATE TABLE `viewestudiantecurso` (
`departamento` varchar(100)
,`codigorude` varchar(100)
,`codigorude_es` varchar(100)
,`municipio` varchar(100)
,`provincia` varchar(100)
,`unidadeducativa` varchar(100)
,`nombre` varchar(100)
,`materno` varchar(100)
,`paterno` varchar(100)
,`nrodiscapacidad` varchar(15)
,`ci` varchar(15)
,`fechanacimiento` date
,`edad` bigint(21)
,`sexo` varchar(100)
,`curso` int(11)
,`discapacidad` varchar(100)
,`tipodiscapcidad` varchar(100)
,`nombreinstitucion` varchar(100)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `viewestudiantesetareo`
-- (See below for the actual view)
--
CREATE TABLE `viewestudiantesetareo` (
`unidadeducativa` varchar(100)
,`0-3F` decimal(23,0)
,`4-6F` decimal(23,0)
,`7-9F` decimal(23,0)
,`10-12F` decimal(23,0)
,`13-15F` decimal(23,0)
,`16-18F` decimal(23,0)
,`19F` decimal(23,0)
,`0-3M` decimal(23,0)
,`4-6M` decimal(23,0)
,`7-9M` decimal(23,0)
,`10-12M` bigint(21)
,`13-15M` decimal(23,0)
,`16-18M` decimal(23,0)
,`19M` decimal(23,0)
,`fecha` date
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `viewestudiantesetareocurso`
-- (See below for the actual view)
--
CREATE TABLE `viewestudiantesetareocurso` (
`edad` bigint(21)
,`etareo` varchar(5)
,`nombreinstitucion` varchar(100)
,`curso` int(11)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `viewmarcologico`
-- (See below for the actual view)
--
CREATE TABLE `viewmarcologico` (
`nombreinstitucion` varchar(100)
,`fecha` date
,`cuadro1` bigint(21)
,`cuadro2` bigint(20)
,`cuadro3` bigint(20)
,`cuadro4` bigint(20)
,`cuadro5` bigint(20)
,`cuadro6` bigint(20)
,`cuadro7` bigint(20)
,`cuadro8` bigint(20)
,`cuadro9` bigint(20)
,`cuadro10` bigint(20)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `viewparticipante`
-- (See below for the actual view)
--
CREATE TABLE `viewparticipante` (
`sector` varchar(100)
,`actividad` varchar(100)
,`categoria` varchar(100)
,`nombreinstitucion` varchar(100)
,`nombre` varchar(100)
,`apellidopaterno` varchar(100)
,`apellidomaterno` varchar(100)
,`fecha_nacimiento` date
,`sexo` varchar(100)
,`ci` varchar(100)
,`nrodiscapacidad` varchar(100)
,`celular` varchar(100)
,`direcciondomicilio` varchar(100)
,`ocupacion` varchar(100)
,`email` varchar(100)
,`cargo` varchar(100)
,`nivelestudio` varchar(100)
,`observaciones` varchar(100)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `viewunidadeducativa`
-- (See below for the actual view)
--
CREATE TABLE `viewunidadeducativa` (
`centro` varchar(100)
,`unidadeducativa` varchar(100)
,`codigo_sie` varchar(100)
,`departamento` varchar(100)
,`municipio` varchar(100)
,`principio` varchar(100)
,`direccion` varchar(100)
,`telefono` varchar(100)
,`email` varchar(100)
);

-- --------------------------------------------------------

--
-- Structure for view `atencionescolaraudiologia`
--
DROP TABLE IF EXISTS `atencionescolaraudiologia`;

CREATE ALGORITHM=UNDEFINED DEFINER=`communitysoft`@`localhost` SQL SECURITY DEFINER VIEW `atencionescolaraudiologia`  AS  select `atencion`.`id` AS `id`,`atencion`.`id_escolar` AS `id_escolar`,`atencion`.`id_especialista` AS `id_especialista`,`atencion`.`id_referencia` AS `id_referencia` from `atencion` ;

-- --------------------------------------------------------

--
-- Structure for view `atencionneonatoaudiologia`
--
DROP TABLE IF EXISTS `atencionneonatoaudiologia`;

CREATE ALGORITHM=UNDEFINED DEFINER=`communitysoft`@`localhost` SQL SECURITY DEFINER VIEW `atencionneonatoaudiologia`  AS  select `atencion`.`id` AS `id1`,`atencion`.`id_neonato` AS `id_neonato`,`atencion`.`id_especialista` AS `id_especialista`,`atencion`.`id_referencia` AS `id_referencia` from `atencion` ;

-- --------------------------------------------------------

--
-- Structure for view `atencionotrosaudiologia`
--
DROP TABLE IF EXISTS `atencionotrosaudiologia`;

CREATE ALGORITHM=UNDEFINED DEFINER=`communitysoft`@`localhost` SQL SECURITY DEFINER VIEW `atencionotrosaudiologia`  AS  select `atencion`.`id` AS `id`,`atencion`.`id_otros` AS `id_otros`,`atencion`.`id_especialista` AS `id_especialista`,`atencion`.`id_referencia` AS `id_referencia` from `atencion` ;

-- --------------------------------------------------------

--
-- Structure for view `viewactividad`
--
DROP TABLE IF EXISTS `viewactividad`;

CREATE ALGORITHM=UNDEFINED DEFINER=`communitysoft`@`localhost` SQL SECURITY DEFINER VIEW `viewactividad`  AS  select `sector`.`nombre` AS `sector`,`tipoactividad`.`nombre` AS `tipoactividad`,`actividad`.`organizador` AS `organizador`,`actividad`.`nombreactividad` AS `nombreactividad`,`actividad`.`nombrelocal` AS `nombrelocal`,`actividad`.`direccionlocal` AS `direccionlocal`,`actividad`.`fecha_inicio` AS `fecha_inicio`,`actividad`.`fecha_fin` AS `fecha_fin`,`actividad`.`horasprogramadas` AS `horasprogramadas`,`persona`.`nombre` AS `perosnanombre`,`persona`.`apellidomaterno` AS `personaapellidomaterno`,`persona`.`apellidopaterno` AS `personaapellidopaterno`,`actividad`.`contenido` AS `contenido`,`actividad`.`observaciones` AS `observaciones`,`centros`.`nombreinstitucion` AS `nombreinstitucion` from ((((`actividad` left join `sector` on((`sector`.`id` = `actividad`.`id_sector`))) left join `tipoactividad` on((`tipoactividad`.`id` = `actividad`.`id_tipoactividad`))) left join `persona` on((`persona`.`id` = `actividad`.`id_persona`))) left join `centros` on((`centros`.`id` = `actividad`.`id_centro`))) ;

-- --------------------------------------------------------

--
-- Structure for view `viewdocente`
--
DROP TABLE IF EXISTS `viewdocente`;

CREATE ALGORITHM=UNDEFINED DEFINER=`communitysoft`@`localhost` SQL SECURITY DEFINER VIEW `viewdocente`  AS  select `departamento`.`nombre` AS `deoartamento`,`unidadeducativa`.`nombre` AS `unidadeducativa`,`docente`.`nombres` AS `nombres`,`docente`.`apellidopaterno` AS `apellidopaterno`,`docente`.`apellidomaterno` AS `apellidomaterno`,`docente`.`nrodiscapacidad` AS `nrodiscapacidad`,`docente`.`ci` AS `ci`,`docente`.`fechanacimiento` AS `fechanacimiento`,`docente`.`sexo` AS `sexo`,`docente`.`celular` AS `celular`,`docente`.`materias` AS `materias`,`centros`.`nombreinstitucion` AS `nombreinstitucion`,`discapacidad`.`nombre` AS `discapacidad`,`tipodiscapacidad`.`nombre` AS `tipodiscapacidad` from (((((`docente` left join `departamento` on((`departamento`.`id` = `docente`.`id_departamento`))) left join `unidadeducativa` on((`unidadeducativa`.`id` = `docente`.`unidadeducativa`))) left join `centros` on((`centros`.`id` = `docente`.`id_centro`))) join `discapacidad` on((`discapacidad`.`id` = `docente`.`discapacidad`))) join `tipodiscapacidad` on((`tipodiscapacidad`.`id` = `docente`.`tipodiscapacidad`))) ;

-- --------------------------------------------------------

--
-- Structure for view `viewestudiante`
--
DROP TABLE IF EXISTS `viewestudiante`;

CREATE ALGORITHM=UNDEFINED DEFINER=`communitysoft`@`localhost` SQL SECURITY DEFINER VIEW `viewestudiante`  AS  select `departamento`.`nombre` AS `departamento`,`estudiante`.`codigorude` AS `codigorude`,`estudiante`.`codigorude_es` AS `codigorude_es`,`municipio`.`descripcion` AS `municipio`,`provincia`.`nombre` AS `provincia`,`unidadeducativa`.`nombre` AS `unidadeducativa`,`estudiante`.`nombres` AS `nombre`,`estudiante`.`apellidomaterno` AS `materno`,`estudiante`.`apellidopaterno` AS `paterno`,`estudiante`.`nrodiscapacidad` AS `nrodiscapacidad`,`estudiante`.`ci` AS `ci`,`estudiante`.`fechanacimiento` AS `fechanacimiento`,timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) AS `edad`,`estudiante`.`sexo` AS `sexo`,`discapacidad`.`nombre` AS `discapacidad`,`tipodiscapacidad`.`nombre` AS `tipodiscapcidad`,`centros`.`nombreinstitucion` AS `nombreinstitucion`,`estudiante`.`observaciones` AS `observaciones`,`inscripcionestudiante`.`id_estudiante` AS `id_estudiante`,`curso`.`curso` AS `curso`,`inscripcionestudiante`.`fecha` AS `fecha` from (((((((((`estudiante` join `departamento` on((`departamento`.`id` = `estudiante`.`departamento`))) join `municipio` on((`municipio`.`id` = `estudiante`.`municipio`))) join `provincia` on((`provincia`.`id` = `estudiante`.`provincisa`))) join `unidadeducativa` on((`unidadeducativa`.`id` = `estudiante`.`unidadeducativa`))) join `discapacidad` on((`discapacidad`.`id` = `estudiante`.`discapacidad`))) join `tipodiscapacidad` on((`tipodiscapacidad`.`id` = `estudiante`.`tipodiscapacidad`))) join `centros` on((`centros`.`id` = `estudiante`.`id_centro`))) join `inscripcionestudiante` on((`inscripcionestudiante`.`id_estudiante` = `estudiante`.`id`))) join `curso` on((`inscripcionestudiante`.`id_curso` = `curso`.`id`))) ;

-- --------------------------------------------------------

--
-- Structure for view `viewestudiantecurso`
--
DROP TABLE IF EXISTS `viewestudiantecurso`;

CREATE ALGORITHM=UNDEFINED DEFINER=`communitysoft`@`localhost` SQL SECURITY DEFINER VIEW `viewestudiantecurso`  AS  select `departamento`.`nombre` AS `departamento`,`estudiante`.`codigorude` AS `codigorude`,`estudiante`.`codigorude_es` AS `codigorude_es`,`municipio`.`descripcion` AS `municipio`,`provincia`.`nombre` AS `provincia`,`unidadeducativa`.`nombre` AS `unidadeducativa`,`estudiante`.`nombres` AS `nombre`,`estudiante`.`apellidomaterno` AS `materno`,`estudiante`.`apellidopaterno` AS `paterno`,`estudiante`.`nrodiscapacidad` AS `nrodiscapacidad`,`estudiante`.`ci` AS `ci`,`estudiante`.`fechanacimiento` AS `fechanacimiento`,timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) AS `edad`,`estudiante`.`sexo` AS `sexo`,`estudiante`.`curso` AS `curso`,`discapacidad`.`nombre` AS `discapacidad`,`tipodiscapacidad`.`nombre` AS `tipodiscapcidad`,`centros`.`nombreinstitucion` AS `nombreinstitucion` from (((((((`estudiante` left join `departamento` on((`departamento`.`id` = `estudiante`.`departamento`))) left join `municipio` on((`municipio`.`id` = `estudiante`.`municipio`))) left join `provincia` on((`provincia`.`id` = `estudiante`.`provincisa`))) left join `unidadeducativa` on((`unidadeducativa`.`id` = `estudiante`.`unidadeducativa`))) left join `discapacidad` on((`discapacidad`.`id` = `estudiante`.`discapacidad`))) left join `tipodiscapacidad` on((`tipodiscapacidad`.`id` = `estudiante`.`tipodiscapacidad`))) left join `centros` on((`centros`.`id` = `estudiante`.`id_centro`))) ;

-- --------------------------------------------------------

--
-- Structure for view `viewestudiantesetareo`
--
DROP TABLE IF EXISTS `viewestudiantesetareo`;

CREATE ALGORITHM=UNDEFINED DEFINER=`communitysoft`@`localhost` SQL SECURITY DEFINER VIEW `viewestudiantesetareo`  AS  select `unidadeducativa`.`nombre` AS `unidadeducativa`,sum((case when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 0) and (`estudiante`.`sexo` = 'FEMENINO')) then 1 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 1) and (`estudiante`.`sexo` = 'FEMENINO')) then 1 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 2) and (`estudiante`.`sexo` = 'FEMENINO')) then 1 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 3) and (`estudiante`.`sexo` = 'FEMENINO')) then 1 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 4) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 5) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 6) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 7) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 8) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 9) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 10) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 11) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 12) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 13) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 14) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 15) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 16) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 17) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 18) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) >= 19) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 else 0 end)) AS `0-3F`,sum((case when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 0) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 1) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 2) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 3) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 4) and (`estudiante`.`sexo` = 'FEMENINO')) then 1 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 5) and (`estudiante`.`sexo` = 'FEMENINO')) then 1 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 6) and (`estudiante`.`sexo` = 'FEMENINO')) then 1 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 7) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 8) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 9) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 10) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 11) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 12) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 13) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 14) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 15) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 16) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 17) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 18) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) >= 19) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 else 0 end)) AS `4-6F`,sum((case when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 0) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 1) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 2) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 3) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 4) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 5) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 6) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 7) and (`estudiante`.`sexo` = 'FEMENINO')) then 1 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 8) and (`estudiante`.`sexo` = 'FEMENINO')) then 1 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 9) and (`estudiante`.`sexo` = 'FEMENINO')) then 1 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 10) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 11) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 12) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 13) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 14) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 15) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 16) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 17) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 18) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) >= 19) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 else 0 end)) AS `7-9F`,sum((case when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 0) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 1) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 2) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 3) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 4) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 5) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 6) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 7) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 8) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 9) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 10) and (`estudiante`.`sexo` = 'FEMENINO')) then 1 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 11) and (`estudiante`.`sexo` = 'FEMENINO')) then 1 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 12) and (`estudiante`.`sexo` = 'FEMENINO')) then 1 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 13) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 14) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 15) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 16) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 17) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 18) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) >= 19) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 else 0 end)) AS `10-12F`,sum((case when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 0) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 1) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 2) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 3) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 4) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 5) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 6) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 7) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 8) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 9) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 10) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 11) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 12) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 13) and (`estudiante`.`sexo` = 'FEMENINO')) then 1 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 14) and (`estudiante`.`sexo` = 'FEMENINO')) then 1 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 15) and (`estudiante`.`sexo` = 'FEMENINO')) then 1 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 16) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 17) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 18) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) >= 19) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 else 0 end)) AS `13-15F`,sum((case when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 0) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 1) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 2) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 3) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 4) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 5) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 6) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 7) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 8) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 9) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 10) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 11) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 12) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 13) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 14) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 15) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 16) and (`estudiante`.`sexo` = 'FEMENINO')) then 1 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 17) and (`estudiante`.`sexo` = 'FEMENINO')) then 1 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 18) and (`estudiante`.`sexo` = 'FEMENINO')) then 1 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) >= 19) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 else 0 end)) AS `16-18F`,sum((case when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 0) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 1) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 2) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 3) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 4) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 5) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 6) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 7) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 8) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 9) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 10) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 11) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 12) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 13) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 14) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 15) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 16) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 17) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 18) and (`estudiante`.`sexo` = 'FEMENINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) >= 19) and (`estudiante`.`sexo` = 'FEMENINO')) then 1 else 1 end)) AS `19F`,sum((case when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 0) and (`estudiante`.`sexo` = 'MASCULINO')) then 1 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 1) and (`estudiante`.`sexo` = 'MASCULINO')) then 1 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 2) and (`estudiante`.`sexo` = 'MASCULINO')) then 1 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 3) and (`estudiante`.`sexo` = 'MASCULINO')) then 1 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 4) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 5) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 6) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 7) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 8) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 9) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 10) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 11) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 12) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 13) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 14) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 15) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 16) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 17) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 18) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) >= 19) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 else 0 end)) AS `0-3M`,sum((case when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 0) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 1) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 2) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 3) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 4) and (`estudiante`.`sexo` = 'MASCULINO')) then 1 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 5) and (`estudiante`.`sexo` = 'MASCULINO')) then 1 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 6) and (`estudiante`.`sexo` = 'MASCULINO')) then 1 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 7) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 8) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 9) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 10) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 11) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 12) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 13) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 14) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 15) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 16) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 17) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 18) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) >= 19) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 else 0 end)) AS `4-6M`,sum((case when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 0) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 1) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 2) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 3) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 4) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 5) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 6) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 7) and (`estudiante`.`sexo` = 'MASCULINO')) then 1 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 8) and (`estudiante`.`sexo` = 'MASCULINO')) then 1 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 9) and (`estudiante`.`sexo` = 'MASCULINO')) then 1 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 10) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 11) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 12) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 13) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 14) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 15) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 16) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 17) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 18) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) >= 19) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 else 0 end)) AS `7-9M`,count((case when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 0) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 1) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 2) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 3) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 4) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 5) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 6) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 7) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 8) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 9) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 10) and (`estudiante`.`sexo` = 'MASCULINO')) then 1 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 11) and (`estudiante`.`sexo` = 'MASCULINO')) then 1 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 12) and (`estudiante`.`sexo` = 'MASCULINO')) then 1 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 13) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 14) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 15) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 16) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 17) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 18) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) >= 19) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 else 0 end)) AS `10-12M`,sum((case when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 0) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 1) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 2) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 3) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 4) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 5) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 6) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 7) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 8) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 9) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 10) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 11) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 12) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 13) and (`estudiante`.`sexo` = 'MASCULINO')) then 1 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 14) and (`estudiante`.`sexo` = 'MASCULINO')) then 1 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 15) and (`estudiante`.`sexo` = 'MASCULINO')) then 1 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 16) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 17) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 18) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) >= 19) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 else 0 end)) AS `13-15M`,sum((case when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 0) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 1) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 2) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 3) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 4) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 5) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 6) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 7) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 8) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 9) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 10) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 11) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 12) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 13) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 14) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 15) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 16) and (`estudiante`.`sexo` = 'MASCULINO')) then 1 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 17) and (`estudiante`.`sexo` = 'MASCULINO')) then 1 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 18) and (`estudiante`.`sexo` = 'MASCULINO')) then 1 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) >= 19) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 else 0 end)) AS `16-18M`,sum((case when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 0) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 1) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 2) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 3) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 4) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 5) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 6) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 7) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 8) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 9) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 10) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 11) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 12) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 13) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 14) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 15) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 16) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 17) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 18) and (`estudiante`.`sexo` = 'MASCULINO')) then 0 when ((timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) >= 19) and (`estudiante`.`sexo` = 'MASCULINO')) then 1 else 1 end)) AS `19M`,`inscripcionestudiante`.`fecha` AS `fecha` from ((`estudiante` left join `unidadeducativa` on((`unidadeducativa`.`id` = `estudiante`.`unidadeducativa`))) join `inscripcionestudiante` on((`inscripcionestudiante`.`id_estudiante` = `estudiante`.`id`))) group by `unidadeducativa`.`nombre`,`inscripcionestudiante`.`fecha` ;

-- --------------------------------------------------------

--
-- Structure for view `viewestudiantesetareocurso`
--
DROP TABLE IF EXISTS `viewestudiantesetareocurso`;

CREATE ALGORITHM=UNDEFINED DEFINER=`communitysoft`@`localhost` SQL SECURITY DEFINER VIEW `viewestudiantesetareocurso`  AS  select timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) AS `edad`,(case when (timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 0) then '0-3' when (timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 1) then '0-3' when (timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 2) then '0-3' when (timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 3) then '0-3' when (timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 4) then '4-6' when (timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 5) then '4-6' when (timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 6) then '4-6' when (timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 7) then '7-9' when (timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 8) then '7-9' when (timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 9) then '7-9' when (timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 10) then '9-12' when (timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 11) then '9-12' when (timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 12) then '9-12' when (timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 13) then '13-15' when (timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 14) then '13-15' when (timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 15) then '13-15' when (timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 16) then '16-18' when (timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 17) then '16-18' when (timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) = 18) then '16-18' when (timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) >= 19) then '19-X' else '19-X' end) AS `etareo`,`centros`.`nombreinstitucion` AS `nombreinstitucion`,`estudiante`.`curso` AS `curso` from (((((((`estudiante` left join `departamento` on((`departamento`.`id` = `estudiante`.`departamento`))) left join `municipio` on((`municipio`.`id` = `estudiante`.`municipio`))) left join `provincia` on((`provincia`.`id` = `estudiante`.`provincisa`))) left join `unidadeducativa` on((`unidadeducativa`.`id` = `estudiante`.`unidadeducativa`))) left join `discapacidad` on((`discapacidad`.`id` = `estudiante`.`discapacidad`))) left join `tipodiscapacidad` on((`tipodiscapacidad`.`id` = `estudiante`.`tipodiscapacidad`))) left join `centros` on((`centros`.`id` = `estudiante`.`id_centro`))) group by timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()),`centros`.`nombreinstitucion`,`estudiante`.`curso` ;

-- --------------------------------------------------------

--
-- Structure for view `viewmarcologico`
--
DROP TABLE IF EXISTS `viewmarcologico`;

CREATE ALGORITHM=UNDEFINED DEFINER=`communitysoft`@`localhost` SQL SECURITY DEFINER VIEW `viewmarcologico`  AS  select `centros`.`nombreinstitucion` AS `nombreinstitucion`,`inscripcionestudiante`.`fecha` AS `fecha`,count(`estudiante`.`id`) AS `cuadro1`,0 AS `cuadro2`,0 AS `cuadro3`,0 AS `cuadro4`,0 AS `cuadro5`,0 AS `cuadro6`,0 AS `cuadro7`,0 AS `cuadro8`,0 AS `cuadro9`,0 AS `cuadro10` from (((`estudiante` join `centros` on((`centros`.`id` = `estudiante`.`id_centro`))) join `inscripcionestudiante` on((`inscripcionestudiante`.`id_estudiante` = `estudiante`.`id`))) join `curso` on((`inscripcionestudiante`.`id_curso` = `curso`.`id`))) where (timestampdiff(YEAR,`estudiante`.`fechanacimiento`,curdate()) in (0,1,2,3,4)) group by `centros`.`nombreinstitucion`,`inscripcionestudiante`.`fecha` union all select `centros`.`nombreinstitucion` AS `nombreinstitucion`,`inscripcionestudiante`.`fecha` AS `fecha`,0 AS `cuadro1`,count(`estudiante`.`id`) AS `cuadro2`,0 AS `cuadro3`,0 AS `cuadro4`,0 AS `cuadro5`,0 AS `cuadro6`,0 AS `cuadro7`,0 AS `cuadro8`,0 AS `cuadro9`,0 AS `cuadro10` from (((`estudiante` join `centros` on((`centros`.`id` = `estudiante`.`id_centro`))) join `inscripcionestudiante` on((`inscripcionestudiante`.`id_estudiante` = `estudiante`.`id`))) join `curso` on((`inscripcionestudiante`.`id_curso` = `curso`.`id`))) where (`estudiante`.`discapacidad` = 1) group by `centros`.`nombreinstitucion`,`inscripcionestudiante`.`fecha` union all select `centros`.`nombreinstitucion` AS `nombreinstitucion`,`actividad`.`fecha_fin` AS `fecha`,0 AS `cuadro1`,0 AS `cuadro2`,count(`participante`.`id`) AS `cuadro3`,0 AS `cuadro4`,0 AS `cuadro5`,0 AS `cuadro6`,0 AS `cuadro7`,0 AS `cuadro8`,0 AS `cuadro9`,0 AS `cuadro10` from ((`actividad` join `participante` on((`actividad`.`id` = `participante`.`id_actividad`))) join `centros` on((`centros`.`id` = `participante`.`id_centro`))) group by `centros`.`nombreinstitucion`,`actividad`.`fecha_fin` ;

-- --------------------------------------------------------

--
-- Structure for view `viewparticipante`
--
DROP TABLE IF EXISTS `viewparticipante`;

CREATE ALGORITHM=UNDEFINED DEFINER=`communitysoft`@`localhost` SQL SECURITY DEFINER VIEW `viewparticipante`  AS  select `sector`.`nombre` AS `sector`,`actividad`.`nombreactividad` AS `actividad`,`categoria`.`nombre` AS `categoria`,`centros`.`nombreinstitucion` AS `nombreinstitucion`,`participante`.`nombre` AS `nombre`,`participante`.`apellidopaterno` AS `apellidopaterno`,`participante`.`apellidomaterno` AS `apellidomaterno`,`participante`.`fecha_nacimiento` AS `fecha_nacimiento`,`participante`.`sexo` AS `sexo`,`participante`.`ci` AS `ci`,`participante`.`nrodiscapacidad` AS `nrodiscapacidad`,`participante`.`celular` AS `celular`,`participante`.`direcciondomicilio` AS `direcciondomicilio`,`participante`.`ocupacion` AS `ocupacion`,`participante`.`email` AS `email`,`participante`.`cargo` AS `cargo`,`participante`.`nivelestudio` AS `nivelestudio`,`participante`.`observaciones` AS `observaciones` from ((((`participante` left join `sector` on((`sector`.`id` = `participante`.`id_sector`))) left join `actividad` on((`actividad`.`id` = `participante`.`id_actividad`))) left join `categoria` on((`categoria`.`id` = `participante`.`id_categoria`))) left join `centros` on((`centros`.`id` = `participante`.`id_centro`))) ;

-- --------------------------------------------------------

--
-- Structure for view `viewunidadeducativa`
--
DROP TABLE IF EXISTS `viewunidadeducativa`;

CREATE ALGORITHM=UNDEFINED DEFINER=`communitysoft`@`localhost` SQL SECURITY DEFINER VIEW `viewunidadeducativa`  AS  select `centros`.`nombreinstitucion` AS `centro`,`unidadeducativa`.`nombre` AS `unidadeducativa`,`unidadeducativa`.`codigo_sie` AS `codigo_sie`,`departamento`.`nombre` AS `departamento`,`municipio`.`nombre` AS `municipio`,`provincia`.`nombre` AS `principio`,`unidadeducativa`.`direccion` AS `direccion`,`unidadeducativa`.`telefono` AS `telefono`,`unidadeducativa`.`email` AS `email` from ((((`unidadeducativa` left join `centros` on((`centros`.`id` = `unidadeducativa`.`id_centro`))) left join `departamento` on((`departamento`.`id` = `unidadeducativa`.`departamento`))) left join `provincia` on((`provincia`.`id` = `unidadeducativa`.`provincia`))) left join `municipio` on((`municipio`.`id` = `unidadeducativa`.`municipio`))) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `actividad`
--
ALTER TABLE `actividad`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `apoderado`
--
ALTER TABLE `apoderado`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `atencion`
--
ALTER TABLE `atencion`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `audiologia`
--
ALTER TABLE `audiologia`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `centros`
--
ALTER TABLE `centros`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ciudad`
--
ALTER TABLE `ciudad`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `curso`
--
ALTER TABLE `curso`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departamento`
--
ALTER TABLE `departamento`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `derivacion`
--
ALTER TABLE `derivacion`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `diagnosticoaudiologia`
--
ALTER TABLE `diagnosticoaudiologia`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `discapacidad`
--
ALTER TABLE `discapacidad`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `docente`
--
ALTER TABLE `docente`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `escolar`
--
ALTER TABLE `escolar`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `especialista`
--
ALTER TABLE `especialista`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `estudiante`
--
ALTER TABLE `estudiante`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gestion`
--
ALTER TABLE `gestion`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inscripcionestudiante`
--
ALTER TABLE `inscripcionestudiante`
  ADD PRIMARY KEY (`id_estudiante`,`id_curso`,`id_gestion`);

--
-- Indexes for table `institucionesdesalud`
--
ALTER TABLE `institucionesdesalud`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `medio`
--
ALTER TABLE `medio`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `municipio`
--
ALTER TABLE `municipio`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `neonatal`
--
ALTER TABLE `neonatal`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `otrasorganizaciones`
--
ALTER TABLE `otrasorganizaciones`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `otros`
--
ALTER TABLE `otros`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `participante`
--
ALTER TABLE `participante`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `persona`
--
ALTER TABLE `persona`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `provincia`
--
ALTER TABLE `provincia`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pruebasaudiologia`
--
ALTER TABLE `pruebasaudiologia`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `referencia`
--
ALTER TABLE `referencia`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sector`
--
ALTER TABLE `sector`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tapon`
--
ALTER TABLE `tapon`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tipoactividad`
--
ALTER TABLE `tipoactividad`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tipocentro`
--
ALTER TABLE `tipocentro`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tipodiagnosticoaudiologia`
--
ALTER TABLE `tipodiagnosticoaudiologia`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tipodiscapacidad`
--
ALTER TABLE `tipodiscapacidad`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tipoespecialidad`
--
ALTER TABLE `tipoespecialidad`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tipoprueba`
--
ALTER TABLE `tipoprueba`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tipopruebasaudiologia`
--
ALTER TABLE `tipopruebasaudiologia`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tipotratamientoaudiologia`
--
ALTER TABLE `tipotratamientoaudiologia`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tratamiento`
--
ALTER TABLE `tratamiento`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `unidadeducativa`
--
ALTER TABLE `unidadeducativa`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `userlevelpermissions`
--
ALTER TABLE `userlevelpermissions`
  ADD PRIMARY KEY (`userlevelid`,`tablename`);

--
-- Indexes for table `userlevels`
--
ALTER TABLE `userlevels`
  ADD PRIMARY KEY (`userlevelid`);

--
-- Indexes for table `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`login`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `actividad`
--
ALTER TABLE `actividad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `apoderado`
--
ALTER TABLE `apoderado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `atencion`
--
ALTER TABLE `atencion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;
--
-- AUTO_INCREMENT for table `audiologia`
--
ALTER TABLE `audiologia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;
--
-- AUTO_INCREMENT for table `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `centros`
--
ALTER TABLE `centros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `ciudad`
--
ALTER TABLE `ciudad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `curso`
--
ALTER TABLE `curso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `departamento`
--
ALTER TABLE `departamento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `derivacion`
--
ALTER TABLE `derivacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `diagnosticoaudiologia`
--
ALTER TABLE `diagnosticoaudiologia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `discapacidad`
--
ALTER TABLE `discapacidad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `docente`
--
ALTER TABLE `docente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT for table `escolar`
--
ALTER TABLE `escolar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `especialista`
--
ALTER TABLE `especialista`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11123;
--
-- AUTO_INCREMENT for table `estudiante`
--
ALTER TABLE `estudiante`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;
--
-- AUTO_INCREMENT for table `gestion`
--
ALTER TABLE `gestion`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2020;
--
-- AUTO_INCREMENT for table `institucionesdesalud`
--
ALTER TABLE `institucionesdesalud`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4234237;
--
-- AUTO_INCREMENT for table `municipio`
--
ALTER TABLE `municipio`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `neonatal`
--
ALTER TABLE `neonatal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `otros`
--
ALTER TABLE `otros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `participante`
--
ALTER TABLE `participante`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `persona`
--
ALTER TABLE `persona`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `provincia`
--
ALTER TABLE `provincia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `pruebasaudiologia`
--
ALTER TABLE `pruebasaudiologia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
--
-- AUTO_INCREMENT for table `referencia`
--
ALTER TABLE `referencia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `sector`
--
ALTER TABLE `sector`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `tapon`
--
ALTER TABLE `tapon`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `tipoactividad`
--
ALTER TABLE `tipoactividad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `tipocentro`
--
ALTER TABLE `tipocentro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tipodiagnosticoaudiologia`
--
ALTER TABLE `tipodiagnosticoaudiologia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `tipodiscapacidad`
--
ALTER TABLE `tipodiscapacidad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `tipoespecialidad`
--
ALTER TABLE `tipoespecialidad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `tipoprueba`
--
ALTER TABLE `tipoprueba`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tipopruebasaudiologia`
--
ALTER TABLE `tipopruebasaudiologia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `tipotratamientoaudiologia`
--
ALTER TABLE `tipotratamientoaudiologia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `tratamiento`
--
ALTER TABLE `tratamiento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `unidadeducativa`
--
ALTER TABLE `unidadeducativa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
