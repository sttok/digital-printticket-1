-- --------------------------------------------------------
-- Host:                         localhost
-- Versión del servidor:         5.7.24 - MySQL Community Server (GPL)
-- SO del servidor:              Win64
-- HeidiSQL Versión:             10.2.0.5599
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Volcando estructura para tabla printicket.order_childs_digitals
DROP TABLE IF EXISTS `order_childs_digitals`;
CREATE TABLE IF NOT EXISTS `order_childs_digitals` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` bigint(20) NOT NULL,
  `evento_id` bigint(20) NOT NULL,
  `zona_id` bigint(20) NOT NULL,
  `identificador` int(11) DEFAULT NULL,
  `order_child_id` bigint(20) NOT NULL,
  `url` text COLLATE utf8mb4_unicode_ci,
  `provider` text COLLATE utf8mb4_unicode_ci,
  `endosado` tinyint(1) DEFAULT '0',
  `descargas` int(11) DEFAULT '0',
  `permiso_descargar` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla printicket.order_childs_digitals: ~12 rows (aproximadamente)
DELETE FROM `order_childs_digitals`;
/*!40000 ALTER TABLE `order_childs_digitals` DISABLE KEYS */;
INSERT INTO `order_childs_digitals` (`id`, `admin_id`, `evento_id`, `zona_id`, `identificador`, `order_child_id`, `url`, `provider`, `endosado`, `descargas`, `permiso_descargar`, `created_at`, `updated_at`) VALUES
	(11, 5, 29, 81, 4173, 21574, 'http://printticket.test/storage/ticket-digital/concierto-noche-de-reyes/4173-cortesia.pdf', 'local', 0, 1, 0, '2022-07-19 14:28:15', '2022-07-26 11:41:15'),
	(12, 5, 29, 81, 4184, 21575, 'http://printticket.test/storage/ticket-digital/concierto-noche-de-reyes/4184-cortesia.pdf', 'local', 0, 0, 0, '2022-07-19 14:28:15', '2022-07-26 11:41:15'),
	(13, 5, 29, 81, 4195, 21576, 'http://printticket.test/storage/ticket-digital/concierto-noche-de-reyes/4195-cortesia.pdf', 'local', 0, 0, 0, '2022-07-19 14:28:15', '2022-07-26 11:41:15'),
	(14, 5, 29, 81, 4208, 21577, 'http://printticket.test/storage/ticket-digital/concierto-noche-de-reyes/4208-cortesia.pdf', 'local', 0, 0, 0, '2022-07-19 14:28:15', '2022-07-26 11:41:15'),
	(15, 5, 29, 233, 3599, 117981, 'http://printticket.testticket-digital/concierto-noche-de-reyes/palco-alfombra-roja/palco-7/3599-palco-alfombra-roja.pdf', 'local', 0, 9, 1, '2022-07-26 16:29:12', '2022-07-28 18:14:38'),
	(16, 5, 29, 233, 3622, 117983, 'http://printticket.testticket-digital/concierto-noche-de-reyes/palco-alfombra-roja/palco-7/3622-palco-alfombra-roja.pdf', 'local', 0, 9, 1, '2022-07-26 16:29:13', '2022-07-28 18:14:38'),
	(17, 5, 29, 233, 3631, 117984, 'http://printticket.testticket-digital/concierto-noche-de-reyes/palco-alfombra-roja/palco-adicional/3631-palco-alfombra-roja.pdf', 'local', 0, 9, 1, '2022-07-26 16:29:13', '2022-07-28 18:14:38'),
	(18, 5, 29, 233, 3678, 117992, 'http://printticket.testticket-digital/concierto-noche-de-reyes/palco-alfombra-roja/palco-8/3678-palco-alfombra-roja.pdf', 'local', 0, 9, 1, '2022-07-26 16:29:13', '2022-07-28 18:14:38'),
	(19, 5, 29, 233, 3693, 117993, 'http://printticket.testticket-digital/concierto-noche-de-reyes/palco-alfombra-roja/palco-8/3693-palco-alfombra-roja.pdf', 'local', 0, 9, 1, '2022-07-26 16:29:13', '2022-07-28 18:14:38'),
	(20, 5, 29, 233, 3749, 118000, 'http://printticket.testticket-digital/concierto-noche-de-reyes/palco-alfombra-roja/palco-9/3749-palco-alfombra-roja.pdf', 'local', 0, 9, 1, '2022-07-26 16:29:13', '2022-07-28 18:14:38'),
	(21, 5, 29, 233, 3758, 118001, 'http://printticket.testticket-digital/concierto-noche-de-reyes/palco-alfombra-roja/palco-9/3758-palco-alfombra-roja.pdf', 'local', 0, 9, 1, '2022-07-26 16:29:13', '2022-07-28 18:14:38'),
	(22, 5, 29, 233, 3765, 118002, 'http://printticket.testticket-digital/concierto-noche-de-reyes/palco-alfombra-roja/palco-9/3765-palco-alfombra-roja.pdf', 'local', 1, 0, 1, '2022-07-26 16:29:13', '2022-07-26 17:01:37');
/*!40000 ALTER TABLE `order_childs_digitals` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
