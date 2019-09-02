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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='дерево категорий';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalog_category`
--

LOCK TABLES `catalog_category` WRITE;
/*!40000 ALTER TABLE `catalog_category` DISABLE KEYS */;
/*!40000 ALTER TABLE `catalog_category` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `catalog_category2tovar`
--

LOCK TABLES `catalog_category2tovar` WRITE;
/*!40000 ALTER TABLE `catalog_category2tovar` DISABLE KEYS */;
/*!40000 ALTER TABLE `catalog_category2tovar` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `catalog_currency`
--

LOCK TABLES `catalog_currency` WRITE;
/*!40000 ALTER TABLE `catalog_currency` DISABLE KEYS */;
INSERT INTO `catalog_currency` VALUES ('EUR',1,71.7100,300,'978',0,71.710000000000),('RUB',1,1.0000,100,'643',1,1.000000000000),('USD',1,64.8100,200,'840',0,64.810000000000);
/*!40000 ALTER TABLE `catalog_currency` ENABLE KEYS */;
UNLOCK TABLES;

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
  UNIQUE KEY `code` (`code`),
  KEY `is_default` (`is_default`)
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
-- Table structure for table `catalog_price_type`
--

DROP TABLE IF EXISTS `catalog_price_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catalog_price_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(255) DEFAULT NULL,
  `is_base` int(11) DEFAULT NULL COMMENT 'флаг базовой цены',
  `xml_id` char(127) DEFAULT NULL COMMENT 'связь с 1С',
  PRIMARY KEY (`id`),
  KEY `is_base` (`is_base`),
  KEY `xml_id` (`xml_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Типы цен';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalog_price_type`
--

LOCK TABLES `catalog_price_type` WRITE;
/*!40000 ALTER TABLE `catalog_price_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `catalog_price_type` ENABLE KEYS */;
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
  `poz` int(11) DEFAULT '0',
  `sysname` char(50) DEFAULT NULL COMMENT 'системное имя поля',
  PRIMARY KEY (`id`),
  KEY `xml_id` (`xml_id`),
  KEY `sysname` (`sysname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='справочник характеристик товара';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalog_properties`
--

LOCK TABLES `catalog_properties` WRITE;
/*!40000 ALTER TABLE `catalog_properties` DISABLE KEYS */;
/*!40000 ALTER TABLE `catalog_properties` ENABLE KEYS */;
UNLOCK TABLES;

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
  KEY `xml_id` (`xml_id`),
  CONSTRAINT `catalog_properties_list_fk11` FOREIGN KEY (`catalog_properties`) REFERENCES `catalog_properties` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='варианты значений характеристик';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalog_properties_list`
--

LOCK TABLES `catalog_properties_list` WRITE;
/*!40000 ALTER TABLE `catalog_properties_list` DISABLE KEYS */;
/*!40000 ALTER TABLE `catalog_properties_list` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='склад магазина';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalog_store`
--

LOCK TABLES `catalog_store` WRITE;
/*!40000 ALTER TABLE `catalog_store` DISABLE KEYS */;
/*!40000 ALTER TABLE `catalog_store` ENABLE KEYS */;
UNLOCK TABLES;

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
  `sku` char(100) DEFAULT NULL COMMENT 'Код артикула',
  `name` char(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL COMMENT 'общий остаток товара',
  `url` char(255) DEFAULT NULL,
  `poz` int(11) DEFAULT NULL,
  `anons` text,
  `info` text,
  `title` char(255) DEFAULT NULL,
  `keywords` char(255) DEFAULT NULL,
  `description` varchar(5000) DEFAULT NULL,
  `new_session` char(127) DEFAULT NULL COMMENT 'новый товар - сессия сеанса',
  `new_date` datetime DEFAULT NULL COMMENT 'Новый товар - дата создания',
  PRIMARY KEY (`id`),
  KEY `public` (`public`),
  KEY `name` (`name`),
  KEY `new_session` (`new_session`,`new_date`),
  KEY `xml_id` (`xml_id`),
  KEY `sku` (`sku`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='сам товар';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalog_tovar`
--

LOCK TABLES `catalog_tovar` WRITE;
/*!40000 ALTER TABLE `catalog_tovar` DISABLE KEYS */;
/*!40000 ALTER TABLE `catalog_tovar` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_CREATE_USER' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `catalog_tovar_before_del_tr` BEFORE DELETE ON `catalog_tovar`
  FOR EACH ROW
BEGIN
update storage set todelete=1 where razdel='catalog_tovar_gallery' and id in(select id from catalog_tovar_gallery where `catalog_tovar`= OLD.id);
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

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
  `catalog_price_type` int(11) DEFAULT NULL COMMENT 'ID типа цены',
  `catalog_tovar_sku_properties` int(11) DEFAULT NULL COMMENT 'ID комбинации хар-к или null',
  `value` decimal(11,2) DEFAULT NULL,
  `vat_in` int(11) DEFAULT NULL COMMENT '1-НДС включен в цену',
  `vat_value` decimal(11,2) DEFAULT NULL COMMENT 'значение НДС',
  PRIMARY KEY (`id`),
  KEY `catalog_currency` (`catalog_currency`),
  KEY `catalog_tovar` (`catalog_tovar`),
  KEY `catalog_tovar_properties` (`catalog_tovar_sku_properties`),
  KEY `catalog_price_type` (`catalog_price_type`),
  CONSTRAINT `catalog_tovar_currency_fk` FOREIGN KEY (`catalog_currency`) REFERENCES `catalog_currency` (`currency`) ON DELETE CASCADE,
  CONSTRAINT `catalog_tovar_currency_fk1` FOREIGN KEY (`catalog_tovar`) REFERENCES `catalog_tovar` (`id`) ON DELETE CASCADE,
  CONSTRAINT `catalog_tovar_currency_fk2` FOREIGN KEY (`catalog_tovar_sku_properties`) REFERENCES `catalog_tovar_sku_properties` (`id_combination`) ON DELETE CASCADE,
  CONSTRAINT `catalog_tovar_currency_fk3` FOREIGN KEY (`catalog_price_type`) REFERENCES `catalog_price_type` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='цены товара';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalog_tovar_currency`
--

LOCK TABLES `catalog_tovar_currency` WRITE;
/*!40000 ALTER TABLE `catalog_tovar_currency` DISABLE KEYS */;
/*!40000 ALTER TABLE `catalog_tovar_currency` ENABLE KEYS */;
UNLOCK TABLES;

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
  `coefficient` int(11) DEFAULT NULL COMMENT 'коэффициент ед. измерения',
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
-- Dumping data for table `catalog_tovar_gabarits`
--

LOCK TABLES `catalog_tovar_gabarits` WRITE;
/*!40000 ALTER TABLE `catalog_tovar_gabarits` DISABLE KEYS */;
/*!40000 ALTER TABLE `catalog_tovar_gabarits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalog_tovar_gallery`
--

DROP TABLE IF EXISTS `catalog_tovar_gallery`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catalog_tovar_gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `catalog_tovar` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `catalog_tovar` (`catalog_tovar`),
  CONSTRAINT `catalog_tovar_gallery_fk` FOREIGN KEY (`catalog_tovar`) REFERENCES `catalog_tovar` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='галерея товара';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalog_tovar_gallery`
--

LOCK TABLES `catalog_tovar_gallery` WRITE;
/*!40000 ALTER TABLE `catalog_tovar_gallery` DISABLE KEYS */;
/*!40000 ALTER TABLE `catalog_tovar_gallery` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `catalog_tovar_properties`
--

LOCK TABLES `catalog_tovar_properties` WRITE;
/*!40000 ALTER TABLE `catalog_tovar_properties` DISABLE KEYS */;
/*!40000 ALTER TABLE `catalog_tovar_properties` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='склад';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalog_tovar_store`
--

LOCK TABLES `catalog_tovar_store` WRITE;
/*!40000 ALTER TABLE `catalog_tovar_store` DISABLE KEYS */;
/*!40000 ALTER TABLE `catalog_tovar_store` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-09-02 10:35:42
