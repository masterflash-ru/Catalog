-- MySQL dump 10.13  Distrib 5.6.45, for FreeBSD12.0 (i386)
--
-- Host: localhost    Database: t
-- ------------------------------------------------------
-- Server version	5.6.45-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `catalog_category`
--

DROP TABLE IF EXISTS `catalog_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catalog_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subid` int(11) DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  `xml_id` char(127) DEFAULT NULL,
  `name` char(255) DEFAULT NULL,
  `url` char(255) DEFAULT NULL,
  `poz` int(11) DEFAULT NULL,
  `public` int(11) DEFAULT NULL,
  `info` text,
  `title` char(255) DEFAULT NULL,
  `keywords` char(255) DEFAULT NULL,
  `description` varchar(5000) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subid` (`subid`,`level`),
  KEY `url` (`url`),
  KEY `public` (`public`),
  KEY `xml_id` (`xml_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='дерево категорий';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `catalog_category2tovar`
--

DROP TABLE IF EXISTS `catalog_category2tovar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catalog_category2tovar` (
  `catalog_category` int(11) NOT NULL,
  `catalog_tovar` int(11) NOT NULL,
  PRIMARY KEY (`catalog_category`,`catalog_tovar`),
  KEY `catalog_category2tovar_fk1` (`catalog_tovar`),
  CONSTRAINT `catalog_category2tovar_fk` FOREIGN KEY (`catalog_category`) REFERENCES `catalog_category` (`id`) ON DELETE CASCADE,
  CONSTRAINT `catalog_category2tovar_fk1` FOREIGN KEY (`catalog_tovar`) REFERENCES `catalog_tovar` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='товар-категории';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `catalog_currency`
--

DROP TABLE IF EXISTS `catalog_currency`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catalog_currency` (
  `currency` char(3) NOT NULL COMMENT 'буквенный код',
  `amount_cnt` int(11) NOT NULL DEFAULT '1' COMMENT 'Номинал',
  `amount` decimal(18,4) DEFAULT NULL COMMENT 'курс по умолчанию',
  `poz` int(11) NOT NULL DEFAULT '100' COMMENT 'порядок в списках',
  `numcode` char(3) DEFAULT NULL COMMENT 'цифровой код',
  `base` int(1) NOT NULL DEFAULT '0' COMMENT 'флаг базовой валюты',
  `current_base_rate` decimal(26,12) DEFAULT NULL COMMENT 'текущий установленый курс обмена',
  PRIMARY KEY (`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `catalog_file`
--

DROP TABLE IF EXISTS `catalog_file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catalog_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='файлы товара';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalog_file`
--

LOCK TABLES `catalog_file` WRITE;
/*!40000 ALTER TABLE `catalog_file` DISABLE KEYS */;
/*!40000 ALTER TABLE `catalog_file` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalog_measure`
--

DROP TABLE IF EXISTS `catalog_measure`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catalog_measure` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` int(11) NOT NULL,
  `measure_title` varchar(500) DEFAULT NULL,
  `symbol_rus` varchar(20) DEFAULT NULL,
  `symbol_intl` varchar(20) DEFAULT NULL,
  `symbol_letter_intl` varchar(20) DEFAULT NULL,
  `is_default` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='единицы измерения';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalog_measure`
--

LOCK TABLES `catalog_measure` WRITE;
/*!40000 ALTER TABLE `catalog_measure` DISABLE KEYS */;
INSERT INTO `catalog_measure` VALUES (1,6,'Метр','м','m','MTR',0),(2,112,'Литр','л.','l','LTR',0),(3,163,'Грамм','г','g','GRM',0),(4,166,'Килограмм','кг','kg','KGM',0),(5,796,'Штука','шт','pc. 1','PCE. NMB',1);
/*!40000 ALTER TABLE `catalog_measure` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalog_properties`
--

DROP TABLE IF EXISTS `catalog_properties`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catalog_properties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(255) DEFAULT NULL,
  `xml_id` char(127) DEFAULT NULL,
  `type` char(20) DEFAULT NULL COMMENT 'тип:str,voc',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='справочник характеристик товара';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `catalog_properties_list`
--

DROP TABLE IF EXISTS `catalog_properties_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catalog_properties_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `xml_id` char(127) DEFAULT NULL,
  `catalog_properties` int(11) DEFAULT NULL,
  `value` char(127) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `catalog_properties` (`catalog_properties`),
  CONSTRAINT `catalog_properties_list_fk11` FOREIGN KEY (`catalog_properties`) REFERENCES `catalog_properties` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='варианты значений характеристик';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `catalog_sku_properties`
--

DROP TABLE IF EXISTS `catalog_sku_properties`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catalog_sku_properties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(255) DEFAULT NULL,
  `xml_id` char(127) DEFAULT NULL,
  `type` char(20) DEFAULT NULL COMMENT 'тип:str,voc',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='справочник характеристик товара';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalog_sku_properties`
--

LOCK TABLES `catalog_sku_properties` WRITE;
/*!40000 ALTER TABLE `catalog_sku_properties` DISABLE KEYS */;
/*!40000 ALTER TABLE `catalog_sku_properties` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalog_sku_properties_list`
--

DROP TABLE IF EXISTS `catalog_sku_properties_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catalog_sku_properties_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `xml_id` char(127) DEFAULT NULL,
  `catalog_properties` int(11) DEFAULT NULL,
  `value` char(127) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `catalog_properties` (`catalog_properties`),
  CONSTRAINT `catalog_properties_list_fk` FOREIGN KEY (`catalog_properties`) REFERENCES `catalog_sku_properties` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='варианты значений характеристик';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalog_sku_properties_list`
--

LOCK TABLES `catalog_sku_properties_list` WRITE;
/*!40000 ALTER TABLE `catalog_sku_properties_list` DISABLE KEYS */;
/*!40000 ALTER TABLE `catalog_sku_properties_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalog_store`
--

DROP TABLE IF EXISTS `catalog_store`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catalog_store` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(127) DEFAULT NULL COMMENT 'имя склада',
  `public` int(1) NOT NULL DEFAULT '1' COMMENT 'активен',
  `address` varchar(245) NOT NULL COMMENT 'адрес',
  `description` text COMMENT 'свободное описание',
  `xml_id` varchar(255) DEFAULT NULL COMMENT 'согласование с 1С',
  `poz` int(11) NOT NULL DEFAULT '100' COMMENT 'порядок',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='склад магазина';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `catalog_tovar`
--

DROP TABLE IF EXISTS `catalog_tovar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catalog_tovar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `public` int(11) DEFAULT NULL,
  `xml_id` char(127) DEFAULT NULL,
  `name` char(255) DEFAULT NULL,
  `url` char(255) DEFAULT NULL,
  `poz` int(11) DEFAULT NULL,
  `anons` text,
  `info` text,
  `title` char(255) DEFAULT NULL,
  `keywords` char(255) DEFAULT NULL,
  `description` varchar(5000) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`),
  KEY `public` (`public`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='сам товар';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `catalog_tovar_currency`
--

DROP TABLE IF EXISTS `catalog_tovar_currency`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catalog_tovar_currency` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `catalog_tovar` int(11) DEFAULT NULL,
  `catalog_currency` char(3) DEFAULT NULL COMMENT 'код валюты',
  `catalog_tovar_sku_properties` int(11) DEFAULT NULL COMMENT 'ID комбинации хар-к или null',
  `value` decimal(11,2) DEFAULT NULL,
  `nds` int(11) DEFAULT NULL COMMENT '1-НДС включен в цену',
  PRIMARY KEY (`id`),
  KEY `catalog_currency` (`catalog_currency`),
  KEY `catalog_tovar` (`catalog_tovar`),
  KEY `catalog_tovar_properties` (`catalog_tovar_sku_properties`),
  CONSTRAINT `catalog_tovar_currency_fk` FOREIGN KEY (`catalog_currency`) REFERENCES `catalog_currency` (`currency`) ON DELETE CASCADE,
  CONSTRAINT `catalog_tovar_currency_fk1` FOREIGN KEY (`catalog_tovar`) REFERENCES `catalog_tovar` (`id`) ON DELETE CASCADE,
  CONSTRAINT `catalog_tovar_currency_fk2` FOREIGN KEY (`catalog_tovar_sku_properties`) REFERENCES `catalog_tovar_sku_properties` (`id_combination`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='цены товара';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `catalog_tovar_gabarits`
--

DROP TABLE IF EXISTS `catalog_tovar_gabarits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catalog_tovar_gabarits` (
  `catalog_tovar` int(11) NOT NULL DEFAULT '0',
  `catalog_tovar_sku_properties` int(11) DEFAULT NULL COMMENT 'хар-ка товара, или null',
  `length` int(11) DEFAULT NULL COMMENT 'длина',
  `height` int(11) DEFAULT NULL COMMENT 'высота',
  `width` int(11) DEFAULT NULL COMMENT 'ширина',
  `weight` int(11) DEFAULT NULL COMMENT 'вес',
  `catalog_measure_code` int(11) DEFAULT NULL COMMENT 'мера (шт,литры...) КОД',
  PRIMARY KEY (`catalog_tovar`),
  KEY `catalog_tovar` (`catalog_tovar`),
  KEY `catalog_tovar_properties` (`catalog_tovar_sku_properties`),
  KEY `catalog_measure_code` (`catalog_measure_code`),
  CONSTRAINT `catalog_tovar_gabarits_fk` FOREIGN KEY (`catalog_measure_code`) REFERENCES `catalog_measure` (`code`) ON DELETE CASCADE,
  CONSTRAINT `catalog_tovar_measure_fk` FOREIGN KEY (`catalog_tovar`) REFERENCES `catalog_tovar` (`id`) ON DELETE CASCADE,
  CONSTRAINT `catalog_tovar_measure_fk1` FOREIGN KEY (`catalog_tovar_sku_properties`) REFERENCES `catalog_tovar_sku_properties` (`id_combination`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='мера,вес,габарит';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `catalog_tovar_properties`
--

DROP TABLE IF EXISTS `catalog_tovar_properties`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catalog_tovar_properties` (
  `catalog_tovar` int(11) DEFAULT NULL,
  `catalog_properties_list` int(11) DEFAULT NULL,
  `catalog_properties` int(11) DEFAULT NULL,
  `value` varchar(4000) DEFAULT NULL,
  KEY `catalog_tovar` (`catalog_tovar`),
  KEY `catalog_properties_list` (`catalog_properties_list`),
  KEY `catalog_properties` (`catalog_properties`),
  CONSTRAINT `catalog_tovar_properties_fk11` FOREIGN KEY (`catalog_tovar`) REFERENCES `catalog_tovar` (`id`) ON DELETE CASCADE,
  CONSTRAINT `catalog_tovar_properties_fk12` FOREIGN KEY (`catalog_properties_list`) REFERENCES `catalog_properties_list` (`id`) ON DELETE CASCADE,
  CONSTRAINT `catalog_tovar_properties_fk3` FOREIGN KEY (`catalog_properties`) REFERENCES `catalog_properties` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='собственно характиристики товара';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `catalog_tovar_sku_properties`
--

DROP TABLE IF EXISTS `catalog_tovar_sku_properties`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catalog_tovar_sku_properties` (
  `id_combination` int(11) NOT NULL COMMENT 'номер комбинации хар-тик',
  `catalog_tovar` int(11) DEFAULT NULL,
  `catalog_properties_list` int(11) DEFAULT NULL,
  `catalog_properties` int(11) DEFAULT NULL,
  `value` char(127) DEFAULT NULL,
  KEY `catalog_tovar` (`catalog_tovar`),
  KEY `catalog_properties_list` (`catalog_properties_list`),
  KEY `catalog_properties` (`catalog_properties`),
  KEY `id` (`id_combination`),
  CONSTRAINT `catalog_tovar_properties_fk` FOREIGN KEY (`catalog_tovar`) REFERENCES `catalog_tovar` (`id`) ON DELETE CASCADE,
  CONSTRAINT `catalog_tovar_properties_fk1` FOREIGN KEY (`catalog_properties_list`) REFERENCES `catalog_sku_properties_list` (`id`) ON DELETE CASCADE,
  CONSTRAINT `catalog_tovar_properties_fk2` FOREIGN KEY (`catalog_properties`) REFERENCES `catalog_sku_properties` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='собственно характиристики товара';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalog_tovar_sku_properties`
--

LOCK TABLES `catalog_tovar_sku_properties` WRITE;
/*!40000 ALTER TABLE `catalog_tovar_sku_properties` DISABLE KEYS */;
/*!40000 ALTER TABLE `catalog_tovar_sku_properties` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalog_tovar_store`
--

DROP TABLE IF EXISTS `catalog_tovar_store`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catalog_tovar_store` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `catalog_store` int(11) DEFAULT NULL,
  `xml_id` char(127) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `catalog_tovar` int(11) DEFAULT NULL,
  `catalog_tovar_sku_properties` int(11) DEFAULT NULL COMMENT 'тип хар-ки, если есть, null - обычный товар',
  PRIMARY KEY (`id`),
  KEY `catalog_store` (`catalog_store`),
  KEY `catalog_tovar` (`catalog_tovar`),
  KEY `catalog_properties` (`catalog_tovar_sku_properties`),
  CONSTRAINT `catalog_tovar_store_fk` FOREIGN KEY (`catalog_store`) REFERENCES `catalog_store` (`id`) ON DELETE CASCADE,
  CONSTRAINT `catalog_tovar_store_fk1` FOREIGN KEY (`catalog_tovar`) REFERENCES `catalog_tovar` (`id`) ON DELETE CASCADE,
  CONSTRAINT `catalog_tovar_store_fk2` FOREIGN KEY (`catalog_tovar_sku_properties`) REFERENCES `catalog_tovar_sku_properties` (`id_combination`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='склад';
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-08-22 12:30:25
