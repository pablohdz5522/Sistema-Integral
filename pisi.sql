-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         9.1.0 - MySQL Community Server - GPL
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.10.0.7000
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para pisi
CREATE DATABASE IF NOT EXISTS `pisi` /*!40100 DEFAULT CHARACTER SET utf8mb3 */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `pisi`;

-- Volcando estructura para tabla pisi.administradores
CREATE TABLE IF NOT EXISTS `administradores` (
  `usuario` int NOT NULL,
  `contraseña` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nombre_admi` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `apellidos_admi` varchar(80) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `rol` varchar(360) COLLATE utf8mb4_general_ci NOT NULL,
  `foto` longblob NOT NULL,
  `intentos_fallidos` int NOT NULL,
  `ultimo_intento` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla pisi.administradores: ~1 rows (aproximadamente)
INSERT INTO `administradores` (`usuario`, `contraseña`, `nombre_admi`, `apellidos_admi`, `rol`, `foto`, `intentos_fallidos`, `ultimo_intento`) VALUES
	(190039, '$2y$10$143X2Cxv7bPJI4sd7.tR0OsJd/l3QxVDatpnC/Ior1bjcrzaL6/Ay', 'Brando del jesus', 'Euan Paredes', 'Administrador', _binary 0x524946466e0b000057454250565038580a00000018000000450000450000414c50480803000001a084ed9f2149fac5ffdf9a59db7bb66ddbf65e6ddbb66ddbb66ddbb69111f1fb1dba332a3bfa36a78898004c48eeee015612ccdccd42413098bb8f566b08e66eee287433778361e4301a8eb0d34e5bddf6d2f1680f53cfbdfc1a6b2c3bdb14689ffcca17afd860ff4d60a1b3005ca9114fc4542bedb9c3229b5ef6caef1a9e7f7ce6ccd597da6b87a506c2dd1a711f20746498ff7ea598628abaf943b53367aafda947f47f8aa951bc6c06846ee06f29929498a418634e3171e49c62ca31262989a272d235b04e0cb3fecb2c4a145316498ad488a4383c25911295f4113a75c779caa48693a2baa4485192486a57f47b2f0660caa4ac8a93de05805064e8dbe6f44bff16590fa96fce39654d201404ccf09224515553926ee843d17dfa2f268a35518cb1d171f0360c7da124aa7652510fc2da0c87298aac8f491b9400784f4963b0d10d30143a3610c9da98f5d39c65e8c3c58af545ed807e94064cfd95b2aa4b7a0ce58e7315553f93b6851718e691c8eaa8acaf264168731ca546f5898cda10de66b847891a038a3aa2ec89b121469d5a76bd22591fd568df12c7b6fa5f646d24931687b5054cfc99fecb226ba298ffd33d0828342cf2ab24aa6a4a7a63da321866bdf89324d644fdf7f6d1e36128376053e5cafe9e1330f46a785c4955479d893ef4ee1f2a89ac85d4ffba15d69be37065aa1e656903786f004e13a96aa9bc1d02ba3d5e89b5306b3338ba0c98f523e59a1e1840a78e6dd5a85e528bc3bad95c91ac8554335f3786b91a51d5647d3a848efb3f52662d8aba0da11bc34d8aaa958d0e405f377dd8470dabc95a05de8d610565b20e527fcc80d04dc0b47f28e5d83429536417a49863d3c4dce825741e709b5a73cc14c91292628a54eb21f0ee66bcefb777ae3dfdbc07bf979463cc24459222996394a4cf6e3febcc5b3ff9e5fc7e741f80490d00265fedec0f353c354d8c3136316bf8ab472d310400031361544300cc3d00185cea88877f5029bfbc73cf790120b807c046a53db803c0e4f36fbcc7b1675f74e19947eeb4eeec1301801b6a370fe8d1dc303683799fbb99f7f5b9054cb00e565038207e070000b025009d012a460046003e61268e462421a1212e18ba48800c096a00d0c9f23bdff01f8c1cdafc6310675ef32739edb5bfacdea03f56bf68fda23d507a007f44febbd601e801e57ffb81f059fb49fb5df00ffb0fffdb200381df075e58f5fbd3531cfd175ff7fa2fcaef4b7bdff7df928fef9f301f30bd72ed0a98ef502f5f7e5dfe63f2cffb1738ff557fc07a91fe75fee3d4cff11e00de0dfdd3f203e803f967f53ff95f701f49ffc57fcdfef7e6bfe77ff89fe0be013f91ff4fff5dfdeff24fe70bd79fecdfb19feb6b11d66e4e5340e8d0837d7a761d4900de2050fd7d744659a98c7bbc1d014decef9169f9a27975fd7d1f8ebc7601882495d9099209da80155f6e291b0adfeb4695b6788cf998c3a4eec591af2badb2bc7dbf0b21b1daa3bfd9b24ffe01dbd3d7f1c8f32178944a8abbe6440000fefda6dcfff855d1805c9bd9000a29aefb00c002cf1c5254cca8d9009d90910f3b885db45f7a2061181731635509b5f2807c717ed17862ab77d3f216a8bafddd065c44f8ad163a912a55ea5e2dc596def78a229e82091631686ab0d70719b9ce3120f10edf2b41ce98d68aa89de718330d3729601c7483caeba490aa59c6dc043969645f54ef7cfa78c0144a86088994cfda1d3e0e32ffeddb68e85d1cffc84024876edc4518fe1b6de6d42168aa43a175ed5585d5949fdc490bfc10a399fc8591062ab2097e369de8cb57bf6403126c2fe55111d8cf81b7fb992cc12a5b26f97859294b7c9c18f73069d13159594959c19f908c71d500009acbd75d6fdc84dba5943f22c44b06e3dda1e622cbbc1a9232b977ff1dbfc7327f13b75f999d3f324a731ff5d19c3e01269183571189ec321b2878d8a62ea86fc08cd32a999311b0b6c1b529ce01e3bdb3da6ad2027a3b201ef71f1adbe8481a79329ef30d1f30c4ab72934cf4ee02e4fdb8b9f7538767641ff468ddc8a59a64f35b6cb9dd8dcafdfc46d9fc16a51f128e8c50856808880e40b57f6bfca9684c0001114fe371bfd7c2354ee7f6f94c23c1e0cabdf54bb6d815c7b8368f634013b112f7f01437f7015597514943dcaefdf0adad057a8622994562536a197831463fdfcd6f33fee167c61ede96217dfbb15b96eb5e841325bea25a15caa1be92a38426941b1984d84a19952d53ce6953f0ad70bf4feba11c40055bb8ce80c2f69f17814153ff74c62580d67174e58a74882ada42286422a3fd6b149537b1860296773a740ea3b24461656b3a021e6e3505be66b4f2ac25bd3bc1676e3720dfa0eee0aaa9d08288acf77afe5219f0dd3b50ed17f61b59f4d82b69f9ae0949eba9cff73122c4611604b7d05c21483bccb52ea2098b3b7d93cc51697f14b8d877f968c9549e60384a8be45ca3351b6ab32e4254b2cdd842171ba84dd25f178e9ae2b5f4a04527ef99c6dc63e4a84d701a0579fad4e98cfc3a0867a17cea4f9d4a9b081d59fc307779efffb6a22efe7c469cc145842e9e3c9e571160d66cfed09e97501a76626726ecfceab3fb96554dcbd7ffa19ec7c39d4ae35143b1d966c042f215f3aaa27936a9fb7cfc3c0162473f84c0eab177cbb6c232204c2b1189ccacd861b4ceb8d74c8bc10d1c39e261c80ba030afc013ec1e02f16f9fbb84ace375fe7f387005f0c97784ef75a640fbae5910a0aa4e722ace81a38bd3f842d20b78fffeb76fefb4665a30ac3a9d2946429a0e5e2642d31469de09866e7ba560ae6b3fa1579e8bbd4a1921fff8f46ab41811f6f230a6f31f2728d93ecd91946535597414a043f373c1176bcdbd43d14eb1fa5baf1b201c562760991b4f8e3506b41326dbf9608b7da145c8d3cf24d3f4df38420d0e78a00d93e17eeca6602709e744563064a0b50e0587a2294fbbd18454ab33f94ae2a08b80882ad3adcd2efb905a0f85098f8c05f7e069f261b7d7a4f33ee181d13739b7b56c9f3d16e1a1303fe970cf269063caf20825e655272a68a1cd4ec81dd5e47e878516374b2574c80c497260b2b969415524d8d37ce1e29e74c1a38972bfde75d023625627d6a7231b6ffb54c5baf4e3882917cccfe92401cc1e02d62281ecfaccdefd5a98ec0850438adcdfb95eae1ff353fb45dfc1c8956f3db012ab9ccf5b2aab26feba85468427cd0cb10b6b6673d30685371c3b30896fe4e0c630929a2bd6a1d2b7105f857f7ad2e087d7fa77d67d590d0c3c1091ca769e95d8b849c7d2130fad97b5d96ff4b2c9b9a9d6a37acd3221d0181dbbe18e9fec7a2955e87ffdc617ffffda600bcd7530f98e9ee3157fa1a1503e630e5ea907ca65886e4459c4a2b39ad974e22359ffb9c1a367ba01acf47624e26fe595d13f112152dd43bb7abf58be29d3a2686da344a364438589dd8e01977654e5fcb7f456cf2d8b8582b7ea42b8ca69e2956a6cb2a805065b30e1d0b43ce8a155d071087a5fdfc134eb02269657bd82cf8ae5fe81bfe214fbd4376d3827b86f2b39e7394338a6736e7da8ace3767f3e1411a38855f972727c586e5dc4c6d3b446a8e1a67420c5f09bfe142b54c5a560f63f46de055651cc45d4606ef887f144065338a9a11576d0124297d21a8361c048b3d8782f2881800627ac7358100107464b37ce83513f3da204590b41943f263a73de9a0ca118b7ff1ea3aaeba5e4b185787940f30e1fdc1148a6c4b9fec965b5c2f1925fd03c1e77cd29b17a99e23fc0868000000045584946ba00000045786966000049492a000800000006001201030001000000010000001a01050001000000560000001b010500010000005e0000002801030001000000020000001302030001000000010000006987040001000000660000000000000048000000010000004800000001000000060000900700040000003032313001910700040000000102030000a00700040000003031303001a0030001000000ffff000002a00400010000004600000003a00400010000004600000000000000, 0, '2025-10-01 19:55:53');

-- Volcando estructura para tabla pisi.alumnos
CREATE TABLE IF NOT EXISTS `alumnos` (
  `matricula_alum` int NOT NULL,
  `nombres_alum` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ape_materno_alum` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ape_paterno_alum` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `edad_alum` int DEFAULT NULL,
  `sexo` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `correo_alum` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fe_nacimiento_alum` date DEFAULT NULL,
  `id_carrera` int NOT NULL,
  `id_facultad` int NOT NULL,
  `idestres_cisco` int DEFAULT NULL,
  PRIMARY KEY (`matricula_alum`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla pisi.alumnos: ~5 rows (aproximadamente)
INSERT INTO `alumnos` (`matricula_alum`, `nombres_alum`, `ape_materno_alum`, `ape_paterno_alum`, `edad_alum`, `sexo`, `correo_alum`, `fe_nacimiento_alum`, `id_carrera`, `id_facultad`, `idestres_cisco`) VALUES
	(191263, 'PABLO', 'GáNDARA', 'HERNáNDEZ', 25, 'MASCULINO', '191263@mail.unacar.mx', '2000-07-24', 3, 2, NULL);

-- Volcando estructura para tabla pisi.ansiedad
CREATE TABLE IF NOT EXISTS `ansiedad` (
  `id_ansiedad` int NOT NULL,
  `id_dass` int NOT NULL,
  `p2` int NOT NULL,
  `p4` int NOT NULL,
  `p7` int NOT NULL,
  `p9` int NOT NULL,
  `p15` int NOT NULL,
  `p19` int NOT NULL,
  `p20` int NOT NULL,
  `total_ansiedad` int NOT NULL,
  `status_ansiedad` varchar(1000) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla pisi.ansiedad: ~0 rows (aproximadamente)

-- Volcando estructura para tabla pisi.apgar
CREATE TABLE IF NOT EXISTS `apgar` (
  `id_apgar` int NOT NULL,
  `p1` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `p2` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `p3` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `p4` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `p5` varchar(45) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla pisi.apgar: ~0 rows (aproximadamente)

-- Volcando estructura para tabla pisi.autoactualizacion
CREATE TABLE IF NOT EXISTS `autoactualizacion` (
  `id_actualizacion` int NOT NULL AUTO_INCREMENT,
  `id_cuestionario` int NOT NULL,
  `p3` int NOT NULL,
  `p8` int NOT NULL,
  `p9` int NOT NULL,
  `p12` int NOT NULL,
  `p16` int NOT NULL,
  `p17` int NOT NULL,
  `p21` int NOT NULL,
  `p23` int NOT NULL,
  `p29` int NOT NULL,
  `p34` int NOT NULL,
  `p37` int NOT NULL,
  `p44` int NOT NULL,
  `p48` int NOT NULL,
  `total_autoactualizacion` int NOT NULL,
  `saludable_autoactualizacion` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_actualizacion`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla pisi.autoactualizacion: ~12 rows (aproximadamente)
INSERT INTO `autoactualizacion` (`id_actualizacion`, `id_cuestionario`, `p3`, `p8`, `p9`, `p12`, `p16`, `p17`, `p21`, `p23`, `p29`, `p34`, `p37`, `p44`, `p48`, `total_autoactualizacion`, `saludable_autoactualizacion`) VALUES
	(1, 8, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 13, 'No Saludable');

-- Volcando estructura para tabla pisi.carrera
CREATE TABLE IF NOT EXISTS `carrera` (
  `id_carrera` int NOT NULL,
  `nombre_carrera` varchar(80) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_facultad` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla pisi.carrera: ~29 rows (aproximadamente)
INSERT INTO `carrera` (`id_carrera`, `nombre_carrera`, `id_facultad`) VALUES
	(1, 'DERECHO', 1),
	(2, 'CRIMINOLOGÍA CRIMINALÍSTICA', 1),
	(3, 'INGENIERÍA EN SISTEMAS COMPUTACIONALES', 2),
	(4, 'INGENIERÍA EN DISEÑO MULTIMEDIA', 2),
	(5, 'INGENIERÍA EN TECNOLOGÍAS DE CÓMPUTO Y COMUNICACIONES ', 2),
	(6, 'ADMINISTRACIÓN DE EMPRESAS', 3),
	(7, 'CONTADURÍA', 3),
	(8, 'ADMINISTRACIÓN TURÍSTICA', 3),
	(9, 'MERCADOTECNIA', 3),
	(10, 'NEGOCIOS INTERNACIONALES', 3),
	(11, 'INGENIERÍA EN MECATRÓNICA', 4),
	(12, 'INGENIERÍA CIVIL', 4),
	(13, 'INGENIERÍA MECÁNICA', 4),
	(14, 'INGENIERÍA GEOFÍSICA ', 4),
	(15, 'INGENIERÍA EN ENERGÍA', 4),
	(16, 'ARQUITECTURA SUSTENTABLE', 4),
	(17, 'EDUCACIÓN FÍSICA Y DEPORTE', 5),
	(18, 'ENFERMERÍA', 5),
	(19, 'NUTRICIÓN', 5),
	(20, 'PSICOLOGÍA', 5),
	(21, 'FISIOTERAPIA', 5),
	(22, 'MEDICINA', 5),
	(23, 'EDUCACIÓN', 6),
	(24, 'LENGUA INGLESA', 6),
	(25, 'COMUNICACIÓN Y GESTIÓN CULTURAL', 6),
	(26, 'BIOLOGÍA MARINA', 7),
	(27, 'LICENCIATURA EN INGENIERÍA QUÍMICA', 8),
	(28, 'LICENCIATURA EN INGENIERÍA PETROLERA', 8),
	(29, 'LICENCIATURA EN INGENIERÍA GEOLÓGICA', 8);

-- Volcando estructura para tabla pisi.dass
CREATE TABLE IF NOT EXISTS `dass` (
  `id_cuestionario` int NOT NULL AUTO_INCREMENT,
  `matricula_alum` int NOT NULL,
  `total_depresion` int DEFAULT NULL,
  `total_ansiedad` int DEFAULT NULL,
  `total_estres` int DEFAULT NULL,
  `total_general` int DEFAULT NULL,
  PRIMARY KEY (`id_cuestionario`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla pisi.dass: ~8 rows (aproximadamente)
INSERT INTO `dass` (`id_cuestionario`, `matricula_alum`, `total_depresion`, `total_ansiedad`, `total_estres`, `total_general`) VALUES
	(10, 191263, 32, 38, 36, 106);

-- Volcando estructura para tabla pisi.dass_ansiedad
CREATE TABLE IF NOT EXISTS `dass_ansiedad` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_cuestionario` int DEFAULT NULL,
  `p2` int DEFAULT NULL,
  `p4` int DEFAULT NULL,
  `p7` int DEFAULT NULL,
  `p9` int DEFAULT NULL,
  `p15` int DEFAULT NULL,
  `p19` int DEFAULT NULL,
  `p20` int DEFAULT NULL,
  `total_ansiedad` int DEFAULT NULL,
  `severidad` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla pisi.dass_ansiedad: ~8 rows (aproximadamente)
INSERT INTO `dass_ansiedad` (`id`, `id_cuestionario`, `p2`, `p4`, `p7`, `p9`, `p15`, `p19`, `p20`, `total_ansiedad`, `severidad`) VALUES
	(13, 10, 3, 3, 2, 3, 2, 3, 3, 38, 'Extremadamente Severo');

-- Volcando estructura para tabla pisi.dass_depresion
CREATE TABLE IF NOT EXISTS `dass_depresion` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_cuestionario` int DEFAULT NULL,
  `p3` int DEFAULT NULL,
  `p5` int DEFAULT NULL,
  `p10` int DEFAULT NULL,
  `p13` int DEFAULT NULL,
  `p16` int DEFAULT NULL,
  `p17` int DEFAULT NULL,
  `p21` int DEFAULT NULL,
  `total_depresion` int DEFAULT NULL,
  `severidad` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla pisi.dass_depresion: ~8 rows (aproximadamente)
INSERT INTO `dass_depresion` (`id`, `id_cuestionario`, `p3`, `p5`, `p10`, `p13`, `p16`, `p17`, `p21`, `total_depresion`, `severidad`) VALUES
	(13, 10, 2, 2, 2, 2, 3, 3, 2, 32, 'Extremadamente Severo');

-- Volcando estructura para tabla pisi.dass_estres
CREATE TABLE IF NOT EXISTS `dass_estres` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_cuestionario` int DEFAULT NULL,
  `p1` int DEFAULT NULL,
  `p6` int DEFAULT NULL,
  `p8` int DEFAULT NULL,
  `p11` int DEFAULT NULL,
  `p12` int DEFAULT NULL,
  `p14` int DEFAULT NULL,
  `p18` int DEFAULT NULL,
  `total_estres` int DEFAULT NULL,
  `severidad` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla pisi.dass_estres: ~8 rows (aproximadamente)
INSERT INTO `dass_estres` (`id`, `id_cuestionario`, `p1`, `p6`, `p8`, `p11`, `p12`, `p14`, `p18`, `total_estres`, `severidad`) VALUES
	(13, 10, 3, 2, 3, 2, 3, 2, 3, 36, 'Extremadamente Severo');

-- Volcando estructura para tabla pisi.datos_fisicos_alumnos
CREATE TABLE IF NOT EXISTS `datos_fisicos_alumnos` (
  `matricula_alum` int NOT NULL,
  `iddatos_fisicos_alumno` int NOT NULL AUTO_INCREMENT,
  `cintura` decimal(5,2) DEFAULT NULL,
  `cadera` decimal(5,2) DEFAULT NULL,
  `clasificacion_cintura_cadera` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `icc` decimal(5,2) DEFAULT NULL,
  `clasificacion_de_icc` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `peso` decimal(5,2) DEFAULT NULL,
  `talla` decimal(5,2) DEFAULT NULL,
  `imc` decimal(5,2) DEFAULT NULL,
  `clasificacion_imc` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `mb` decimal(5,2) DEFAULT NULL,
  `get1` decimal(5,2) DEFAULT NULL,
  `porcentaje_masa_grasa` decimal(5,2) DEFAULT NULL,
  `valor_ideal_porcentaje_grasa` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `clasificacion_porcentaje_grasa` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `masa_magra` decimal(5,2) DEFAULT NULL,
  `agua_total` decimal(5,2) DEFAULT NULL,
  `porcentaje_agua_total` decimal(5,2) DEFAULT NULL,
  `glucosa` decimal(5,2) DEFAULT NULL,
  `clasificacion_glucosa` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `trigliceridos` decimal(5,2) DEFAULT NULL,
  `clasificacion_trigliceridos` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `colesterol` decimal(5,2) DEFAULT NULL,
  `clasificacion_colesterol` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tension_arterial` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `clasificacion_tension_arterial` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  PRIMARY KEY (`iddatos_fisicos_alumno`),
  KEY `matricula_alum` (`matricula_alum`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla pisi.datos_fisicos_alumnos: ~1 rows (aproximadamente)
INSERT INTO `datos_fisicos_alumnos` (`matricula_alum`, `iddatos_fisicos_alumno`, `cintura`, `cadera`, `clasificacion_cintura_cadera`, `icc`, `clasificacion_de_icc`, `peso`, `talla`, `imc`, `clasificacion_imc`, `mb`, `get1`, `porcentaje_masa_grasa`, `valor_ideal_porcentaje_grasa`, `clasificacion_porcentaje_grasa`, `masa_magra`, `agua_total`, `porcentaje_agua_total`, `glucosa`, `clasificacion_glucosa`, `trigliceridos`, `clasificacion_trigliceridos`, `colesterol`, `clasificacion_colesterol`, `tension_arterial`, `clasificacion_tension_arterial`, `fecha`) VALUES
	(191263, 1, 80.00, 90.00, 'Riesgo Bajo', 0.89, 'Ginecoide', 150.00, 120.00, 104.17, 'Obesidad grado 3 (mórbida)', 45.00, 54.00, 12.00, '12', 'Recomendado', 80.00, 45.00, 30.00, 12.00, 'Normal', 50.00, 'Normal', 150.00, 'Optimo', '180/120', 'Hipertensión Grado 2', '2025-10-01');

-- Volcando estructura para tabla pisi.datos_fisicos_maestros
CREATE TABLE IF NOT EXISTS `datos_fisicos_maestros` (
  `iddatos_fisicos_maestros` int NOT NULL,
  `cintura` decimal(5,2) DEFAULT NULL,
  `cadera` decimal(5,2) DEFAULT NULL,
  `clasificacion_cintura_cadera` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `icc` decimal(5,2) DEFAULT NULL,
  `clasificacion_de_icc` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `peso` decimal(5,2) DEFAULT NULL,
  `talla` decimal(5,2) DEFAULT NULL,
  `imc` decimal(5,2) DEFAULT NULL,
  `clasificacion_imc` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `mb` decimal(5,2) DEFAULT NULL,
  `get` decimal(5,2) DEFAULT NULL,
  `porcentaje_masa_grasa` decimal(5,2) DEFAULT NULL,
  `valor_ideal_porcentaje_grasa` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `clasificacion_porcentaje_grasa` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `masa_magra` decimal(5,2) DEFAULT NULL,
  `agua_total` decimal(5,2) DEFAULT NULL,
  `porcentaje_agua_total` decimal(5,2) DEFAULT NULL,
  `glucosa` decimal(5,2) DEFAULT NULL,
  `clasificacion_glucosa` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `trigliceridos` decimal(5,2) DEFAULT NULL,
  `clasificacion_trigliceridos` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `colesterol` decimal(5,2) DEFAULT NULL,
  `clasificacion_colesterol` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tension_arterial` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `clasificacion_tension_arterial` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `matricula_mae` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla pisi.datos_fisicos_maestros: ~0 rows (aproximadamente)

-- Volcando estructura para tabla pisi.depresion
CREATE TABLE IF NOT EXISTS `depresion` (
  `id_depresion` int NOT NULL,
  `id_dass` int NOT NULL,
  `p3` int NOT NULL,
  `p5` int NOT NULL,
  `p10` int NOT NULL,
  `p13` int NOT NULL,
  `p16` int NOT NULL,
  `p17` int NOT NULL,
  `p21` int NOT NULL,
  `total_depresion` int NOT NULL,
  `status_depresion` varchar(1000) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla pisi.depresion: ~0 rows (aproximadamente)

-- Volcando estructura para tabla pisi.ejercicio
CREATE TABLE IF NOT EXISTS `ejercicio` (
  `id_ejercicio` int NOT NULL AUTO_INCREMENT,
  `id_cuestionario` int NOT NULL,
  `p4` int NOT NULL,
  `p13` int NOT NULL,
  `p22` int NOT NULL,
  `p30` int NOT NULL,
  `p38` int NOT NULL,
  `total_ejercicio` int NOT NULL,
  `saludable_ejercicio` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_ejercicio`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla pisi.ejercicio: ~12 rows (aproximadamente)
INSERT INTO `ejercicio` (`id_ejercicio`, `id_cuestionario`, `p4`, `p13`, `p22`, `p30`, `p38`, `total_ejercicio`, `saludable_ejercicio`) VALUES
	(1, 8, 1, 1, 1, 1, 1, 5, 'No Saludable');

-- Volcando estructura para tabla pisi.estilo_de_vida
CREATE TABLE IF NOT EXISTS `estilo_de_vida` (
  `id_cuestionario` int NOT NULL AUTO_INCREMENT,
  `matricula_alum` int NOT NULL,
  `fecha` datetime NOT NULL,
  `total` int NOT NULL,
  `estado_saludable` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_cuestionario`),
  KEY `fk_matricula_alum` (`matricula_alum`),
  CONSTRAINT `fk_matricula_alum_estilo_vida` FOREIGN KEY (`matricula_alum`) REFERENCES `alumnos` (`matricula_alum`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla pisi.estilo_de_vida: ~5 rows (aproximadamente)
INSERT INTO `estilo_de_vida` (`id_cuestionario`, `matricula_alum`, `fecha`, `total`, `estado_saludable`) VALUES
	(8, 191263, '2025-10-01 19:36:01', 50, 'No Saludable');

-- Volcando estructura para tabla pisi.estres
CREATE TABLE IF NOT EXISTS `estres` (
  `id_estres` int NOT NULL,
  `id_dass` int NOT NULL,
  `p1` int NOT NULL,
  `p6` int NOT NULL,
  `p8` int NOT NULL,
  `p11` int NOT NULL,
  `p12` int NOT NULL,
  `p14` int NOT NULL,
  `p18` int NOT NULL,
  `total_estres` int NOT NULL,
  `status_estres` varchar(1000) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla pisi.estres: ~0 rows (aproximadamente)

-- Volcando estructura para tabla pisi.estres_cisco
CREATE TABLE IF NOT EXISTS `estres_cisco` (
  `idestres_cisco` int NOT NULL,
  `pre_inicial` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pre_2` int DEFAULT NULL,
  `p1` text COLLATE utf8mb4_general_ci,
  `p2` text COLLATE utf8mb4_general_ci,
  `p3` text COLLATE utf8mb4_general_ci,
  `p4` text COLLATE utf8mb4_general_ci,
  `p5` text COLLATE utf8mb4_general_ci,
  `p6` text COLLATE utf8mb4_general_ci,
  `p7` text COLLATE utf8mb4_general_ci,
  `p8` text COLLATE utf8mb4_general_ci,
  `p9` text COLLATE utf8mb4_general_ci,
  `p10` text COLLATE utf8mb4_general_ci,
  `p11` text COLLATE utf8mb4_general_ci,
  `p12` text COLLATE utf8mb4_general_ci,
  `p13` text COLLATE utf8mb4_general_ci,
  `p14` text COLLATE utf8mb4_general_ci,
  `p15` text COLLATE utf8mb4_general_ci,
  `p16` text COLLATE utf8mb4_general_ci,
  `p17` text COLLATE utf8mb4_general_ci,
  `p18` text COLLATE utf8mb4_general_ci,
  `p19` text COLLATE utf8mb4_general_ci,
  `p20` text COLLATE utf8mb4_general_ci,
  `p21` text COLLATE utf8mb4_general_ci,
  `p22` text COLLATE utf8mb4_general_ci,
  `p23` text COLLATE utf8mb4_general_ci,
  `p24` text COLLATE utf8mb4_general_ci,
  `p25` text COLLATE utf8mb4_general_ci,
  `p26` text COLLATE utf8mb4_general_ci,
  `p27` text COLLATE utf8mb4_general_ci,
  `p28` text COLLATE utf8mb4_general_ci,
  `p29` text COLLATE utf8mb4_general_ci,
  `p30` text COLLATE utf8mb4_general_ci,
  `p31` text COLLATE utf8mb4_general_ci,
  `p32` text COLLATE utf8mb4_general_ci,
  `p33` text COLLATE utf8mb4_general_ci,
  `p34` text COLLATE utf8mb4_general_ci,
  `p35` text COLLATE utf8mb4_general_ci,
  `p36` text COLLATE utf8mb4_general_ci,
  `p37` text COLLATE utf8mb4_general_ci,
  `p38` text COLLATE utf8mb4_general_ci,
  `p39` text COLLATE utf8mb4_general_ci,
  `p40` text COLLATE utf8mb4_general_ci,
  `p41` text COLLATE utf8mb4_general_ci,
  `p42` text COLLATE utf8mb4_general_ci,
  `p43` text COLLATE utf8mb4_general_ci,
  `p44` text COLLATE utf8mb4_general_ci,
  `p45` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla pisi.estres_cisco: ~0 rows (aproximadamente)

-- Volcando estructura para tabla pisi.estres_maestros
CREATE TABLE IF NOT EXISTS `estres_maestros` (
  `idestres_maestro` int NOT NULL,
  `p1` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `p2` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `p3` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `p4` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `p5` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `p6` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `p7` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `p8` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `p9` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `p10` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `p11` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `p12` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `p13` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `p14` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla pisi.estres_maestros: ~0 rows (aproximadamente)

-- Volcando estructura para tabla pisi.facultad
CREATE TABLE IF NOT EXISTS `facultad` (
  `id_facultad` int NOT NULL,
  `nombre_facultad` varchar(80) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla pisi.facultad: ~8 rows (aproximadamente)
INSERT INTO `facultad` (`id_facultad`, `nombre_facultad`) VALUES
	(1, 'FACULTAD DE DERECHO\r\n'),
	(2, 'FACULTAD DE CIENCIAS DE LA INFORMACIÓN'),
	(3, 'FACULTAD DE CIENCIAS ECONÓMICAS ADMINISTRATIVAS'),
	(4, 'FACULTAD DE INGENIERÍA'),
	(5, 'FACULTAD DE CIENCIAS DE LA SALUD'),
	(6, 'FACULTAD DE CIENCIAS EDUCATIVAS '),
	(7, 'FACULTAD DE CIENCIAS NATURALES Y EXACTAS '),
	(8, 'FACULTAD DE QUÍMICA');

-- Volcando estructura para tabla pisi.historial_alumnos
CREATE TABLE IF NOT EXISTS `historial_alumnos` (
  `sobrepeso` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `diabetes` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `hipertension` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `trigliceridos` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `colesterol` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `hepatitis` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `higado_graso` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cardiopatias` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nefropatias` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `estreñimiento` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `gastritis` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `colitis` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cancer` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `otros` text COLLATE utf8mb4_general_ci,
  `fecha_historial` date DEFAULT NULL,
  `matricula_alum` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla pisi.historial_alumnos: ~1 rows (aproximadamente)
INSERT INTO `historial_alumnos` (`sobrepeso`, `diabetes`, `hipertension`, `trigliceridos`, `colesterol`, `hepatitis`, `higado_graso`, `cardiopatias`, `nefropatias`, `estreñimiento`, `gastritis`, `colitis`, `cancer`, `otros`, `fecha_historial`, `matricula_alum`) VALUES
	('ninguno', 'ninguno', 'ninguno', 'ninguno', 'ninguno', 'ninguno', 'ninguno', 'ninguno', 'ninguno', 'ninguno', 'ninguno', 'ninguno', 'ninguno', 'No', '2025-10-01', 191263);

-- Volcando estructura para tabla pisi.historial_maestros
CREATE TABLE IF NOT EXISTS `historial_maestros` (
  `sobrepeso` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `diabetes` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `hipertension` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `trigliceridos` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `colesterol` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `hepatitis` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `higado_graso` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cardiopatias` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `estreñimiento` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `gastritis` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `colitis` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cancer` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `otros` text COLLATE utf8mb4_general_ci,
  `fecha_historial` date DEFAULT NULL,
  `matricula_mae` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla pisi.historial_maestros: ~0 rows (aproximadamente)

-- Volcando estructura para tabla pisi.maestros
CREATE TABLE IF NOT EXISTS `maestros` (
  `matricula_mae` int NOT NULL,
  `nombres_mae` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ape_materno_mae` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ape_paterno_mae` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `edad_mae` int DEFAULT NULL,
  `sexo_mae` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `correo_mae` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fe_nacimiento_alum` date DEFAULT NULL,
  `id_facultad` int NOT NULL,
  `idestres_maestro` int NOT NULL,
  `id_apgar` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla pisi.maestros: ~0 rows (aproximadamente)

-- Volcando estructura para tabla pisi.manejo_de_estres
CREATE TABLE IF NOT EXISTS `manejo_de_estres` (
  `id_manejoestres` int NOT NULL AUTO_INCREMENT,
  `id_cuestionario` int NOT NULL,
  `p6` int NOT NULL,
  `p11` int NOT NULL,
  `p27` int NOT NULL,
  `p36` int NOT NULL,
  `p40` int NOT NULL,
  `p41` int NOT NULL,
  `p45` int NOT NULL,
  `total_manejoestres` int NOT NULL,
  `saludable_manejo` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_manejoestres`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla pisi.manejo_de_estres: ~12 rows (aproximadamente)
INSERT INTO `manejo_de_estres` (`id_manejoestres`, `id_cuestionario`, `p6`, `p11`, `p27`, `p36`, `p40`, `p41`, `p45`, `total_manejoestres`, `saludable_manejo`) VALUES
	(1, 8, 1, 1, 1, 1, 1, 1, 1, 7, 'No Saludable');

-- Volcando estructura para tabla pisi.nutricion
CREATE TABLE IF NOT EXISTS `nutricion` (
  `id_nutricion` int NOT NULL AUTO_INCREMENT,
  `id_cuestionario` int NOT NULL,
  `p1` int NOT NULL,
  `p5` int NOT NULL,
  `p14` int NOT NULL,
  `p19` int NOT NULL,
  `p26` int NOT NULL,
  `p35` int NOT NULL,
  `total_nutricion` int NOT NULL,
  `saludable` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_nutricion`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla pisi.nutricion: ~12 rows (aproximadamente)
INSERT INTO `nutricion` (`id_nutricion`, `id_cuestionario`, `p1`, `p5`, `p14`, `p19`, `p26`, `p35`, `total_nutricion`, `saludable`) VALUES
	(1, 8, 3, 1, 1, 1, 1, 1, 8, 'No Saludable');

-- Volcando estructura para tabla pisi.patologias_alumnos
CREATE TABLE IF NOT EXISTS `patologias_alumnos` (
  `idpatologias_personal` int NOT NULL,
  `enfermedad` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tratamiento` text COLLATE utf8mb4_general_ci,
  `fecha` date DEFAULT NULL,
  `matricula_alum` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla pisi.patologias_alumnos: ~7 rows (aproximadamente)

-- Volcando estructura para tabla pisi.patologias_maestros
CREATE TABLE IF NOT EXISTS `patologias_maestros` (
  `idpatologias_personal` int NOT NULL,
  `enfermedad` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tratamiento` text COLLATE utf8mb4_general_ci,
  `fecha` date DEFAULT NULL,
  `matricula_mae` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla pisi.patologias_maestros: ~0 rows (aproximadamente)

-- Volcando estructura para tabla pisi.registro_ingresos
CREATE TABLE IF NOT EXISTS `registro_ingresos` (
  `id` int NOT NULL,
  `usuario` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `nombre_completo` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `rol` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `fecha_ingreso` datetime NOT NULL,
  `fecha_salida` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla pisi.registro_ingresos: ~38 rows (aproximadamente)
INSERT INTO `registro_ingresos` (`id`, `usuario`, `nombre_completo`, `rol`, `fecha_ingreso`, `fecha_salida`) VALUES
	(0, '190039', 'Brando del jesus', 'Administrador', '2025-10-01 19:55:53', NULL);

-- Volcando estructura para tabla pisi.salud
CREATE TABLE IF NOT EXISTS `salud` (
  `id_salud` int NOT NULL AUTO_INCREMENT,
  `id_cuestionario` int NOT NULL,
  `p2` int NOT NULL,
  `p7` int NOT NULL,
  `p15` int NOT NULL,
  `p20` int NOT NULL,
  `p28` int NOT NULL,
  `p32` int NOT NULL,
  `p33` int NOT NULL,
  `p42` int NOT NULL,
  `p43` int NOT NULL,
  `p46` int NOT NULL,
  `total_salud` int NOT NULL,
  `saludable_salud` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_salud`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla pisi.salud: ~12 rows (aproximadamente)
INSERT INTO `salud` (`id_salud`, `id_cuestionario`, `p2`, `p7`, `p15`, `p20`, `p28`, `p32`, `p33`, `p42`, `p43`, `p46`, `total_salud`, `saludable_salud`) VALUES
	(1, 8, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 10, 'No Saludable');

-- Volcando estructura para tabla pisi.soporte_interpersonal
CREATE TABLE IF NOT EXISTS `soporte_interpersonal` (
  `id_soporte_interpersonal` int NOT NULL AUTO_INCREMENT,
  `id_cuestionario` int NOT NULL,
  `p10` int NOT NULL,
  `p18` int NOT NULL,
  `p24` int NOT NULL,
  `p25` int NOT NULL,
  `p31` int NOT NULL,
  `p39` int NOT NULL,
  `p47` int NOT NULL,
  `total_soporte` int NOT NULL,
  `saludable_soporte` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_soporte_interpersonal`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla pisi.soporte_interpersonal: ~12 rows (aproximadamente)
INSERT INTO `soporte_interpersonal` (`id_soporte_interpersonal`, `id_cuestionario`, `p10`, `p18`, `p24`, `p25`, `p31`, `p39`, `p47`, `total_soporte`, `saludable_soporte`) VALUES
	(1, 8, 1, 1, 1, 1, 1, 1, 1, 7, 'No Saludable');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
