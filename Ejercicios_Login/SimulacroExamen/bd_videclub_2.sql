-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 18-12-2023 a las 15:20:18
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
-- Base de datos: `bd_videclub_simulacro_examen`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `peliculas`
--

CREATE TABLE `peliculas` (
  `id_pelicula` int(11) NOT NULL,
  `titulo` varchar(15) NOT NULL,
  `director` varchar(20) NOT NULL,
  `sinopsis` text NOT NULL,
  `tematica` varchar(15) NOT NULL,
  `caratula` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `peliculas`
--

INSERT INTO `peliculas` (`id_pelicula`, `titulo`, `director`, `sinopsis`, `tematica`, `caratula`) VALUES
(1, 'Pelicula1', 'Director1', 'Sinopsis1', 'Tematica1', 'caratula1.jpg'),
(2, 'Pelicula2', 'Director2', 'Sinopsis2', 'Tematica2', 'caratula2.jpg'),
(3, 'Pelicula3', 'Director3', 'Sinopsis3', 'Tematica3', 'caratula3.jpg'),
(4, 'Pelicula4', 'Director4', 'Sinopsis4', 'Tematica4', 'caratula4.jpg'),
(5, 'Pelicula5', 'Director5', 'Sinopsis5', 'Tematica5', 'caratula5.jpg'),
(6, 'Pelicula6', 'Director6', 'Sinopsis6', 'Tematica6', 'caratula6.jpg'),
(7, 'Pelicula7', 'Director7', 'Sinopsis7', 'Tematica7', 'caratula7.jpg'),
(8, 'Pelicula8', 'Director8', 'Sinopsis8', 'Tematica8', 'caratula8.jpg'),
(9, 'Pelicula9', 'Director9', 'Sinopsis9', 'Tematica9', 'caratula9.jpg'),
(10, 'Pelicula10', 'Director10', 'Sinopsis10', 'Tematica10', 'caratula10.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `DNI` varchar(15) NOT NULL,
  `usuario` varchar(15) NOT NULL,
  `clave` varchar(50) NOT NULL,
  `telefono` int(15) NOT NULL,
  `email` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`DNI`, `usuario`, `clave`, `telefono`, `email`) VALUES
('12345098', 'usuario10', 'e823d38e2018737a77b4b9bf3e94c697', 876543210, 'usuario10@examp'),
('12345678', 'usuario1', 'a4a97ffc170ec7ab32b85b2129c69c50', 123456789, 'usuario1@exampl'),
('23456789', 'usuario2', '10dea63031376352d413a8e530654b8b', 987654321, 'usuario2@exampl'),
('32037474S', 'Maria', '31c7d084f0460fcde98ee9314fc8ef30', 99889092, 'joseangel@gmail'),
('34567890', 'usuario3', '35559e8b5732fbd5029bef54aeab7a21', 567890123, 'usuario3@exampl'),
('45678901', 'usuario4', 'c707dce7b5a990e349c873268cf5a968', 321098765, 'usuario4@exampl'),
('56789012', 'usuario5', '9d4ba1ec63d70f19106c2aec14926374', 789012345, 'usuario5@exampl'),
('67890123', 'usuario6', 'e5c4bd895be104cc1a928687c7fc922a', 234567890, 'usuario6@exampl'),
('76432167Q', 'joselito', '9e74458084358e639d43012b122af253', 99889092, 'joseangel@gmail'),
('78901234', 'usuario7', '8b33d112c1a64f9fb374eb87b98990cf', 890123456, 'usuario7@exampl'),
('89012345', 'usuario8', '7d905fbdc246912149bf8bdb2c43efd8', 456789012, 'usuario8@exampl'),
('90123456', 'usuario9', '68bfccf877bb29c1698663b8f6920a20', 12345678, 'usuario9@exampl');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `peliculas`
--
ALTER TABLE `peliculas`
  ADD PRIMARY KEY (`id_pelicula`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`DNI`(9));

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `peliculas`
--
ALTER TABLE `peliculas`
  MODIFY `id_pelicula` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
