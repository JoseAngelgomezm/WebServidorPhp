-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 21-11-2023 a las 10:16:40
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
-- Base de datos: `bd_videoclub`
--
CREATE database if not exists bd_videoclub  DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
  use bd_videoclub; 
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `películas`
--

CREATE TABLE `peliculas` (
  `idPelicula` int(11) NOT NULL,
  `titulo` varchar(15) NOT NULL,
  `director` varchar(20) NOT NULL,
  `sinopsis` text NOT NULL,
  `tematica` varchar(15) NOT NULL,
  `caratula` varchar(30) NOT NULL DEFAULT 'no_imagen.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `películas`
--

INSERT INTO `películas` (`idPelicula`, `titulo`, `director`, `sinopsis`, `tematica`, `caratula`) VALUES
(9, 'Gran Torino', 'Clint Eastwood', 'Walt Kowalski, un veterano de la guerra de Corea, es un obrero jubilado del sector del automóvil que ha enviudado recientemente. Su máxima pasión es cuidar de su más preciado tesoro: un coche Gran Torino de 1972. Es un hombre inflexible y cascarrabias, al que le cuesta trabajo asimilar los cambios que se producen a su alrededor, especialmente la llegada de multitud de inmigrantes asiáticos a su barrio. Sin embargo, las circustancias harán que se vea obligado a replantearse sus ideas.', 'Drama', 'no_imagen.jpg'),
(10, 'Crypto', 'John Stalberg', 'Martin es uno de los agentes del FBI más prometedores de todo Wall Street. Sin embargo, tras una disputa con sus jefes, el joven debe trasladarse a su ciudad natal y trabajar desde allí. Al poco tiempo, Martin comprende que su relocalización ha sido de todo menos una coincidencia. (FILMAFFINITY)', 'Thriller', 'no_imagen.jpg'),
(11, 'La llegada', 'Alejandro Rojas', 'Los ganadores del Goya Alberto Ammann (\"Celda 211\") y Bruna Cusí (\"Verano 1993\") protagonizan este asfixiante thriller que recrea con precisión los estrictos controles de inmigración de los aeropuertos estadounidenses. Una película que sin duda hará que te lo pienses dos veces antes de viajar a la tierra de las oportunidades.  \r\n\r\nDiego, urbanista venezolano, y Elena, bailarina de Barcelona, se mudan a Estados Unidos con sus visados aprobados para empezar una nueva vida. Su intención es impulsar sus carreras profesionales y formar una familia en \"la tierra de las oportunidades\". Pero al entrar en la zona de inmigración del aeropuerto de Nueva York son conducidos a la sala de inspección secundaria, donde serán sometidos a un desagradable proceso de inspección por los agentes de aduanas y a un interrogatorio psicológicamente extenuante, en un intento de descubrir si la pareja puede tener algo que ocultar.', 'Thriller', 'no_imagen.jpg'),
(12, 'Hunger Games', 'Gary Ross', 'Para demostrar su poder, el régimen del estado totalitario de Panem organiza cada año \"Los juegos del hambre\". En ellos, 24 jóvenes compiten el uno contra el otro en una batalla en la que solo puede haber un superviviente. La joven Katniss se ofrece voluntaria para participar en los juegos para salvar a su hermana. Junto a ella participará Peeta, un joven al que ha conocido desde la infancia y que está enamorado de ella. Sin embargo, el Capitolio quiere convertirlos en contrincantes.', 'Accion', 'no_imagen.jpg'),
(13, 'Hostiles', 'Scott Cooper', 'En 1892, un reputado capitán del ejército, Joseph J. Blocker (Christian Bale), se ve en la obligación de escoltar contra su voluntad a un moribundo jefe cheyenne (Wes Studi) y a su familia, de regreso a las tierras de su tribu en Montana. Para ello tendrán que emprender un peligroso viaje por las praderas de Nuevo México, donde se encontrarán con una joven viuda (Rosamund Pike) cuya familia fue asesinada por un grupo de comanches que aún rondan por la zona. Juntos tendrán que unir fuerzas para sobrevivir al castigador paisaje y a las hostiles tribus comanche que se encuentran por el camino. (FILMAFFINITY)', 'Drama', 'no_imagen.jpg'),
(14, 'Emancipation', 'Antoine Fuqua', 'Un Film de Antoine Fuqua. Inspirada en la conmovedora historia real de un hombre dispuesto a cualquier cosa por su familia, y por la Libertad. Cuando Peter, un hombre esclavizado, arriesga su vida por escapar y regresar con su familia, se embarca en una peligrosa travesía de amor y resiliencia. ', 'Accion', 'no_imagen.jpg'),
(15, ' Gold ', 'Anthony Hayes', 'Cuando dos hombres que viajan por el desierto descubren el pedrusco de oro más grande que han visto en su vida, empiezan a soñar con la riqueza que les traerá, pero la avaricia toma las riendas. Tienen que extraer el oro de la tierra y para ello trazan un plan en el que uno de ellos tiene que ir a por el equipo necesario y el otro se tiene que quedar allí solo a su suerte. (FILMAFFINITY)', 'Thriller', 'no_imagen.jpg'),
(16, 'Percy', 'Clark Johnson', 'Un granjero canadiense se enfrenta una gran corporación después de que director interfiera con sus cultivos.', 'Drama', 'no_imagen.jpg');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `películas`
--
ALTER TABLE `películas`
  ADD PRIMARY KEY (`idPelicula`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `películas`
--
ALTER TABLE `películas`
  MODIFY `idPelicula` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
