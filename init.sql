/*
SQLyog Community v13.3.0 (64 bit)
MySQL - 11.2.6-MariaDB-ubu2204 : Database - sesame
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`sesame` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

/*Table structure for table `redirects` */

DROP TABLE IF EXISTS `redirects`;

CREATE TABLE `redirects` (
  `id` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `label` varchar(64) NOT NULL,
  `url` varchar(512) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `redirects` */

insert  into `redirects`(`id`,`name`,`label`,`url`,`active`) values 
(1,'lms','LMS','https://lms.eloquencia.org/webservice/rest/server.php',1);

/*Table structure for table `sessions` */

DROP TABLE IF EXISTS `sessions`;

CREATE TABLE `sessions` (
  `id` varchar(128) NOT NULL COMMENT 'Session ID (random hash)',
  `user_id` int(32) unsigned NOT NULL COMMENT 'associated user',
  `email` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  PRIMARY KEY (`id`,`user_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `sessions` */

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(32) unsigned NOT NULL AUTO_INCREMENT COMMENT 'session id',
  `email` varchar(255) NOT NULL COMMENT 'email of the user',
  `login` varchar(32) NOT NULL,
  `firstname` varchar(64) NOT NULL,
  `lastname` varchar(64) NOT NULL,
  `password_hash` varchar(255) NOT NULL COMMENT 'hased password',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'creation timestamp',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`email`,`login`,`firstname`,`lastname`,`password_hash`,`created_at`) values 
(1,'test@test.com','testtest','test','test','$2y$12$eYDQZJ.PmCPm..5b7izzbuohxUcoFxll3KcUVt8VIrlkBjplYnYFm','2025-09-20 21:22:31');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
