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

-- Volcando estructura para tabla printicket.direcciones_eventos
DROP TABLE IF EXISTS `direcciones_eventos`;
CREATE TABLE IF NOT EXISTS `direcciones_eventos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `evento_id` bigint(20) NOT NULL,
  `entrada_id` bigint(20) NOT NULL,
  `usuario_id` bigint(20) NOT NULL,
  `direccion_usuario` bigint(20) unsigned NOT NULL,
  `path` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla printicket.direcciones_eventos: ~0 rows (aproximadamente)
DELETE FROM `direcciones_eventos`;
/*!40000 ALTER TABLE `direcciones_eventos` DISABLE KEYS */;
/*!40000 ALTER TABLE `direcciones_eventos` ENABLE KEYS */;

-- Volcando estructura para tabla printicket.direcciones_evento_palcos
DROP TABLE IF EXISTS `direcciones_evento_palcos`;
CREATE TABLE IF NOT EXISTS `direcciones_evento_palcos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `direccion_evento_id` bigint(20) unsigned NOT NULL,
  `palco` int(11) NOT NULL,
  `path` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla printicket.direcciones_evento_palcos: ~0 rows (aproximadamente)
DELETE FROM `direcciones_evento_palcos`;
/*!40000 ALTER TABLE `direcciones_evento_palcos` DISABLE KEYS */;
/*!40000 ALTER TABLE `direcciones_evento_palcos` ENABLE KEYS */;

-- Volcando estructura para tabla printicket.direcciones_usuarios
DROP TABLE IF EXISTS `direcciones_usuarios`;
CREATE TABLE IF NOT EXISTS `direcciones_usuarios` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `usuario_id` bigint(20) unsigned NOT NULL,
  `path` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `drivers` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla printicket.direcciones_usuarios: ~0 rows (aproximadamente)
DELETE FROM `direcciones_usuarios`;
/*!40000 ALTER TABLE `direcciones_usuarios` DISABLE KEYS */;
/*!40000 ALTER TABLE `direcciones_usuarios` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
