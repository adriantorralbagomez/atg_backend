-- Adminer 4.8.1 MySQL 5.5.5-10.3.32-MariaDB-0ubuntu0.20.04.1 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP DATABASE IF EXISTS `uvademesa`;
CREATE DATABASE `uvademesa` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish2_ci */;
USE `uvademesa`;

DROP TABLE IF EXISTS `caja`;
CREATE TABLE `caja` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orden_id` int(11) NOT NULL,
  `sector_id` int(11) DEFAULT NULL,
  `tipocaja_id` int(11) NOT NULL,
  `etiqueta_id` int(11) NOT NULL,
  `estado` enum('P','R','A','L','E') COLLATE utf8_spanish2_ci NOT NULL DEFAULT 'P',
  PRIMARY KEY (`id`),
  KEY `sector_id` (`sector_id`),
  KEY `tipocaja_id` (`tipocaja_id`),
  KEY `etiqueta_id` (`etiqueta_id`),
  KEY `orden_id` (`orden_id`),
  CONSTRAINT `caja_ibfk_4` FOREIGN KEY (`sector_id`) REFERENCES `sector` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `caja_ibfk_5` FOREIGN KEY (`tipocaja_id`) REFERENCES `tipocaja` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `caja_ibfk_7` FOREIGN KEY (`etiqueta_id`) REFERENCES `etiqueta` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `caja_ibfk_9` FOREIGN KEY (`orden_id`) REFERENCES `orden` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;


DROP TABLE IF EXISTS `cliente`;
CREATE TABLE `cliente` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(20) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(60) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `cliente` (`id`, `nombre`, `telefono`, `direccion`) VALUES
(1,	'Mercadona',	'24024024',	'calle random'),
(2,	'Día',	'35636363',	'de noche'),
(3,	'carrefour',	'32525252',	'carre4'),
(4,	'lidl',	'2435252',	'andandad');

DROP TABLE IF EXISTS `etiqueta`;
CREATE TABLE `etiqueta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cliente_id` int(11) NOT NULL,
  `formato` text COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cliente_id` (`cliente_id`),
  CONSTRAINT `etiqueta_ibfk_3` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `etiqueta` (`id`, `cliente_id`, `formato`) VALUES
(1,	1,	'(Etiqueta Mercadona)'),
(2,	2,	'(Etiqueta Día)');

DROP TABLE IF EXISTS `finca`;
CREATE TABLE `finca` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `ubicacion` varchar(60) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(20) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `finca` (`id`, `nombre`, `ubicacion`, `telefono`) VALUES
(1,	'Finca1',	'Ciudad 1',	'2525252'),
(2,	'Finca2',	'Ciudad2',	'2252525'),
(3,	'Finca3',	'Ciudad3',	'252524');

DROP TABLE IF EXISTS `orden`;
CREATE TABLE `orden` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lote` varchar(20) COLLATE utf8_spanish2_ci NOT NULL,
  `variedad_id` int(11) NOT NULL,
  `finca_id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `cantidad` int(11) NOT NULL,
  `estado` enum('P','R','A','L','E') COLLATE utf8_spanish2_ci NOT NULL DEFAULT 'P',
  PRIMARY KEY (`id`),
  KEY `variedad_id` (`variedad_id`),
  KEY `finca_id` (`finca_id`),
  CONSTRAINT `orden_ibfk_3` FOREIGN KEY (`variedad_id`) REFERENCES `variedad` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `orden_ibfk_4` FOREIGN KEY (`finca_id`) REFERENCES `finca` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;


DROP TABLE IF EXISTS `orden_pedidoinfo`;
CREATE TABLE `orden_pedidoinfo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orden_id` int(11) NOT NULL,
  `pedidoinfo_id` int(11) NOT NULL,
  `variedad_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `orden_id` (`orden_id`),
  KEY `variedad_id` (`variedad_id`),
  KEY `pedidoinfo_id` (`pedidoinfo_id`),
  CONSTRAINT `orden_pedidoinfo_ibfk_4` FOREIGN KEY (`orden_id`) REFERENCES `orden` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `orden_pedidoinfo_ibfk_5` FOREIGN KEY (`variedad_id`) REFERENCES `variedad` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `orden_pedidoinfo_ibfk_6` FOREIGN KEY (`pedidoinfo_id`) REFERENCES `pedidoinfo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;


DROP TABLE IF EXISTS `parcela`;
CREATE TABLE `parcela` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `variedad_id` int(11) NOT NULL,
  `finca_id` int(11) NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `cant_total` int(11) NOT NULL,
  `cant_disp` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `variedad_id` (`variedad_id`),
  KEY `finca_id` (`finca_id`),
  CONSTRAINT `parcela_ibfk_3` FOREIGN KEY (`variedad_id`) REFERENCES `variedad` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `parcela_ibfk_4` FOREIGN KEY (`finca_id`) REFERENCES `finca` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `parcela` (`id`, `variedad_id`, `finca_id`, `nombre`, `cant_total`, `cant_disp`) VALUES
(1,	1,	1,	'Parcela1',	500000,	500000),
(2,	2,	1,	'Parcela2',	600000,	300000),
(3,	3,	2,	'Parcela1',	400000,	400000),
(4,	2,	3,	'Parcela1',	600000,	450000);

DROP TABLE IF EXISTS `pedido`;
CREATE TABLE `pedido` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cliente_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `estado` enum('P','R','C') COLLATE utf8_spanish2_ci NOT NULL DEFAULT 'P',
  `fecha` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cliente_id` (`cliente_id`),
  CONSTRAINT `pedido_ibfk_2` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;


DROP TABLE IF EXISTS `pedidoinfo`;
CREATE TABLE `pedidoinfo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pedido_id` int(11) NOT NULL,
  `variedad_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pedido_id` (`pedido_id`),
  KEY `variedad_id` (`variedad_id`),
  CONSTRAINT `pedidoinfo_ibfk_4` FOREIGN KEY (`pedido_id`) REFERENCES `pedido` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pedidoinfo_ibfk_5` FOREIGN KEY (`variedad_id`) REFERENCES `variedad` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;


DROP TABLE IF EXISTS `sector`;
CREATE TABLE `sector` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `espacio_total` int(11) NOT NULL,
  `espacio_disp` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `sector` (`id`, `nombre`, `espacio_total`, `espacio_disp`) VALUES
(1,	'Sector 1',	50000,	50000),
(2,	'Sector 2',	40000,	40000),
(3,	'Sector 3',	300000,	300000);

DROP TABLE IF EXISTS `tipocaja`;
CREATE TABLE `tipocaja` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` char(20) COLLATE utf8_spanish2_ci NOT NULL,
  `pesokg` decimal(5,2) NOT NULL,
  `detalles` text COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `tipocaja` (`id`, `nombre`, `pesokg`, `detalles`) VALUES
(1,	'Caja campo',	10.00,	'Caja de campo que contiene las cajas de la uva.\r\nEstas cajas se agrupan en palets y se transportan al campo.'),
(2,	'Caja expedición',	15.00,	'Caja de expedición: Caja que contiene las cajas de medio kilo. \r\nLas cajas de 15 kg se introducen en palets y estos se transportan al camión en la salida de expedición.'),
(3,	'Caja final',	0.50,	'Caja final');

DROP TABLE IF EXISTS `usuario`;
CREATE TABLE `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `finca_id` int(11) DEFAULT NULL,
  `usuario` char(16) COLLATE utf8_spanish2_ci NOT NULL,
  `password` char(32) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `mail_corp` varchar(60) COLLATE utf8_spanish2_ci NOT NULL,
  `mail_per` varchar(60) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` int(12) NOT NULL,
  `rol` enum('A','CC','CP','PP','CE') COLLATE utf8_spanish2_ci NOT NULL,
  `token` char(1) COLLATE utf8_spanish2_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `finca_id` (`finca_id`),
  CONSTRAINT `usuario_ibfk_2` FOREIGN KEY (`finca_id`) REFERENCES `finca` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `usuario` (`id`, `finca_id`, `usuario`, `password`, `nombre`, `mail_corp`, `mail_per`, `telefono`, `rol`, `token`) VALUES
(1,	NULL,	'admin',	'21232f297a57a5a743894a0e4a801fc3',	'admin',	'admin@aldmaldm',	'admin@aldmaldm',	1313214312,	'A',	NULL),
(2,	NULL,	'miriam',	'277f8d77fceb01b44add2092ff8bbc33',	'miriam',	'miriam',	'miriam',	2342424,	'A',	NULL),
(3,	NULL,	'adrian',	'8c4205ec33d8f6caeaaaa0c10a14138c',	'adrian',	'adrian',	'adrian',	2342424,	'A',	NULL),
(4,	1,	'usucampo1',	'0e0685ad30d81b42e62052b7a8d0dcb0',	'usucampo1',	'usucampo1@correo.org',	'usucampo1@correo.org',	12314134,	'CC',	NULL);

DROP TABLE IF EXISTS `variedad`;
CREATE TABLE `variedad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(20) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `variedad` (`id`, `nombre`) VALUES
(1,	'Blanca'),
(2,	'Roja'),
(3,	'Negra');

-- 2022-01-31 08:21:34
