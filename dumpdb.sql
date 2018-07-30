-- --------------------------------------------------------
-- Хост:                         127.0.0.1
-- Версия сервера:               5.7.16 - MySQL Community Server (GPL)
-- Операционная система:         Win64
-- HeidiSQL Версия:              9.5.0.5196
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Дамп структуры базы данных dumpdb
CREATE DATABASE IF NOT EXISTS `dumpdb` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `dumpdb`;

-- Дамп структуры для таблица dumpdb.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(64) NOT NULL,
  `salt` varchar(6) NOT NULL,
  `first_name` varchar(64) NOT NULL,
  `last_name` varchar(64) NOT NULL,
  `date_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `date_create` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active` tinyint(1) NOT NULL DEFAULT '10',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы dumpdb.users: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `username`, `email`, `password`, `salt`, `first_name`, `last_name`, `date_update`, `date_create`, `active`) VALUES
	(1, 'admin', 'example@example.com', '2c341ee262dec6031ea9b177b85d0ba6', 'MOdxQF', 'Firstname', 'Lastname', '2018-07-30 12:23:12', '2018-07-30 12:23:12', 10);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

-- Дамп структуры для таблица dumpdb.users_image
CREATE TABLE IF NOT EXISTS `users_image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `src` varchar(255) NOT NULL,
  `date_create` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_users_image_users` (`user_id`),
  CONSTRAINT `FK_users_image_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы dumpdb.users_image: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `users_image` DISABLE KEYS */;
INSERT INTO `users_image` (`id`, `user_id`, `title`, `description`, `src`, `date_create`, `date_update`) VALUES
	(1, 1, 'Test 1', 'Test test test', '/web/files/images/100x100/940f32d49aa2baecc414522036ef38f8.JPG', '2018-07-30 15:12:20', '2018-07-30 15:12:20'),
	(2, 1, 'Test 2', 'Test test test', '/web/files/images/100x100/a7ef9da077fda3e3be853427567bd994.JPG', '2018-07-30 15:14:11', '2018-07-30 15:14:11');
/*!40000 ALTER TABLE `users_image` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
