--
-- DbNinja v3.2.6 for MySQL
--
-- Dump date: 2014-08-11 17:37:49 (UTC)
-- Server version: 5.5.38-0ubuntu0.14.04.1
-- Database: sexo
--

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;

CREATE DATABASE `sexo` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `sexo`;

--
-- Structure for table: category
--
CREATE TABLE `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(2000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;


--
-- Structure for table: city
--
CREATE TABLE `city` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;


--
-- Structure for table: image
--
CREATE TABLE `image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(200) DEFAULT NULL,
  `sex_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_image_sex1_idx` (`sex_id`),
  CONSTRAINT `fk_image_sex1` FOREIGN KEY (`sex_id`) REFERENCES `sex` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Structure for table: publicity
--
CREATE TABLE `publicity` (
  `publicity_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `user_email` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`publicity_id`),
  KEY `fk_publicity_user1_idx` (`user_id`,`user_email`),
  CONSTRAINT `fk_publicity_user1` FOREIGN KEY (`user_id`, `user_email`) REFERENCES `user` (`id`, `email`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Structure for table: sex
--
CREATE TABLE `sex` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(30) NOT NULL,
  `local` varchar(30) NOT NULL,
  `description` varchar(4000) NOT NULL,
  `sendemail` enum('YES','NO','CONTACTO') NOT NULL DEFAULT 'NO',
  `created` datetime DEFAULT NULL,
  `expiration` datetime NOT NULL,
  `upaded` datetime DEFAULT NULL,
  `url` varchar(30) NOT NULL,
  `state` enum('PENDENTE','APROVADO','RENOVAR') NOT NULL DEFAULT 'APROVADO',
  `type` enum('NORMAL','DESTAQUE_GRANDE','DESTAQUE_PEQUENO') NOT NULL DEFAULT 'NORMAL',
  `email` varchar(45) DEFAULT NULL,
  `city_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_sex_city1_idx` (`city_id`),
  KEY `fk_sex_category1_idx` (`category_id`),
  CONSTRAINT `fk_sex_category1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_sex_city1` FOREIGN KEY (`city_id`) REFERENCES `city` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Structure for table: user
--
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) DEFAULT NULL,
  `password` varchar(30) DEFAULT NULL,
  `confirmation` tinyint(4) DEFAULT NULL,
  `email` varchar(45) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`,`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Data for table: category
--
LOCK TABLES `category` WRITE;
ALTER TABLE `category` DISABLE KEYS;

INSERT INTO `category` (`id`,`name`) VALUES (1,'Selecione a categoria'),(2,'Mulheres procurando Homens'),(3,'Mulheres procurando Mulheres'),(4,'Homens procurando Mulheres'),(5,'	Homens procurando Homens');

ALTER TABLE `category` ENABLE KEYS;
UNLOCK TABLES;
COMMIT;

--
-- Data for table: city
--
LOCK TABLES `city` WRITE;
ALTER TABLE `city` DISABLE KEYS;

INSERT INTO `city` (`id`,`name`) VALUES (1,'Selecione a cidade'),(2,'Açores'),(3,'Aveiro'),(4,'Beja'),(5,'Braga'),(6,'Bragança'),(7,'Castelo Branco'),(8,'Coimbra'),(9,'Évora'),(10,'Faro'),(11,'Guarda'),(12,'Leiria'),(13,'Lisboa'),(14,'Madeira'),(15,'Portalegre'),(16,'Porto'),(17,'Santarém'),(18,'Setúbal'),(19,'Viana do Castelo'),(20,'Vila Real'),(21,'Viseu');

ALTER TABLE `city` ENABLE KEYS;
UNLOCK TABLES;
COMMIT;

--
-- Data for table: image
--
LOCK TABLES `image` WRITE;
ALTER TABLE `image` DISABLE KEYS;

-- Table contains no data

ALTER TABLE `image` ENABLE KEYS;
UNLOCK TABLES;
COMMIT;

--
-- Data for table: publicity
--
LOCK TABLES `publicity` WRITE;
ALTER TABLE `publicity` DISABLE KEYS;

-- Table contains no data

ALTER TABLE `publicity` ENABLE KEYS;
UNLOCK TABLES;
COMMIT;

--
-- Data for table: sex
--
LOCK TABLES `sex` WRITE;
ALTER TABLE `sex` DISABLE KEYS;

-- Table contains no data

ALTER TABLE `sex` ENABLE KEYS;
UNLOCK TABLES;
COMMIT;

--
-- Data for table: user
--
LOCK TABLES `user` WRITE;
ALTER TABLE `user` DISABLE KEYS;

-- Table contains no data

ALTER TABLE `user` ENABLE KEYS;
UNLOCK TABLES;
COMMIT;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;

